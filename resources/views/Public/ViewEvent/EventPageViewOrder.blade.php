@extends('Public.ViewEvent.Layouts.EventPage')

@section('content')
    <div class="allotment-event-heading">
		Order Confirmation
    </div>
    <div class="event-view-order-section">
        <div class="row">
		    <div class="col-lg-4">
			    <div class="event_block_pad">
                    @include('Public.ViewEvent.Partials.EventHeaderSection')
                    @include('Public.ViewEvent.Partials.EventShareSection')
                </div>
            </div>
            <div class="col-lg-8">
                <div class="event_block_pad">
                    @include('Public.ViewEvent.Partials.EventViewOrderSection')
                    <!-- @include('Public.ViewEvent.Partials.EventFooterSection') -->
                </div>
            </div>
        </div>
    </div>
    
@stop
