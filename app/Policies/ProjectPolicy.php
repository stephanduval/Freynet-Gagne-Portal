<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the project list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Admin can view all projects
        if ($user->roles()->where('name', 'admin')->exists()) {
            return true;
        }

        // Check if the user and the project's client share a company
        return $this->usersShareCompany($user, $project->client);
    }

    /**
     * Check if two users share at least one company
     */
    private function usersShareCompany(User $user1, ?User $user2): bool
    {
        if (! $user2) {
            return false;
        }

        // Get company IDs for both users
        $user1CompanyIds = $user1->companies()->pluck('companies.id')->toArray();
        $user2CompanyIds = $user2->companies()->pluck('companies.id')->toArray();

        // Check if there's any overlap
        return ! empty(array_intersect($user1CompanyIds, $user2CompanyIds));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create projects directly
        return $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Admin can update all projects
        if ($user->roles()->where('name', 'admin')->exists()) {
            return true;
        }

        // Users can only update their own projects (not projects from others in their company)
        return $user->id === $project->client_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Only admin can delete projects
        return $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Only admin can restore projects
        return $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Only admin can force delete projects
        return $user->roles()->where('name', 'admin')->exists();
    }
}
