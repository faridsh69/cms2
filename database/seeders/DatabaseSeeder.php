<?php

namespace Database\Seeders;

use App\Cms\Seeder as CmsSeeder;
use Illuminate\Database\Seeder as LaravelSeeder;

class DatabaseSeeder extends LaravelSeeder
{
	public function run()
	{
		$this->call(Seeder001Categories::class);
		$this->call(Seeder002Tags::class);
		$this->call(Seeder003Users::class);
		$this->call(Seeder004Settings::class);
		$this->call(Seeder005Products::class);
		$this->call(Seeder006Pages::class);
		$this->call(Seeder007Blocks::class);
		// $this->call(Seeder008Modules::class);
		$this->call(Seeder009Fields::class);
		$this->call(Seeder010Roles::class);

		$this->call(CmsSeeder::class);

		// $this->call(CmsLaravelWebsiteSeeder::class);
	}
}
