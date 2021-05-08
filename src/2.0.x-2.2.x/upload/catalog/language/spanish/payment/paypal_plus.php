<?php
// Text
$_['text_redirect']          = 'Recibiendo datos de PayPal, espere...';
$_['text_pending']           = 'Esperando confirmación de pago.';
$_['text_denied']            = 'Tu pago no fue aprobado.';
$_['text_analyze']           = 'Su pago se está revisando en PayPal.';
$_['text_completed']         = 'Su pago ha sido confirmado y está bajo revisión para la liberación del pedido..';
$_['text_sandbox']           = '<b>Advertencia:</b><br>Estás en el entorno de la sandbox.<br>Utilice una tarjeta de prueba para realizar el pago.';

// Button
$_['button_try_again']       = 'Intentar nuevamente';

// Log
$_['log_header_invalid']     = 'Webhook Error: El header no contiene los datos esperados.';
$_['log_body_empty']         = 'Webhook Error: El body esta vacio.';
$_['log_body_invalid']       = 'Webhook Error: El body no contiene los datos esperados.';
$_['log_signature_fail']     = 'Webhook Error: Verificación de firma fallida.';
$_['log_signature_success']  = 'Se ha confirmado la suscripción a webhook.';
$_['log_webhook_id_invalid'] = 'Webhook Error: La tienda no autorizó el ID de webhook.';
$_['log_status_empty']       = 'Webhook Error: El evento notificado no fue autorizado por la tienda.';
$_['log_sale_id_empty']      = 'Webhook Error: La notificación recibida no contiene el ID de transacción.';
$_['log_order_id_empty']     = 'Webhook Error: No se pudo encontrar el pedido vinculado al ID de transacción.';
$_['log_success']            = 'El webhook se procesó correctamente.';

// Error
$_['error_api']              = '<b>No se pudo comunicar con PayPal.</b><br>Vuelve a intentarlo o selecciona otro tipo de pago.<br>Si el problema persiste, comuníquese con nuestro servicio de atención al cliente.';
$_['error_order']            = '<b>Hubo un error causado por problemas con los datos del pedido.</b><br>Si el problema persiste, comuníquese con nuestro servicio de atención al cliente.';
$_['error_card_issuer']      = '<b>El pago no fue aprobado por el emisor de la tarjeta.</b><br>Si el problema persiste, , comuníquese con el emisor de la tarjeta utilizada.';
$_['error_paypal']           = '<b>PayPal no aprobó el pago.</b><br>Pruebe con otra tarjeta o seleccione otra tipo de pago.';
$_['error_approval']         = '<b>El pago no fue aprobado.</b><br>Comprueba que has cumplimentado correctamente todos los campos y que la tarjeta utilizada tiene límite suficiente para el pago del pedido.<br><b>Importante:</b> Si su tarjeta está bloqueada o restringida, su pago no será autorizado.<br><br>Puede intentar usar otra tarjeta o seleccionar otro método de pago.<br>Si tiene alguna pregunta, comuníquese con nuestro servicio.';
$_['error_register']         = '<b>Advertencia:</b><br>Los datos a continuación deben completarse correctamente:<br><b>%s</b><br>Después de corregir los datos, podrá finalizar su pedido nuevamente.<br>Si tiene alguna pregunta, comuníquese con nuestro servicio de atención al cliente.';
$_['error_configuration']    = '<b>Advertencia:</b><br>Estamos experimentando problemas técnicos.<br>Vuelva a intentarlo más tarde o seleccione otro tipo de pago.<br>Si tiene alguna pregunta, comuníquese con nuestro servicio de atención al cliente.';
$_['error_try_new_card']     = '<b>Advertencia:</b><br>Su pago no fue aprobado. Utilice otra tarjeta.<br>Si el problema persiste, comuníquese con nuestro servicio de atención al cliente.';
$_['error_try_limit']        = '<b>Advertencia:</b><br>Has superado el límite de intentos. Utilice otra tarjeta.<br>Si el problema persiste, comuníquese con nuestro servicio de atención al cliente.';
$_['error_session_expire']   = '<b>Advertencia:</b><br>Se agotó el tiempo para intentar realizar el pago.<br>Vuelva a intentarlo.<br>Si el problema persiste, comuníquese con nuestro servicio de atención al cliente.';
$_['error_unexpected']       = '<b>Advertencia:</b><br>Se produjo un error inesperado.<br>Vuelva a intentarlo.<br>Si el problema persiste, comuníquese con nuestro servicio de atención al cliente.';
