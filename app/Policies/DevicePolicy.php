<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DevicePolicy
{

    public function before(?User $user, string $ability): bool|null
    {
        if ($user?->isAdministrator()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Device $device): Response
    {
        return $device->stock > 0 ? Response::allow() : Response::deny('Device is out of stock', 404);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Device $device): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Device $device): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(?User $user, Device $device): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Device $device): bool
    {
        return false;
    }
}
