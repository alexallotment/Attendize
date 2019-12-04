@extends('Public.ViewEvent.Layouts.EventPage')

@section('head')

@stop

@section('content')
    <div class="allotment-event-heading event-heading-order-form">
		ORDER DETAILS
	</div>
    <div class="event-checkout-section">
        <div class="col-lg-4">
            @include('Public.ViewEvent.Partials.EventHeaderSection')
            @include('Public.ViewEvent.Partials.EventOrderForm')
        </div>
        <div class="col-lg-8">
            @include('Public.ViewEvent.Partials.EventCreateOrderSection')
        </div>
        <script>var OrderExpires = {{strtotime($expires)}};</script>
        <!-- @include('Public.ViewEvent.Partials.EventFooterSection') -->
    </div>
@stop

