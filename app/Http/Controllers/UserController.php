<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
   

    $validated = $request->validate([
        'page' => 'integer|min:1',
        'itemsPerPage' => 'integer|min:1|max:100', // Limit items per page to prevent abuse
        'q' => 'nullable|string',
    ]);
    $page = $request->get('page', 1); // Default to page 1 if not provided
    $itemsPerPage = $request->get('itemsPerPage', 10); // Default to 10 items per page
    \Log::info('Pagination Request Parameters:', [
        'page' => $page,
        'itemsPerPage' => $itemsPerPage,
        'q' => $request->get('q'),
    ]);
    
    $users = User::query()
    ->select(['id', 'name', 'email']) // ✅ Only fetch necessary fields
    ->when($request->get('q'), function ($query, $search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
    })
    ->paginate($itemsPerPage, ['*'], 'page', $page);


// Transform users data only if there are results
$transformedUsers = $users->getCollection()->map(function ($user) {
    return [
        'id' => $user->id,
        'fullName' => $user->name,
        'email' => $user->email,
        'role' => 'user',
        'status' => 'active',
        'currentPlan' => 'basic',
        'avatar' => null,
        'billing' => 'auto',
        'user' => [
            'fullName' => $user->name,
            'email' => $user->email,
        ],
    ];
});

$paginatedResponse = $users->toArray();
$paginatedResponse['data'] = $transformedUsers->toArray(); // Ensure transformation

    return response()->json($paginatedResponse);
}



    public function addUser(Request $request)
    {
        \Log::info('Add User Request: ', $request->all());

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'company_id' => 'required|exists:companies,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        \Log::info('Validated Data: ', $validated);

        try {
            \DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'password_reset_required' => true,
            ]);

            \Log::info('User created: ', ['id' => $user->id]);

            // Assign company relationship
            $user->companies()->attach($validated['company_id']);
            \Log::info('Company assigned to user.', ['user_id' => $user->id, 'company_id' => $validated['company_id']]);

            // Assign role relationship
            $user->roles()->attach($validated['role_id']);
            \Log::info('Role assigned to user.', ['user_id' => $user->id, 'role_id' => $validated['role_id']]);

            \DB::commit();

            return response()->json(['message' => 'User created successfully.', 'user' => $user], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error Adding User: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Failed to create user.', 'details' => $e->getMessage()], 500);
        }
    }
    public function deleteUser($id)
{
    try {
        $user = User::findOrFail($id);

        \DB::transaction(function () use ($user) {
            $user->delete();
        });

        return response()->json(['message' => 'User deleted successfully.'], 200);
    } catch (\Exception $e) {
        \Log::error('Error deleting user: ', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to delete user.'], 500);
    }
}
public function showUser($id)
{
    try {
        $user = User::with(['companies', 'roles'])->findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'company_id' => $user->companies->first()?->id, // Assuming one company per user
            'role_id' => $user->roles->first()?->id, // Assuming one role per user
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching user details: ', ['message' => $e->getMessage()]);

        return response()->json(['error' => 'User not found.'], 404);
    }
}
public function updateUser(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|unique:users,email,' . $id,
            'company_id' => 'sometimes|required|exists:companies,id',
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);

        // Update user fields
        $user->update([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
        ]);

        // Update company relationship
        if (isset($validated['company_id'])) {
            $user->companies()->sync([$validated['company_id']]);
        }

        // Update role relationship
        if (isset($validated['role_id'])) {
            $user->roles()->sync([$validated['role_id']]);
        }

        return response()->json(['message' => 'User updated successfully.', 'user' => $user]);
    } catch (\Exception $e) {
        \Log::error('Error updating user: ', ['message' => $e->getMessage()]);

        return response()->json(['error' => 'Failed to update user.', 'details' => $e->getMessage()], 500);
    }
}
}
