@if(!$event->is_live)
<section id="goLiveBar">
        @if(!$event->is_live)

        {{ @trans("ManageEvent.event_not_live") }}
        <a href="{{ route('MakeEventLive' , ['event_id' => $event->id]) }}"
           style="background-color: green; border-color: green;"
        class="btn btn-success btn-xs">{{ @trans("ManageEvent.publish_it") }}</a>
        @endif
</section>
@endif

<section id="overview">
    <div class="allotment-left-organiser-name mobile">
        {{$event->organiser->name}}
        <span class="event-line-up">{{$event->line_up}}</span>
    </div>

    @if (($event->bg_type == 'image' || $event->bg_type == 'custom_image' || Input::get('bg_img_preview')) && !Input::get('bg_color_preview'))
    <img 
    src="{{(Input::get('bg_img_preview') ? URL::to(Input::get('bg_img_preview')) :  asset(config('attendize.cdn_url_static_assets').'/'.$event->bg_image_path))}}"
    style="width:100%; height: auto;"
    />

    @endif

    <h1 property="name" style="display:none;">{{$event->title}}</h1>

    <div class="allotment-left-organiser-name desktop">
        {{$event->organiser->name}}
        <span class="event-line-up">{{$event->line_up}}</span>
    </div>

    <div class="event_date">
        <p property="startDate" content="{{ $event->start_date->toIso8601String() }}">
            <strong>{{ $event->start_date->format('D F j Y') }}</strong>
        </p>
        
        <p property="endDate" content="{{ $event->end_date->toIso8601String() }}">
             @if($event->start_date->diffInDays($event->end_date) == 0)
                {{ $event->start_date->format('H:i a') }}
                - 
                {{ $event->end_date->format('H:i a') }}
             @else
                {{ $event->end_date->format('D jS F Y') }}
             @endif
        </p>
    </div>
    <div class="event_venue">
        <span property="location" typeof="Place">
            <b property="name">{{$event->venue_name}}</b><br/>
            
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
                    <span>{{ $event->location_address }}</span>
                 @endif
                
            <meta property="address" content="{{ urldecode($event->venue_name) }}">
        </span>
    </div>


    @if($event->age_restriction != '')
        <div class="event_age_price">
            <p>
                Ages: {{$event->age_restriction}}
                @if($event->age_restriction != '')
                    <br>
                    <small>{{$event->age_restriction_disclaimer}}</small>
                @endif
            </p>
        </div>
    @endif

    <!-- <div class="content event_details" property="description">
        {!! Markdown::parse($event->description) !!}
    </div> -->

    {{-- <div class="event_buttons">
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <a class="btn btn-event-link btn-lg" href="{{{$event->event_url}}}#tickets">@lang("Public_ViewEvent.TICKETS")</a>
            </div>
            <div class="col-md-4 col-sm-4">
                <a class="btn btn-event-link btn-lg" href="{{{$event->event_url}}}#details">@lang("Public_ViewEvent.DETAILS")</a>
            </div>
            <div class="col-md-4 col-sm-4">
                <a class="btn btn-event-link btn-lg" href="{{{$event->event_url}}}#location">@lang("Public_ViewEvent.LOCATION")</a>
            </div>
        </div>
    </div> --}}
</section>
