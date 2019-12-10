<html>
    <head>

        <title>@yield('title')</title>

        @include('Shared/Layouts/ViewJavascript')

        @include('Shared.Partials.GlobalMeta')

        <!--JS-->
       {!! HTML::script('vendor/jquery/dist/jquery.min.js') !!}
        <!--/JS-->

        <!--Style-->
       {!!HTML::style('assets/stylesheet/application.css')!!}
       {!!HTML::style('assets/stylesheet/terms.css')!!}

        <!--/Style-->

        @yield('head')

        <style>

                        body {
                /*background: url({{asset('assets/images/background.png')}}) repeat;*/
                background-color: #e16c26;
            }

            h2 {
                text-align: center;
                margin-bottom: 31px;
                text-transform: uppercase;
                letter-spacing: 4px;
                font-size: 23px;
            }
            .panel {
                background-color: #ffffff;
                background-color: rgba(255,255,255,.95);
                padding: 15px 30px ;
                border: none;
                color: #333;
                box-shadow: 0 0 5px 0 rgba(0,0,0,.2);
                margin-top: 40px;
            }

            .panel a {
                color: #333;
                font-weight: 600;
            }

            .logo {
                text-align: center;
                margin-bottom: 20px;
            }

            .logo img {
                width: 200px;
            }

            .signup {
                margin-top: 10px;
            }

            .forgotPassword {
                font-size: 12px;
                color: #ccc;
            }

            .footer-links{
                padding: 0;
                margin:0;
                text-align: center;
            }

            .footer-links a{
                display: inline-block;
                color: #fff;
                padding: 10px;
                opacity: 0.75;
            }

            .footer-links a:hover{
                opacity: 1;
            }



            /* COMING SOON NOT PUBLISHED EVENT PAGE */

            .allotment-event-heading{
                background: #373737;
                padding: 20px 10px;
                color: #fff;
                font-size: 30px;
                text-transform: uppercase;
                margin:0;
                text-align: center;
            }

            .event-pre-launch .panel{
                margin-top:0px;
            }

            .countdown-timer-heading{
                text-transform: uppercase;
                font-size: 14px;
                padding: 0;
                margin:0;
                margin-top: 30px;
                padding-bottom: 5px;
            }

            #countdown-timer{

            width:100%;
            max-width: 280px;
            margin:auto;
            display: block;
            position: relative;
            border-left: 1px solid #ddd;
            border-top: 1px solid #ddd;
            }

            #countdown-timer .dt-block{
                width: 25%;
                display: inline-block;
                font-size: 0px;
                text-align:center;
                border-bottom: 1px solid #ddd;
                border-right: 1px solid #ddd;

            }

            #countdown-timer .dt-block .dt-value{
                font-size:30px;
                display: block;
                line-height: 1;
                padding-top: 5px;
                padding-bottom: 5px;
            }

            #countdown-timer .dt-block .dt-label{
                font-size:10px;
                text-transform: uppercase;
                display: block;
                line-height: 1;
                padding-bottom: 5px;
                color: #e16c26;
            }
        </style>
    </head>
    <body>
        <section id="main" role="main">
            <section class="container">
                @yield('content')
            </section>

        </section>
        <div style="text-align: center; color: white" >
        </div>

        @include("Shared.Partials.LangScript")
        {!!HTML::script('assets/javascript/backend.js')!!}

        {!!HTML::script(config('attendize.cdn_url_static_assets').'/assets/javascript/frontend.js')!!}
    </body>
    @include('Shared.Partials.GlobalFooterJS')
</html>
