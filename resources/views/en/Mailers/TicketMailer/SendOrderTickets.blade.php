@extends('en.Emails.Layouts.Master')

@section('message_content')
Hello,<br><br>

Your order for the event <strong>{{$order->event->title}}</strong> was successful.<br><br>

Your tickets are attached to this email. You can also view you order details and download your tickets
at: {{route('showOrderDetails', ['order_reference' => $order->order_reference])}}

@if(!$order->is_payment_received)
<br><br>
<strong>Please note: This order still requires payment. Instructions on how to make payment can be found on your
    order page: {{route('showOrderDetails', ['order_reference' => $order->order_reference])}}</strong>
<br><br>
@endif

<!-- NEW EMAIL STUFF -->
<h3>Event Details</h3>
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
                <span>{{ $event->$event->location_address }}</span>
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
<!-- NEW EMAIL STUFF -->


<h3>Order Details</h3>
Order Reference: <strong style="text-transform:uppercase;">{{$order->order_reference}}</strong><br>
Order Name: <strong>{{$order->full_name}}</strong><br>
Order Date: <strong>{{$order->created_at->format(config('attendize.default_datetime_format'))}}</strong><br>
Order Email: <strong>{{$order->email}}</strong><br>
<a href="{!! route('downloadCalendarIcs', ['event_id' => $order->event->id]) !!}">Add To Calendar</a>

@if ($order->is_business)
<h3>Business Details</h3>
@if ($order->business_name) @lang("Public_ViewEvent.business_name"): <strong>{{$order->business_name}}</strong><br>@endif
@if ($order->business_tax_number) @lang("Public_ViewEvent.business_tax_number"): <strong>{{$order->business_tax_number}}</strong><br>@endif
@if ($order->business_address_line_one) @lang("Public_ViewEvent.business_address_line1"): <strong>{{$order->business_address_line_one}}</strong><br>@endif
@if ($order->business_address_line_two) @lang("Public_ViewEvent.business_address_line2"): <strong>{{$order->business_address_line_two}}</strong><br>@endif
@if ($order->business_address_state_province) @lang("Public_ViewEvent.business_address_state_province"): <strong>{{$order->business_address_state_province}}</strong><br>@endif
@if ($order->business_address_city) @lang("Public_ViewEvent.business_address_city"): <strong>{{$order->business_address_city}}</strong><br>@endif
@if ($order->business_address_code) @lang("Public_ViewEvent.business_address_code"): <strong>{{$order->business_address_code}}</strong><br>@endif
@endif

<h3>Order Items</h3>
<div style="padding:10px; background: #F9F9F9; border: 1px solid #f1f1f1;">
    <table style="width:100%; margin:10px;">
        <tr>
            <td>
                <strong>Ticket</strong>
            </td>
            <td>
                <strong>Qty.</strong>
            </td>
            <td>
                <strong>Price</strong>
            </td>
            <td>
                <strong>Fee</strong>
            </td>
            <td>
                <strong>Total</strong>
            </td>
        </tr>
        @foreach($order->orderItems as $order_item)
        <tr>
            <td>{{$order_item->title}}</td>
            <td>{{$order_item->quantity}}</td>
            <td>
                @if((int)ceil($order_item->unit_price) == 0)
                FREE
                @else
                {{money($order_item->unit_price, $order->event->currency)}}
                @endif
            </td>
            <td>
                @if((int)ceil($order_item->unit_price) == 0)
                -
                @else
                {{money($order_item->unit_booking_fee, $order->event->currency)}}
                @endif
            </td>
            <td>
                @if((int)ceil($order_item->unit_price) == 0)
                FREE
                @else
                {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity),
                $order->event->currency)}}
                @endif
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td><strong>Sub Total</strong></td>
            <td colspan="2">
                {{$orderService->getOrderTotalWithBookingFee(true)}}
            </td>
        </tr>
        @if($order->event->organiser->charge_tax == 1)
        <tr>
            <td colspan="3"></td>
            <td>
                <strong>{{$order->event->organiser->tax_name}}</strong><em>({{$order->event->organiser->tax_value}}%)</em>
            </td>
            <td colspan="2">
                {{$orderService->getTaxAmount(true)}}
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3"></td>
            <td><strong>Total</strong></td>
            <td colspan="2">
                {{$orderService->getGrandTotal(true)}}
            </td>
        </tr>
    </table>
    <br><br>
</div>
<br><br>
Thank you
@stop
