@php
    $form = FormBuilder::create(\App\Forms\CustomeForm::class, [
    'method' => 'POST',
        'url' => 'subscribe',
        'class' => 'm-form m-form--state pt-5',
        'id' =>  'custome-form',
        'enctype' => 'multipart/form-data',
    ]);
@endphp
<div class="container mt-5">
	<br>
	<br>
	<div class="row">
		<div class="col-md-8">
			@include('front.components.alert')
			{!! form($form) !!}
		</div>
	</div>
</div>