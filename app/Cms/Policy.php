<?php

declare(strict_types=1);

namespace App\Cms;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class Policy
{
    use HandlesAuthorization;

    public string $modelNameSlug = 'user';

    final public function index(User $user)
    {
        return $user->can($this->modelNameSlug . '_index');
    }

    final public function view(User $user, $list)
    {
        return $user->can($this->modelNameSlug . '_view');
    }

    final public function create(User $user)
    {
        return $user->can($this->modelNameSlug . '_create');
    }

    final public function update(User $user, $list)
    {
        return $user->can($this->modelNameSlug . '_update');
    }

    final public function delete(User $user, $list)
    {
        return $user->can($this->modelNameSlug . '_delete');
    }
}
