@extends('Public.ViewEvent.Layouts.EventPage')

@section('content')
	<!-- <div class="row">
		<div class="col-lg-4">
		</div>
			<div class="col-lg-8 event_organiser_heading_wrap">
			<div class="event_organiser_heading">
				{{ $event->organiser->name }}
			</div>
		</div>
	</div> -->
	
	<div class="row no-gutters">
		<div class="no-padding col-lg-4">
			<div class="event_block_pad">
				@include('Public.ViewEvent.Partials.EventHeaderSection')
				@include('Public.ViewEvent.Partials.EventShareSection')
			</div>
		</div>
		<div class="no-padding col-lg-8">
			<div class="allotment-event-heading">
					{{$event->organiser->name}}
			</div>
			<div class="event_block_pad">
				@include('Public.ViewEvent.Partials.EventTicketsSection')
			</div>
		</div>
	</div>

    
    {{-- @include('Public.ViewEvent.Partials.EventFooterSection') --}}
   
    {{-- @include('Public.ViewEvent.Partials.EventMapSection') --}}
    {{-- @include('Public.ViewEvent.Partials.EventOrganiserSection') --}}
    
@stop

