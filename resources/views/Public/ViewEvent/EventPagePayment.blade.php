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
				@include('Public.ViewEvent.Partials.EventOrderForm')
			</div>

			<div class="col-lg-8">
				@include('Public.ViewEvent.Partials.EventPaymentSection')
				@include('Public.ViewEvent.Partials.EventFooterSection')
			</div>
		</div>
	</div>
@stop