@extends('front.page')
@section('content_block')
<div class="row">
	<div class="col-12">
        <h1>{{  $category->title }}</h1>
        <h4>{{  $category->description }}</h4>
        <br>
        {!! $category->content !!}
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-12">
                <hr>
                <div class="rtl-text-right mr-3">
                    <small><b>{{ __('date') }}:</b> {{ $category->created_at }}</small>
                    <br>
                    <a href="whatsapp://send?text={{ route('front.blog.category', $category->id) }}" data-action="share/whatsapp/share">
                        <i class="fa fa-share-alt"></i>
                        <small>Whatsapp</small>
                    </a>
                    <br>
                </div>
                <hr>
            </div>
        </div>
        @if( count($blogs) > 0 )
        <div class="row">
            <div class="col-12 text-center">
                <div class="section-heading">
                    <h2>{{ __('blogs') }}</h2>
                    <div class="line-shape"></div>
                </div>
            </div>
            @each('front.themes.' . config('setting-developer.theme') . '.blog-card', $blogs, 'blog')
        </div>
        @endif
    </div>
    <br>
</div>
@endsection
