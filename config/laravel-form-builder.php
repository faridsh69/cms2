<?php

declare(strict_types=1);

return [
	'defaults' => [
		'wrapper_class' => 'form-group m-form__group',
		'wrapper_error_class' => 'has-error',
		'label_class' => '',
		'field_class' => 'form-control m-input m-input--air m-input--pill',
		'field_error_class' => '',
		'help_block_class' => 'm-form__help',
		'error_class' => 'text-danger',
		'required_class' => 'required',
	],
	// Templates
	'form' => 'laravel-form-builder::form',
	'text' => 'laravel-form-builder::text',
	'textarea' => 'laravel-form-builder::textarea',
	'button' => 'laravel-form-builder::button',
	'buttongroup' => 'laravel-form-builder::buttongroup',
	'radio' => 'laravel-form-builder::radio',
	'checkbox' => 'laravel-form-builder::checkbox-m',
	'select' => 'laravel-form-builder::select',
	'choice' => 'laravel-form-builder::choice',
	'repeated' => 'laravel-form-builder::repeated',
	'child_form' => 'laravel-form-builder::child_form',
	'collection' => 'laravel-form-builder::collection',
	'static' => 'laravel-form-builder::static',

	// Remove the laravel-form-builder:: prefix above when using template_prefix
	'template_prefix' => '',

	'default_namespace' => '',

	'custom_fields' => [
		'switch-m' => 'App\Forms\Fields\SwitchM',
		'checkbox-m' => '\App\Forms\Fields\CheckboxM',
		'switch-bootstrap-m' => '\App\Forms\Fields\SwitchBootstrapM',
		'text-m' => '\App\Forms\Fields\TextM',
		'submit-m' => '\App\Forms\Fields\SubmitM',
		'image' => '\App\Forms\Fields\Image',
		'gallery' => '\App\Forms\Fields\Gallery',
		'file' => '\App\Forms\Fields\File',
		'captcha' => '\App\Forms\Fields\Captcha',
	],

	'field_icons' => [
		'url' => 'la-sitemap',
		'app_url' => 'la-sitemap',
		'slug' => 'la-sitemap',
		'name' => 'la-header',
		'app_title' => 'la-header',
		'title' => 'la-header',
		'keywords' => 'la-key',
		'meta_image' => 'la-image',
		'canonical_url' => 'la-globe',
		'meta_description' => 'la-bookmark',
		'email' => 'la-envelope-o',
		'first_name' => 'la-user',
		'last_name' => 'la-users',
		'mobile' => 'la-mobile-phone',
		'phone' => 'la-mobile-phone',
		'birth_date' => 'la-child',
		'website' => 'la-internet-explorer',
		'password' => 'la-lock',
		'password_confirmation' => 'la-unlock',
		'cdn_url' => 'la-cloud',
		'default_meta_title' => 'la-chrome',
		'default_meta_description' => 'la-chrome',
		'pagination_number' => 'la-list',
		'crisp' => 'la-wechat',
		'google_analytics' => 'la-google',
		'google_plus' => 'la-google-plus',
		'twitter' => 'la-twitter',
		'facebook' => 'la-facebook',
		'instagram' => 'la-instagram',
		'skype' => 'la-skype',
		'telegram' => 'la-github',
		'fax' => 'la-fax',
		'throttle' => 'la-battery-2',
		'email_username' => 'la-at',
		'email_password' => 'la-lock',
		'latitude' => 'la-map-marker',
		'longitude' => 'la-map-marker',
		'date' => 'la-calendar-o',
		'price' => 'la-dollar',
		'time' => 'la-clock-o',
		'telephone' => 'la-phone',
		'national_code' => 'la-cc',
		'crisp_id' => 'la-wechat',
		'hotjar_id' => 'la-bar-chart',
		'google_analytics_id' => 'la-google',
		'site_verification_google_code' => 'la-google',
		'google_map_code' => 'la-map-marker',
		'icon' => 'la-image',
		'order' => 'la-angle-double-up',
	],
];
