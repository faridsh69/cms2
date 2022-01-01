<?php

namespace App\Policies;

use App\Cms\Policy;

class AddressPolicy extends Policy
{
	public string $modelNameSlug = 'address';
}
