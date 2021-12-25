<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Bizinabox</title>

    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />

    <meta name="keywords" content="Bizinabox, Preview, Template, Business, Website" />
    <meta name="description" content="Bizinabox, Preview, Template, Business, Website">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {!! $website->css??'' !!}

    @yield('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" >
    <link href="{{asset('assets/css/dev1/preview.css')}}" rel="stylesheet" type="text/css" />

    @if(config('app.env') == 'local')
        <link href="http://bizinabox.localhost/assets/css/section.css?v={{deploy_key()}}" rel="stylesheet" type="text/css" />
    @elseif(config('app.env') == 'development')
        <link href="https://development.bizinabox.com/assets/css/section.css?v={{deploy_key()}}" rel="stylesheet" type="text/css" />
    @elseif(config('app.env') == 'testing')
        <link href="https://testing.bizinabox.com/assets/css/section.css?v={{deploy_key()}}" rel="stylesheet" type="text/css" />
    @else
        <link href="https://bizinabox.com/assets/css/section.css?v={{deploy_key()}}" rel="stylesheet" type="text/css" />
    @endif
</head>
<body>


<div class="out_content overflow-hidden min-vh-100">
    <div id="app" class="bz-page">
        <template-view></template-view>
    </div>
</div>

<a href="#" class="scroll-to-top" style="display: inline;">
    &#8593;
</a>

@routes

@include('components.global.toast')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

<script>
    window.token = '{{ csrf_token() }}';
    window.appURL = '{{env("APP_URL")}}';
    window.imageHost = '{{env("STATIC_IMAGE_HOST")}}';
    window.appDomain = '{{env("APP_DOMAIN")}}';
    window.appEnv = '{{env("APP_ENV")}}';
    window.template = {!! $website !!};
</script>

@if(config('app.env') == 'local')
    <script src="http://bizinabox.localhost/assets/js/section.js?v={{deploy_key()}}" type="text/javascript" ></script>
@elseif(config('app.env') == 'development')
    <script src="https://development.bizinabox.com/assets/js/section.js?v={{deploy_key()}}" type="text/javascript" ></script>
@elseif(config('app.env') == 'testing')
    <script src="https://testing.bizinabox.com/assets/js/section.js?v={{deploy_key()}}" type="text/javascript" ></script>
@else
    <script src="https://bizinabox.com/assets/js/section.js?v={{deploy_key()}}" type="text/javascript" ></script>
@endif

{!! $website->script??'' !!}

</body>

</html>
