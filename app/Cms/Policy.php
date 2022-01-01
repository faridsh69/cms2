<?php

namespace App\Cms;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public string $modelNameSlug = 'user';

    public function index(User $user)
    {
        return $user->can($this->modelNameSlug . '_index');
    }

    public function view(User $user, $list)
    {
        return $user->can($this->modelNameSlug . '_view');
    }

    public function create(User $user)
    {
        return $user->can($this->modelNameSlug . '_create');
    }

    public function update(User $user, $list)
    {
        return $user->can($this->modelNameSlug . '_update');
    }

    public function delete(User $user, $list)
    {
        return $user->can($this->modelNameSlug . '_delete');
    }
}
