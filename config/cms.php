<?php

declare(strict_types=1);

$cms = [
	'config' => [
		'models_namespace' => 'App\Models\\',
		'faker_files' => '/app/public/static-files/faker/',
		'cms_files' => '/app/public/static-files/laravel-cms-website/',
		'default_images' => '/storage/static-files/default-model-images/',
		'upload_folder' => '/upload/',
	],
	'migration' => [
		'user',
		'category',
		'activity',
		'address',
		'advertise',
		'basket',
		'block',
		'blog',
		'car',
		'cinema',
		'comment',
		'factor',
		'field',
		'file',
		'follow',
		'food',
		'food-program',
		'form',
		'answer',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'like',
		'module',
		'movie',
		'music',
		'notification',
		'page',
		'post',
		'product',
		'rate',
		'restaurant',
		'setting-general',
		'setting-contact',
		'setting-developer',
		'shop',
		'showtime',
		'story',
		'tag',
		'tagend',
		'tour',
		'travel',
	],
	'seeder' => [
		'address',
		'advertise',
		'answer',
		'blog',
		'car',
		'category',
		'cinema',
		'factor',
		'field',
		'food',
		'food-program',
		'form',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'movie',
		'music',
		'post',
		'product',
		'restaurant',
		'shop',
		'showtime',
		'story',
		'tag',
		'tagend',
		'travel',
		'tour',
	],
	'policies' => [
		'activity',
		'advertise',
		'answer',
		'address',
		'block',
		'blog',
		'car',
		'category',
		'cinema',
		'comment',
		'factor',
		'field',
		'file',
		'follow',
		'food',
		'food-program',
		'form',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'like',
		'module',
		'movie',
		'music',
		'notification',
		'page',
		'permission',
		'post',
		'product',
		'rate',
		'restaurant',
		'role',
		'shop',
		'showtime',
		'story',
		'tag',
		'tagend',
		'tour',
		'travel',
		'user',
	],
	'admin_routes' => [
		'activity',
		'address',
		'advertise',
		'answer',
		'block',
		'blog',
		'car',
		'category',
		'cinema',
		'comment',
		'factor',
		'field',
		'file',
		'follow',
		'food',
		'food-program',
		'form',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'like',
		'module',
		'movie',
		'music',
		'notification',
		'page',
		'permission',
		'post',
		'product',
		'rate',
		'restaurant',
		'role',
		'shop',
		'showtime',
		'story',
		'tag',
		'tagend',
		'tour',
		'travel',
		'user',
	],
	'admin_tests' => [
		'address',
		'advertise',
		'answer',
		'block',
		'blog',
		'car',
		'category',
		'cinema',
		'comment',
		'factor',
		'field',
		'file',
		'follow',
		'food',
		'food-program',
		'form',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'like',
		'module',
		'movie',
		'music',
		'page',
		'post',
		'product',
		'rate',
		'restaurant',
		'shop',
		'showtime',
		'story',
		'tag',
		'tagend',
		'tour',
		'travel',
		'user',
	],
	'front_routes' => [
		'advertise',
		'blog',
		'car',
		'category',
		'cinema',
		'food',
		'food-program',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'movie',
		'music',
		'post',
		'product',
		'restaurant',
		'shop',
		'showtime',
		'story',
		'tag',
		'tour',
		'travel',
		'user',
	],
	'front_tests' => [
		'advertise',
		'blog',
		'car',
		'category',
		'cinema',
		'food',
		'food-program',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'movie',
		'music',
		'post',
		'product',
		'restaurant',
		'shop',
		'showtime',
		'story',
		'tag',
		'tour',
		'travel',
		'user',
	],
	'api_routes' => [
		'advertise',
		'blog',
		'car',
		'category',
		'cinema',
		'food',
		'food-program',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'like',
		'module',
		'movie',
		'music',
		'page',
		'post',
		'product',
		'restaurant',
		'shop',
		'showtime',
		'story',
		'tag',
		'tour',
		'travel',
		'user',
	],
	'api_tests' => [
		'advertise',
		'blog',
		'car',
		'category',
		'cinema',
		'food',
		'food-program',
		'gym',
		'gym-action',
		'gym-program',
		'home',
		'hotel',
		'movie',
		'music',
		'post',
		'product',
		'restaurant',
		'shop',
		'showtime',
		'story',
		'tag',
		'tour',
		'travel',
		'user',
	],
];

$cms['social_companies'] = ['GOOGLE', 'TWITTER', 'FACEBOOK', 'LINKEDIN', 'GITHUB', 'GITLAB', 'BITBUCKET'];

foreach ($cms['social_companies'] as $social_company) {
	$cms[mb_strtolower($social_company)] = [
		'client_id' => env($social_company . '_CLIENT_ID'),
		'client_secret' => env($social_company . '_CLIENT_SECRET'),
		'redirect' => env($social_company . '_CLIENT_CALLBACK'),
	];
}

return $cms;
