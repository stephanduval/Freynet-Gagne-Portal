<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info('ProjectController::index - Fetching projects', [
            'per_page' => $request->input('per_page'),
            'page' => $request->input('page'),
            'all_params' => $request->all(),
        ]);

        $user = Auth::user();

        $query = Project::with(['client:id,name,email,department', 'client.companies', 'company', 'messages.attachments']);

        // Admin users can see all projects
        if ($user->roles()->where('name', 'Admin')->exists()) {
            // Filter by client for admin users if client_id is provided
            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }
        } else {
            // Non-admin users see all projects from users in their company
            $userCompanyIds = $user->companies()->pluck('companies.id')->toArray();

            if (! empty($userCompanyIds)) {
                // Get all users who belong to the same company/companies
                $companyUserIds = User::whereHas('companies', function ($q) use ($userCompanyIds) {
                    $q->whereIn('companies.id', $userCompanyIds);
                })->pluck('id')->toArray();

                // Filter projects to those created by users in the same company
                $query->whereIn('client_id', $companyUserIds);

                Log::info('Filtering projects by company', [
                    'user_id' => $user->id,
                    'user_companies' => $userCompanyIds,
                    'company_users' => $companyUserIds,
                ]);
            } else {
                // If user has no company, only show their own projects
                $query->where('client_id', $user->id);
            }
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by service type if provided
        if ($request->has('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // Filter by date range for deadline
        if ($request->has('deadline_from')) {
            $query->where('deadline', '>=', $request->deadline_from);
        }
        if ($request->has('deadline_to')) {
            $query->where('deadline', '<=', $request->deadline_to);
        }

        // Filter by search term (title, property, or service_description)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('property', 'like', "%{$search}%")
                    ->orWhere('service_description', 'like', "%{$search}%");
            });
        }

        // Handle sorting
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Paginate results
        $perPage = $request->input('per_page', 10);

        Log::info('ProjectController::index - Before pagination', [
            'per_page' => $perPage,
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings(),
        ]);

        // Handle "All" option (per_page = -1)
        if ($perPage === -1) {
            $projects = $query->get();
            $totalProjects = $projects->count();

            Log::info('ProjectController::index - Returning all projects', [
                'total_projects' => $totalProjects,
                'per_page' => $totalProjects,
                'current_page' => 1,
                'last_page' => 1,
            ]);

            return response()->json([
                'data' => ProjectResource::collection($projects),
                'total' => $totalProjects,
                'current_page' => 1,
                'per_page' => $totalProjects,
                'last_page' => 1,
                'from' => $totalProjects > 0 ? 1 : 0,
                'to' => $totalProjects,
            ]);
        }

        $projects = $query->paginate($perPage);

        Log::info('ProjectController::index - Returning paginated projects', [
            'total' => $projects->total(),
            'per_page' => $projects->perPage(),
            'current_page' => $projects->currentPage(),
            'last_page' => $projects->lastPage(),
        ]);

        // Transform the paginated data using ProjectResource
        $transformedProjects = ProjectResource::collection($projects->getCollection());

        // Create response with pagination metadata
        $response = $projects->toArray();
        $response['data'] = $transformedProjects->toArray($request);

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('ProjectController::store - Creating new project');

        // Validate request data
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'property' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'status' => 'nullable|in:received,in_progress,delivered',
            'time_preference' => 'nullable|in:before_noon,before_4pm,anytime',
            'deadline' => 'nullable|date',
            'service_type' => 'nullable|in:translation,revision,modifications,transcription,voice_over,other',
            'service_description' => 'nullable|string',
        ]);

        // Only admins can create projects for other clients
        $user = Auth::user();

        if (! $user->roles()->where('name', 'Admin')->exists() && $validated['client_id'] !== $user->id) {
            return response()->json(['message' => 'Unauthorized to create project for another client'], 403);
        }

        // Set default values if not provided
        $validated['status'] = $validated['status'] ?? 'received';
        $validated['time_preference'] = $validated['time_preference'] ?? 'anytime';

        // Create the project
        $project = Project::create($validated);

        return new ProjectResource($project->load(['client:id,name,email,department', 'client.companies', 'company', 'messages.attachments']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Log::info('ProjectController::show - Starting request', [
            'project_id' => $id,
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'request_headers' => request()->headers->all(),
        ]);

        // Log authentication state
        Log::info('Auth state:', [
            'is_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'user_role' => auth()->user()?->roles->first()?->name,
            'token' => request()->bearerToken() ? 'Present' : 'Missing',
        ]);

        try {
            $project = Project::with(['client:id,name,email,department', 'client.companies', 'company', 'messages.attachments'])->findOrFail($id);
            Log::info('Project found:', [
                'project_id' => $project->id,
                'client_id' => $project->client_id,
                'auth_user_id' => auth()->id(),
                'project_status' => $project->status,
            ]);

            // Use policy authorization
            if (! auth()->user()->can('view', $project)) {
                Log::warning('User not authorized to view project', [
                    'user_id' => auth()->id(),
                    'user_role' => auth()->user()?->roles->first()?->name,
                    'project_id' => $id,
                    'project_client_id' => $project->client_id,
                ]);

                return response()->json(['message' => 'Unauthorized to view this project'], 403);
            }

            Log::info('Project access granted', [
                'user_id' => auth()->id(),
                'project_id' => $id,
            ]);

            return new ProjectResource($project);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::show', [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'project_id' => $id,
            ]);

            return response()->json(['message' => 'Failed to fetch project'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info("ProjectController::update - Updating project {$id}");

        try {
            $project = Project::findOrFail($id);

            // Use policy authorization
            if (! auth()->user()->can('update', $project)) {
                return response()->json(['message' => 'Unauthorized to update this project'], 403);
            }

            // Validate based on user role
            $user = auth()->user();

            if ($user->roles()->where('name', 'Client')->exists()) {
                $validated = $request->validate([
                    'property' => 'nullable|string|max:255',
                    'time_preference' => 'nullable|in:before_noon,before_4pm,anytime',
                    'service_description' => 'nullable|string',
                    'latest_completion_date' => 'nullable|date|after:deadline',
                ]);
            } else {
                $validated = $request->validate([
                    'client_id' => 'sometimes|exists:users,id',
                    'title' => 'sometimes|string|max:255',
                    'property' => 'nullable|string|max:255',
                    'contact_email' => 'nullable|email|max:255',
                    'status' => 'nullable|in:received,in_progress,delivered',
                    'time_preference' => 'nullable|in:before_noon,before_4pm,anytime',
                    'deadline' => 'nullable|date',
                    'service_type' => 'nullable|in:translation,revision,modifications,transcription,voice_over,other',
                    'service_description' => 'nullable|string',
                    'latest_completion_date' => 'nullable|date|after:deadline',
                ]);
            }

            $project->update($validated);

            return new ProjectResource($project->load(['client:id,name,email,department', 'client.companies', 'company', 'messages.attachments']));
        } catch (\Exception $e) {
            Log::error('Error updating project: '.$e->getMessage());

            return response()->json(['message' => 'Failed to update project'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Log::info("ProjectController::destroy - Deleting project {$id}");

        try {
            $project = Project::findOrFail($id);

            // Use policy authorization
            if (! auth()->user()->can('delete', $project)) {
                return response()->json(['message' => 'Unauthorized to delete projects'], 403);
            }

            // Check if project has messages
            $messagesCount = $project->messages()->count();
            if ($messagesCount > 0) {
                // Update messages to remove project association
                $project->messages()->update(['project_id' => null]);
            }

            $project->delete();

            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting project: '.$e->getMessage());

            return response()->json(['message' => 'Failed to delete project'], 500);
        }
    }

    /**
     * Bulk delete multiple projects.
     */
    public function bulkDestroy(Request $request)
    {
        Log::info('ProjectController::bulkDestroy - Bulk deleting projects');

        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer|exists:projects,id',
            ]);

            $ids = $validated['ids'];
            $projects = Project::whereIn('id', $ids)->get();

            // Check authorization for each project
            foreach ($projects as $project) {
                if (! auth()->user()->can('delete', $project)) {
                    return response()->json(['message' => 'Unauthorized to delete one or more projects'], 403);
                }
            }

            // Use a transaction to ensure data integrity
            \DB::transaction(function () use ($projects) {
                foreach ($projects as $project) {
                    // Check if project has messages and handle them
                    $messagesCount = $project->messages()->count();
                    if ($messagesCount > 0) {
                        // Update messages to remove project association
                        $project->messages()->update(['project_id' => null]);
                    }

                    $project->delete();
                }
            });

            $deletedCount = count($ids);
            Log::info("Bulk deleted {$deletedCount} projects", ['ids' => $ids]);

            return response()->json([
                'message' => "Successfully deleted {$deletedCount} projects.",
                'deleted_count' => $deletedCount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error bulk deleting projects: ', [
                'message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json(['message' => 'Failed to delete projects.'], 500);
        }
    }

    /**
     * Get summary statistics for projects
     */
    public function summary()
    {
        Log::info('ProjectController::summary - Generating project summary');

        $user = Auth::user();

        $query = Project::query();

        // Admin users see all projects
        if (! $user->roles()->where('name', 'Admin')->exists()) {
            // Non-admin users see all projects from users in their company
            $userCompanyIds = $user->companies()->pluck('companies.id')->toArray();

            if (! empty($userCompanyIds)) {
                // Get all users who belong to the same company/companies
                $companyUserIds = User::whereHas('companies', function ($q) use ($userCompanyIds) {
                    $q->whereIn('companies.id', $userCompanyIds);
                })->pluck('id')->toArray();

                // Filter projects to those created by users in the same company
                $query->whereIn('client_id', $companyUserIds);
            } else {
                // If user has no company, only show their own projects
                $query->where('client_id', $user->id);
            }
        }

        $summary = [
            'total' => $query->count(),
            'received' => (clone $query)->where('status', 'received')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'due_today' => (clone $query)->whereDate('deadline', now()->format('Y-m-d'))->count(),
            'overdue' => (clone $query)->whereDate('deadline', '<', now()->format('Y-m-d'))
                ->whereNotIn('status', ['delivered'])->count(),
        ];

        return response()->json($summary);
    }
}
