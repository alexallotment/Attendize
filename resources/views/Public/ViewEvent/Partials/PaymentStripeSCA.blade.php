<form class="online_payment" action="<?php echo route('postCreateOrder', ['event_id' => $event->id]); ?>" method="post" id="stripe-sca-payment-form">
    <div class="form-row">
        <label for="card-element">
            Credit or debit card
        </label>
        <div id="card-element">

        </div>

        <div id="card-errors" role="alert"></div>
    </div>
    {!! Form::token() !!}

    <input class="btn btn-lg btn-success card-submit" style="width:100%;" type="submit" value="Complete Payment">

</form>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">

    var stripe = Stripe('<?php echo $account_payment_gateway->config['publishableKey']; ?>');
    var elements = stripe.elements();

    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var cardElement = elements.create('card', {hidePostalCode: true, style: style});
    cardElement.mount('#card-element');

    cardElement.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });


</script>
<style type="text/css">

    .StripeElement {
        box-sizing: border-box;

        height: 40px;

        padding: 10px 12px;

        border: 1px solid #e0e0e0 !important;
        border-radius: 4px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
        margin-bottom: 20px;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }





    .StripeElement {
  background-color: #fff;
}

.StripeElement * {
  font-family: Source Code Pro, Consolas, Menlo, monospace;
  font-size: 16px;
  font-weight: 500;
}

.StripeElement .row {
  display: -ms-flexbox;
  display: flex;
  margin: 0 5px 10px;
}

.StripeElement .field {
  position: relative;
  width: 100%;
  height: 50px;
  margin: 0 10px;
}

.StripeElement .field.half-width {
  width: 50%;
}

.StripeElement .field.quarter-width {
  width: calc(25% - 10px);
}

.StripeElement .baseline {
  position: absolute;
  width: 100%;
  height: 1px;
  left: 0;
  bottom: 0;
  background-color: #cfd7df;
  transition: background-color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.StripeElement label {
  position: absolute;
  width: 100%;
  left: 0;
  bottom: 8px;
  color: #cfd7df;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  transform-origin: 0 50%;
  cursor: text;
  pointer-events: none;
  transition-property: color, transform;
  transition-duration: 0.3s;
  transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1);
}

.StripeElement .input {
  position: absolute;
  width: 100%;
  left: 0;
  bottom: 0;
  padding-bottom: 7px;
  color: #32325d;
  background-color: transparent;
}

.StripeElement .input::-webkit-input-placeholder {
  color: transparent;
  transition: color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.StripeElement .input::-moz-placeholder {
  color: transparent;
  transition: color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.StripeElement .input:-ms-input-placeholder {
  color: transparent;
  transition: color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.StripeElement .input.StripeElement {
  opacity: 0;
  transition: opacity 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  will-change: opacity;
}

.StripeElement .input.focused,
.StripeElement .input:not(.empty) {
  opacity: 1;
}

.StripeElement .input.focused::-webkit-input-placeholder,
.StripeElement .input:not(.empty)::-webkit-input-placeholder {
  color: #cfd7df;
}

.StripeElement .input.focused::-moz-placeholder,
.StripeElement .input:not(.empty)::-moz-placeholder {
  color: #cfd7df;
}

.StripeElement .input.focused:-ms-input-placeholder,
.StripeElement .input:not(.empty):-ms-input-placeholder {
  color: #cfd7df;
}

.StripeElement .input.focused + label,
.StripeElement .input:not(.empty) + label {
  color: #aab7c4;
  transform: scale(0.85) translateY(-25px);
  cursor: default;
}

.StripeElement .input.focused + label {
  color: #24b47e;
}

.StripeElement .input.invalid + label {
  color: #ffa27b;
}

.StripeElement .input.focused + label + .baseline {
  background-color: #24b47e;
}

.StripeElement .input.focused.invalid + label + .baseline {
  background-color: #e25950;
}

.StripeElement input, .StripeElement button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  outline: none;
  border-style: none;
}

.StripeElement input:-webkit-autofill {
  -webkit-text-fill-color: #e39f48;
  transition: background-color 100000000s;
  -webkit-animation: 1ms void-animation-out;
}

.StripeElement .StripeElement--webkit-autofill {
  background: transparent !important;
}

.StripeElement input, .StripeElement button {
  -webkit-animation: 1ms void-animation-out;
}

.StripeElement button {
  display: block;
  width: calc(100% - 30px);
  height: 40px;
  margin: 40px 15px 0;
  background-color: #24b47e;
  border-radius: 4px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  cursor: pointer;
}

.StripeElement .error svg {
  margin-top: 0 !important;
}

.StripeElement .error svg .base {
  fill: #e25950;
}

.StripeElement .error svg .glyph {
  fill: #fff;
}

.StripeElement .error .message {
  color: #e25950;
}

.StripeElement .success .icon .border {
  stroke: #abe9d2;
}

.StripeElement .success .icon .checkmark {
  stroke: #24b47e;
}

.StripeElement .success .title {
  color: #32325d;
  font-size: 16px !important;
}

.StripeElement .success .message {
  color: #8898aa;
  font-size: 13px !important;
}

.StripeElement .success .reset path {
  fill: #24b47e;
}





</style>
