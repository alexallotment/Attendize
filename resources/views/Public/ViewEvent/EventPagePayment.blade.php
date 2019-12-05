@extends('Public.ViewEvent.Layouts.EventPage')

@section('head')

@stop

@section('content')
	<div class="allotment-event-heading event-heading-order-form">
		PAYMENT DETAILS
		{{-- @lang("Public_ViewEvent.payment_information") --}}
	</div>
	<div class="event-checkout-section">
		<div class="row">
			<div class="col-lg-4">
				@include('Public.ViewEvent.Partials.EventHeaderSection')
				
			</div>

			<div class="col-lg-8">
				<h2>Tickets</h2>
				@include('Public.ViewEvent.Partials.EventOrderForm')
				@include('Public.ViewEvent.Partials.EventPaymentSection')
				@include('Public.ViewEvent.Partials.EventFooterSection')
			</div>
		</div>
	</div>
@stop