<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasRoleBasedNavigation
{
    /**
     * Check if the current user has any of the specified roles
     */
    public static function userHasRole(array $roles): bool
    {
        $user = Auth::user();
        if (!$user || !$user->role_id) {
            return false;
        }
        
        // Get the role directly from the database
        $role = \App\Models\Role::find($user->role_id);
        
        return $role && in_array($role->name, $roles);
    }
    
    /**
     * Check if the current user is an admin
     */
    public static function userIsAdmin(): bool
    {
        return self::userHasRole(['Admin']);
    }
    
    /**
     * Check if the current user is a dentist
     */
    public static function userIsDentist(): bool
    {
        return self::userHasRole(['Dentist']);
    }
    
    /**
     * Check if the current user is a receptionist
     */
    public static function userIsReceptionist(): bool
    {
        return self::userHasRole(['Receptionist']);
    }
    
    /**
     * Check if the current user has any valid role
     */
    public static function userHasValidRole(): bool
    {
        return self::userHasRole(['Admin', 'Dentist', 'Receptionist']);
    }
}