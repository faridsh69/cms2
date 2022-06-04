<?php

declare(strict_types=1);

namespace App\Cms;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class AuthPolicy
{
	use HandlesAuthorization;

	// Define in each policies to find model slug.
	public string $modelNameSlug;

	final public function index(User $user): bool
	{
		return true;
	}

	final public function view(User $user, $list): bool
	{
		if ($user->can($this->modelNameSlug . '_view')) {
			return true;
		}

		return $user->id === $list->user_id;
	}

	final public function create(User $user): bool
	{
		return true;
	}

	final public function update(User $user, $list): bool
	{
		if ($user->can($this->modelNameSlug . '_update')) {
			return true;
		}

		return $user->id === $list->user_id;
	}

	final public function delete(User $user, $list): bool
	{
		if ($user->can($this->modelNameSlug . '_delete')) {
			return true;
		}

		return $user->id === $list->user_id;
	}
}
