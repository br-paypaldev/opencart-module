<?php
// Text
$_['text_redirect']          = 'Receiving data from PayPal, please wait ...';
$_['text_pending']           = 'Waiting for payment confirmation.';
$_['text_denied']            = 'Your payment was not approved.';
$_['text_analyze']           = 'Your payment is being reviewed by PayPal.';
$_['text_completed']         = 'Your payment has been confirmed, however, it is under analysis to release the order.';
$_['text_sandbox']           = '<b>Warning!</b><br>You are in the sandbox environment.<br>Use a test card to make the payment.';

// Button
$_['button_try_again']       = 'Try again';

// Log
$_['log_header_invalid']     = 'Webhook Error: Header does not contain the expected data.';
$_['log_body_empty']         = 'Webhook Error: Body is empty.';
$_['log_body_invalid']       = 'Webhook Error: Body does not contain the expected data.';
$_['log_signature_fail']     = 'Webhook Error: Signature verification failed.';
$_['log_signature_success']  = 'Signature has been confirmed.';
$_['log_webhook_id_invalid'] = 'Webhook Error: Webhook ID was not authorized by the store.';
$_['log_status_empty']       = 'Webhook Error: Notified event was not authorized by the store.';
$_['log_sale_id_empty']      = 'Webhook Error: Notification received does not contain the transaction ID.';
$_['log_order_id_empty']     = 'Webhook Error: Order ID linked to the transaction ID could not be found.';
$_['log_success']            = 'Webhook was successfully processed.';

// Error
$_['error_api']              = '<b>There was a failure to communicate with PayPal.</b><br>Try again or select another form of payment.<br>If the problem persists, contact our customer service.';
$_['error_order']            = '<b>There was a failure caused by problems with the order data.</b><br>Contact our service and report the problem.';
$_['error_card_issuer']      = '<b>Payment was not approved by the card issuer.</b><br>If in doubt, contact the issuer of the card used.';
$_['error_paypal']           = '<b>Payment was not approved by PayPal.</b><br>Try another card or select another form of payment.';
$_['error_approval']         = '<b>Payment was not approved.</b><br>Check that you have filled in all the fields correctly and that the card used has a sufficient limit for payment of the order.<br><br>You can try to use another card or select another type of payment.<br>In case of doubts, please contact our customer service.';
$_['error_register']         = '<b>Warning!</b><br>Data below needs to be filled in correctly:<br><b>%s</b><br>After correcting data you will be able to finalize your order again.<br>In case of doubts, please contact our customer service.';
$_['error_configuration']    = '<b>Warning!</b><br>We are experiencing technical problems.<br>Try again later or select another form of payment.<br>In case of doubts, please contact our customer service.';
$_['error_try_new_card']     = '<b>Warning!</b><br>Your payment was not approved. Please use another card.<br>If the problem persists, contact our customer service.';
$_['error_try_limit']        = '<b>Warning!</b><br>You have exceeded the attempted limit. Please use another card.<br>If the problem persists, contact our customer service.';
$_['error_session_expire']   = '<b>Warning!</b><br>Time to try to make the payment has elapsed.<br>Please try again.<br>If the problem persists, contact our customer service.';
$_['error_unexpected']       = '<b>Warning!</b><br>An unexpected error has occurred.<br>Please try again.<br>If the problem persists, contact our customer service.';
