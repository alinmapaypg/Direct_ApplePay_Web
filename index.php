<?php
require_once 'apple_pay_conf.php';
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Apple Pay Test</title>

<style>
#applePay {
    width: 150px;
    height: 50px;
    display: none;
    border-radius: 6px;
    margin: 20px auto;
    background-image: -webkit-named-image(apple-pay-logo-white);
    background-color: black;
    background-size: 60%;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
</head>

<body>

<button id="applePay"></button>

<p id="got_notactive" style="display:none">
    Apple Pay is available, but no active card found.
</p>

<p id="notgot" style="display:none">
    Apple Pay is not supported on this browser.
</p>

<p id="success" style="display:none">
    ✅ Test transaction completed.
    <a href="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>">Reset</a>
</p>

<script>
const DEBUG = <?= DEBUG ? 'true' : 'false' ?>;
const merchantIdentifier = '<?= PRODUCTION_MERCHANTIDENTIFIER ?>';

function logit(data) {
    if (DEBUG) console.log(data);
}

/* ------------------------------------------------
   APPLE PAY AVAILABILITY CHECK
------------------------------------------------ */
if (window.ApplePaySession) {
    ApplePaySession
        .canMakePaymentsWithActiveCard(merchantIdentifier)
        .then(function (canPay) {
            if (canPay) {
                document.getElementById('applePay').style.display = 'block';
            } else {
                document.getElementById('got_notactive').style.display = 'block';
            }
        });
} else {
    document.getElementById('notgot').style.display = 'block';
}

/* ------------------------------------------------
   CLICK APPLE PAY BUTTON
------------------------------------------------ */
document.getElementById('applePay').onclick = function () {

    const amount = 2.00;

    const paymentRequest = {
        countryCode: '<?= PRODUCTION_COUNTRYCODE ?>',
        currencyCode: '<?= PRODUCTION_CURRENCYCODE ?>',
        supportedNetworks: ['visa', 'masterCard', 'amex', 'mada'],
        merchantCapabilities: [
            'supports3DS'
        ],
        lineItems: [
            { label: 'Test Payment', amount: amount.toFixed(2) }
        ],
        total: {
            label: '<?= PRODUCTION_DISPLAYNAME ?>',
            amount: amount.toFixed(2)
        }
    };

    const session = new ApplePaySession(3, paymentRequest);

    /* --------------------------------------------
       MERCHANT VALIDATION
    -------------------------------------------- */
    session.onvalidatemerchant = function (event) {
        fetch(
            'apple_pay_comm.php?u=' +
            encodeURIComponent(event.validationURL)
        )
        .then(res => res.json())
        .then(data => session.completeMerchantValidation(data))
        .catch(() => session.abort());
    };

    /* --------------------------------------------
       PAYMENT AUTHORIZED
    -------------------------------------------- */
    session.onpaymentauthorized = function (event) {

        // ✅ ALWAYS stringify the LIVE token here
        const paymentTokenString = JSON.stringify(event.payment.token);

        logit(event.payment.token);
        logit('Transaction ID: ' + event.payment.token.paymentData.header.transactionId);

        sendPaymentToken(paymentTokenString)
            .then(function (success) {

                if (success) {
                    document.getElementById('applePay').style.display = 'none';
                    document.getElementById('success').style.display = 'block';
                    session.completePayment(ApplePaySession.STATUS_SUCCESS);
                } else {
                    session.completePayment(ApplePaySession.STATUS_FAILURE);
                }
            })
            .catch(() => {
                session.completePayment(ApplePaySession.STATUS_FAILURE);
            });
    };

    session.oncancel = function () {
        logit('Apple Pay cancelled');
    };

    session.begin();
};

/* ------------------------------------------------
   SEND TOKEN TO SERVER
------------------------------------------------ */
function sendPaymentToken(paymentTokenString) {
    return fetch('payment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            amount: "2.00",
            applePayToken: paymentTokenString
        })
    }).then(res => res.ok);
}
</script>

</body>
</html>
