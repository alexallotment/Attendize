<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{{$event->title}}} - Allotment Productions</title>


        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" />
        <link rel="canonical" href="{{$event->event_url}}" />


        <!-- Open Graph data -->
        <meta property="og:title" content="{{{$event->title}}}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{$event->event_url}}?utm_source=fb" />
        @if($event->images->count())
        <meta property="og:image" content="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" />
        @endif
        <meta property="og:description" content="{{Str::words(strip_tags(Markdown::parse($event->description))), 20}}" />
        <meta property="og:site_name" content="Allotment Tickets" />

        <link rel="stylesheet" href="https://use.typekit.net/xzt0goi.css">
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('head')

       {!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}

        @if ($event->bg_type == 'color' || Input::get('bg_color_preview'))
            <style>.background-container {background-color: {{(Input::get('bg_color_preview') ? '#'.Input::get('bg_color_preview') : $event->bg_color)}} !important; }</style>
        @endif

        @if (($event->bg_type == 'image' || $event->bg_type == 'custom_image' || Input::get('bg_img_preview')) && !Input::get('bg_color_preview'))
            <style>
                .background-container {
                    background: url({{(Input::get('bg_img_preview') ? URL::to(Input::get('bg_img_preview')) :  asset(config('attendize.cdn_url_static_assets').'/'.$event->bg_image_path))}}) no-repeat center center fixed;
                    background-size: cover;
                }
            </style>
        @endif

    </head>
    <body class="attendize">
        <div class="background-container">
            <div class="background-container-overlay"></div>
        </div>
        <div id="event_page_wrap" vocab="http://schema.org/" typeof="Event">
            <div class="container">
                <div class="allotment-event-page-top"><span>Powered By</span> <span>Allotment</span> <span>Productions</span></div>
                <div class="event_page_content">
                    @yield('content')
                    <div class="event-footer-section">
                        <a class="adminLink "
                        href="/terms-conditions">Terms & Conditions</a>
                        <a class="adminLink "
                        href="/privacy-policy">Privacy Policy</a>
                        <a class="adminLink "
                        href="/contact">Contact Us</a>
                    </div>
                </div>
            </div>
            

            {{-- Push for sticky footer--}}
            @stack('footer')
        </div>

        {{-- Sticky Footer--}}
        @yield('footer')

        @include("Shared.Partials.LangScript")
        {!!HTML::script(config('attendize.cdn_url_static_assets').'/assets/javascript/frontend.js')!!}


        @if(isset($secondsToExpire))
        <script>if($('#countdown')) {setCountdown($('#countdown'), {{$secondsToExpire}});}</script>
        @endif

        @include('Shared.Partials.GlobalFooterJS')
    </body>
</html>
