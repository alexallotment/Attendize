<section id='order_form' class="">
    @if($payment_failed)
    <div class="row">
        <div class="col-md-8 alert-danger" style="text-align: left; padding: 10px">
            @lang("Order.payment_failed")
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            @lang("Public_ViewEvent.below_order_details_header")
        </div>

        <div class="col-md-12">
            {{-- <div class="row"> --}}
                
                <div class="event_order_form">
                    @if($order_requires_payment)
                        @include('Public.ViewEvent.Partials.OfflinePayments')
                    @endif

                    @if(View::exists($payment_gateway['checkout_blade_template']))
                        @include($payment_gateway['checkout_blade_template'])
                    @endif
                </div>

            {{-- </div> --}}
        </div>
    </div>
</section>
@if(session()->get('message'))
<script>showMessage('{{session()->get('message')}}');</script>
@endif

