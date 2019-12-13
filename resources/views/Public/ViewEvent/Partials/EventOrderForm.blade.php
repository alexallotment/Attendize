<div class="event-section-order-form">
    <table class="table mb0 table-condensed">

        @php 
        $total_fees = 0;
        $simple_total = 0;
        $variable_total = 0;
        $full_products_total = 0;
        @endphp
        @foreach($tickets as $ticket)

        @php
        $total_fees = $total_fees + $ticket['booking_fee'];
        $total_fees = $total_fees + $ticket['organiser_booking_fee'];
        
        @endphp

        @foreach ($products[0] as $simple)
            @php
            $line_total = $simple['price'] * $simple['value'];
            $simple_total = $simple_total + $line_total;
            @endphp

            <tr>
            <td><strong>{{$simple['name']}}</strong></td>
            <td><strong>{{$simple['value']}}</strong></td>
            <td><strong>£{{$simple['price']}}</strong></td>
            </tr>
        @endforeach

        @foreach ($products[1] as $variable)
            @php
            $line_total = $variable['product_price'] * $variable['value'];
            $variable_total = $variable_total + $line_total;
            @endphp

            <tr>
            <td><strong>{{$variable['product_name']}}</strong></td>
            <td><strong>{{$variable['value']}}</strong></td>
            <td><strong>£{{$variable['product_price']}}</strong></td>
            </tr>
        @endforeach

        <tr>
            <td class="pl0"><strong>{{{$ticket['ticket']['title']}}}</strong></td>
            <td><strong>{{$ticket['qty']}}</strong></td>
            <td style="text-align: right;">
                @if((int)ceil($ticket['full_price']) === 0)
                    <strong>
                        @lang("Public_ViewEvent.free")
                    </strong>
                @else
                    <strong>
                        {{ money($ticket['full_price'], $event->currency) }}
                       
                    </strong>
                @endif
            </td>
        </tr>
        @endforeach
    </table>

</div>
@if($order_total > 0)
<div class="order-form-footer">
    @php
    $full_products_total = $simple_total + $variable_total;
    $tickets_total = $orderService->getOrderTotalWithBookingFee(true);
    $tickets_total = ltrim($tickets_total, '£');
    $final_total = $full_products_total + $tickets_total;

    @endphp
    <h5>
        @lang("Public_ViewEvent.total"): <span style="float: right;"><b>£{{ $final_total }}</b></span>
    </h5>
    @if($event->organiser->charge_tax)
    <h5>
        {{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%):
        <span style="float: right;"><b>{{ $orderService->getTaxAmount(true) }}</b></span>
    </h5>
    <h5 class="grand-total">
        <strong>@lang("Public_ViewEvent.grand_total")</strong>
        <span style="float: right;"><b>{{  $orderService->getGrandTotal(true) }}</b></span>
    </h5>
    @endif

    <small>Includes {{money($total_fees, $event->currency)}} Booking Fee.</small>
</div>
@endif

<div class="help-block">
<div class="alert alert-default" style="margin-top:30px;">
    {!! @trans("Public_ViewEvent.time", ["time"=>"<span id='countdown'></span>"]) !!}
    </div>
</div>