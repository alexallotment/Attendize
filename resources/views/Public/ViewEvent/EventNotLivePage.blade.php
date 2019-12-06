@extends('Shared.Layouts.MasterWithoutMenus')

@section('title')
    Event Not Live
@stop

@section('content')
    <div class="row event-pre-launch">
        <div class="col-md-6 col-md-offset-3">
            @if (($event->bg_type == 'image' || $event->bg_type == 'custom_image' || Input::get('bg_img_preview')) && !Input::get('bg_color_preview'))
            <img 
            src="{{(Input::get('bg_img_preview') ? URL::to(Input::get('bg_img_preview')) :  asset(config('attendize.cdn_url_static_assets').'/'.$event->bg_image_path))}}"
            style="width:100%; height: auto;"
            />

            @endif

            <h1 class="allotment-event-heading">{{$event->organiser->name}}</h1>

            <div class="panel">

                <div class="panel-body text-center">

                
                    <h1 property="name" style="display:none;">{{$event->title}}</h1>


                    <p property="startDate" content="{{ $event->start_date->toIso8601String() }}">
                        <strong class="text-uppercase">{{ $event->start_date->format('D F j Y') }}</strong><br/>
                        <span class="event-line-up">{{$event->line_up}}</span>
                    </p>



                    <div class="event_venue">
                        <span property="location" typeof="Place">
                            <b property="name" class="text-uppercase">{{$event->venue_name}}</b><br/>
                            
                                @if($event->location_is_manual)
                                    @if($event->location_street_number != '')
                                        <span>{{$event->location_street_number}}</span><br/>
                                    @endif

                                    @if($event->location_address_line_1 != '')
                                        <span>{{$event->location_address_line_1}}</span><br/>
                                    @endif

                                    @if($event->location_address_line_2 != '')
                                        <span>{{$event->location_address_line_2}}</span><br/>
                                    @endif

                                    @if($event->location_country != '')
                                        <span>{{$event->location_country}}</span><br/>
                                    @endif

                                    @if($event->location_country_code != '')
                                        <span>{{$event->location_country_code}}</span><br/>
                                    @endif

                                    @if($event->location_state != '')
                                        <span>{{$event->location_state}}</span><br/>
                                    @endif

                                    @if($event->location_post_code != '')
                                        <span>{{$event->location_post_code}}</span><br/>
                                    @endif
                                 @else
                                    <span>{{ $event->$event->location_address }}</span>
                                 @endif
                                
                            <meta property="address" content="{{ urldecode($event->venue_name) }}">
                        </span>
                    </div>

        <script>
            var upgradeTime = {{$countdown}};
            var seconds = upgradeTime;
            function timer() {
              var days        = Math.floor(seconds/24/60/60);
              var hoursLeft   = Math.floor((seconds) - (days*86400));
              var hours       = Math.floor(hoursLeft/3600);
              var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
              var minutes     = Math.floor(minutesLeft/60);
              var remainingSeconds = seconds % 60;
              function pad(n) {
                return (n < 10 ? "0" + n : n);
              }
              document.getElementById('countdown-timer').innerHTML = 

              '<div class="dt-block">' +
              '<span class="dt-value">' + pad(days) + '</span><span class="dt-label">DAYS</span>'+
              '</div><div class="dt-block">' +
              '<span class="dt-value">' + pad(hours) + '</span><span class="dt-label">HOURS</span>'+ 
              '</div><div class="dt-block">' +
              '<span class="dt-value">' + pad(minutes) + '</span><span class="dt-label">MINS</span>'+ 
              '</div><div class="dt-block">' +
              '<span class="dt-value">' + pad(remainingSeconds) + '</span><span class="dt-label">SECS</span>'
              '</div>';

              // seconds--;
              if (seconds == 0) {
                clearInterval(countdownTimer);
                document.getElementById('refresh-link').innerHTML = '<a class="btn btn-block btn-success" href="javascript:window.location.reload(true)">Reload Page</a>';
              } else {
                seconds--;
              }
            }
            var countdownTimer = setInterval('timer()', 1000);
        </script>

                    <p class="countdown-timer-heading">Tickets go on sale in</p>
                    <div id="countdown-timer"><div class="dt-block"><span class="dt-value">00</span><span class="dt-label">DAYS</span></div><div class="dt-block"><span class="dt-value">00</span><span class="dt-label">HOURS</span></div><div class="dt-block"><span class="dt-value">00</span><span class="dt-label">MINS</span></div><div class="dt-block"><span class="dt-value">00</span><span class="dt-label">SECS</span></div></div>
                    <div id="refresh-link"></div>

                    <?php //var_dump($countdown);?>


                    @if($event->age_restriction != '')
                        <div class="event_age_price">
                            <p>
                                <br/>Ages {{$event->age_restriction}} {{$event->age_restriction_disclaimer}}
                            </p>
                        </div>
                    @endif

                                        
                    
                    <?php //var_dump($event); ?>
                </div>
            </div>

            <div class="footer-links">
                        
                        <a class="adminLink "
                        href="/privacy-policy">Privacy Policy</a>
                        <a class="adminLink "
                        href="/contact">Contact Us</a>
                    </div>
        </div>
    </div>
@stop

