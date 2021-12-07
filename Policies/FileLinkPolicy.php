<?php

namespace Modules\FileLinkModule\Policies;

use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\FileLinkModule\Entities\FileLink;

class FileLinkPolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    protected $useString    = 'Use File Links';
    protected $manageString = 'Manage File Links';

    public function viewAny(User $user)
    {
        return $this->checkPermission($user, $this->useString);
    }

    public function view(User $user, FileLink $fileLink)
    {
        return $this->checkPermission($user, $this->useString);
    }

    public function create(User $user)
    {
        return $this->checkPermission($user, $this->useString);
    }

    public function edit(User $user, FileLink $fileLink)
    {
        if($this->checkPermission($user, $this->useString) && $fileLink->user_id === $user->user_id)
        {
            return true;
        }

        return $this->checkPermission($user, $this->manageString);
    }

    public function delete(User $user, FileLink $fileLink)
    {
        if($this->checkPermission($user, $this->useString) && $fileLink->user_id === $user->user_id)
        {
            return true;
        }

        return $this->checkPermission($user, $this->manageString);
    }
}
