<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@if(Request::is('/')) {{$basic['seo_title']}} @else @yield('title') @endif {{$basic['sign']?? '|'}} {{$basic['name']?? 'Website'}}</title>

    @if(Request::is('/'))
        @include("components.front.seo", $seo)
    @else
        @yield('meta')
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="icon" href="{{$basic['favicon']}}" />

    <!-- Google Front -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <link rel="stylesheet" href="{{mix('assets/css/style.css')}}" />


    <link href="{{asset('assets/vendors/contentbuilder/box/box.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendors/contentbuilder/assets/minimalist-blocks/content.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendors/contentbuilder/assets/scripts/simplelightbox/simplelightbox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendors/contentbuilder/contentbuilder/contentbuilder.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendors/contentbuilder/contentbox/contentbox.css')}}" rel="stylesheet" type="text/css" />

    {!! app('tenant')->css !!}

    @if(app('tenant')->header!==null)
        {!! app('tenant')->header->css !!}
    @endif
    @if(app('tenant')->footer!==null)
        {!! tenant()->footer->mainCss !!}
        {!! tenant()->footer->sectionCss !!}
        {!! tenant()->footer->css !!}
    @endif

    {!! $basic['front_head'] !!}

    @yield('style')

    <x-front.theme-color></x-front.theme-color>
    <x-front.menu-color></x-front.menu-color>

    @if(config('app.env') == 'local')
        <link href="http://bizinabox.localhost/assets/css/section.css?v={{deploy_key()}}" rel="stylesheet" type="text/css" />
    @else
        <link href="https://development.bizinabox.com/assets/css/section.css?v={{deploy_key()}}" rel="stylesheet" type="text/css" />
    @endif
</head>
<body>
    @if(tenant()->version === 1)
        <div id="header_area">
            @if(tenant()->header!==null)
                {!! tenant()->getHeader(0) !!}
            @endif
        </div>
    @else
        <div id="header" class="bz-page">
            <header-view></header-view>
        </div>
    @endif

    @yield('content')

    @if(tenant()->version === 1)
        <div class="is-wrapper h-auto">
            @if(tenant()->footer!==null)
                {!! tenant()->footer->content !!}
            @endif
        </div>
    @else
        <div id="footer" class="bz-page">
            <footer-view></footer-view>
        </div>
    @endif

    <a href="#" class="scroll-to-top" style="display: inline;">
        &#8593;
    </a>

    <div class="newsletter_area"></div>

    <script src="{{mix('assets/js/script.js')}}"></script>

    <script>
        var token = $('meta[name="csrf-token"]').attr('content');
    </script>

    {{--Loader Include--}}
    @if(Request::is('/') && $basic['loading']!=null && $basic['loading']!=0)
        @include('components.global.loader', ['loading'=>$basic['loading'], 'color'=>$basic['loading_color']?? '#333', 'time'=>$basic['loading_time']?? '1000'])
    @endif
    {{--Loader Include End--}}

    @include('components.global.toast')

    {!! tenant()->script !!}

    <script src="{{asset('assets/vendors/contentbuilder/assets/scripts/simplelightbox/simple-lightbox.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/contentbuilder/contentbuilder/contentbuilder.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/contentbuilder/contentbox/contentbox.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/contentbuilder/assets/minimalist-blocks/content.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/contentbuilder/box/box.js')}}" type="text/javascript"></script>

    @if(tenant()->header!==null)
        {!! tenant()->header->script !!}
    @endif
    @if(tenant()->footer!==null)
        {!! tenant()->footer->script !!}
    @endif

    @publishedModule("email")
        @if(!session("closeNewsletter"))
            <script src="{{asset('assets/js/front/subscribe.js')}}"></script>
        @endif
    @endpublishedModule

    @yield('script')

    {!! $basic['front_bottom'] !!}

</body>

<script>
    window.token = '{{ csrf_token() }}';
    window.appURL = '{{env("APP_URL")}}';
    window.imageHost = '{{env("STATIC_IMAGE_HOST")}}';
    window.appDomain = '{{env("APP_DOMAIN")}}';
    window.appEnv = '{{env("APP_ENV")}}';
    window.template = {!! tenant() !!};
</script>

@if(config('app.env') == 'local')
    <script src="http://bizinabox.localhost/assets/js/section.js?v={{deploy_key()}}" type="text/javascript" ></script>
@else
    <script src="https://development.bizinabox.com/assets/js/section.js?v={{deploy_key()}}" type="text/javascript" ></script>
@endif
</html>
