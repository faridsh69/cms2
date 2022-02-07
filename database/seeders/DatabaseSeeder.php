<?php

namespace Database\Seeders;

use App\Cms\Seeder as CmsSeeder;
use Illuminate\Database\Seeder as LaravelSeeder;

class DatabaseSeeder extends LaravelSeeder
{
	public function run()
	{
		// Create some sample data for categories and tags
		// Also create important data for block and modules, settings, pages and users
		$this->call(Seeder001Categories::class);
		$this->call(Seeder002Tags::class);
		$this->call(Seeder003Users::class);
		$this->call(Seeder004Settings::class);
		$this->call(Seeder005Products::class);
		$this->call(Seeder006Pages::class);
		$this->call(Seeder007Blocks::class);
		$this->call(Seeder008Modules::class);
		$this->call(Seeder009Fields::class);
		$this->call(Seeder010Roles::class);

		// This file will automatically read list of models from config cms.seeder
		// Then will create 4 fake data based on model columns
		$this->call(CmsSeeder::class);

		// This line will automatically generate all data for Laravel CMS website
		// $this->call(CmsLaravelWebsiteSeeder::class);
	}
}
