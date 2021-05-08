<?php
// Heading
$_['heading_title']         = 'Transacciones';

// Text
$_['text_list']             = 'Listado de transacciones';
$_['text_paypal_plus']      = 'PayPal Plus';
$_['text_all']              = 'Todo';
$_['text_pending']          = 'Pendiente';
$_['text_analyze']          = 'En análisis';
$_['text_approved']         = 'Aprobado';
$_['text_completed']        = 'Completo';
$_['text_refunded']         = 'Reintegrado';
$_['text_canceled']         = 'Cancelado';
$_['text_denied']           = 'Rechazado';
$_['text_reversed']         = 'Invertido';
$_['text_partially_refund'] = 'Reintegrado parcialmente';
$_['text_dispute']          = 'En disputa';
$_['text_consulting']       = 'Consultando información de transacciones en PayPal para actualizar en la tienda...';
$_['text_consulted']        = '¡Concluido! La información de la transacción se ha actualizado en la tienda.';
$_['text_request_refund']   = 'Solicitando un reembolso a PayPal...';
$_['text_solicited_refund'] = '¡Concluido! PayPal recibió la solicitud de reembolso.';
$_['text_excluding']        = 'Eliminando transacción en tienda...';
$_['text_excluded']         = 'Transacción eliminada.';
$_['text_confirm_yes']      = 'Si, quiero confirmar';
$_['text_confirm_delete']   = '<h4>¿Está seguro de que desea eliminar esta transacción?<h4><p><b>Advertencia:</b> Se confirmada, esta ação não poderá ser desfeita.</p>';
$_['text_confirm_refund']   = '<h4>¿Estás seguro de que quieres hacer un reembolso?<h4><p><b>Advertencia:</b> Se confirmada, esta ação não poderá ser desfeita.</p>';
$_['text_sandbox']          = '<b>Advertencia:</b><br>El entorno de la sandbox está habilitado.';

// Info
$_['info_filter']           = '<b>De forma predeterminada, se muestran los últimos 3 días de transacciones filtradas por la fecha del pedido.</b>.<br>
                               Para ver transacciones anteriores a los últimos 3 días, ingrese el rango de fechas deseado y haga clic en el botón <b>Filtrar</b>.<br>
                               <b>Advertencia:</b> Cuanto más largo sea el intervalo de fechas filtrado, mayor será la cantidad de transacciones devueltas, lo que puede hacer que la transacción sea lenta en mostrarse.';

// Modal
$_['modal_title_refund']    = 'Reembolso';
$_['modal_title_details']   = 'Pagar';
$_['modal_payer_name']      = 'Pagado por:';
$_['modal_payer_email']     = 'E-mail:';
$_['modal_invoice_number']  = 'Factura en PayPal:';
$_['modal_approved']        = 'Valor:';
$_['modal_installments']    = 'Cuota en:';
$_['modal_refunded']        = 'Total reembolsado:';
$_['modal_status']          = 'Estado de PayPal:';
$_['modal_solution']        = 'Realize o reembolso total ou parcial do valor pago pelo cliente através do PayPal.';
$_['modal_refund']          = 'Cantidad devuelta:';
$_['modal_close']           = 'Salir';
$_['modal_send']            = 'Confirmar reembolso';

// Tab
$_['tab_details']           = 'Detalles';
$_['tab_last_json']         = 'Ultimo Json';

// Column
$_['column_order_id']       = 'Pedidos ID';
$_['column_date_added']     = 'Fecha de pedido';
$_['column_customer']       = 'Cliente';
$_['column_payment_id']     = 'Payment ID';
$_['column_total']          = 'Total';
$_['column_status']         = 'Estado';
$_['column_action']         = 'Acción';

// Button
$_['button_print']          = 'Imprimir';
$_['button_copy']           = 'Dupdo';
$_['button_csv']            = 'CSV';
$_['button_excel']          = 'Hoja de cálculo';
$_['button_pdf']            = 'PDF';
$_['button_columns']        = 'Columnas';
$_['button_filter']         = 'Filtrar';
$_['button_info']           = 'Información de la transacción';
$_['button_search']         = 'Consultar transacción en PayPal';
$_['button_refund']         = 'Transacción de reembolso';
$_['button_delete']         = 'Eliminar transacción';

// Filter
$_['entry_initial_date']    = 'Fecha de inicio del pedido:';
$_['entry_final_date']      = 'Fecha de finalización del pedido:';
$_['entry_status']          = 'Estado de la transacción:';

// Error
$_['error_permission']      = 'Advertencia: No se le permite buscar transacciones en el PayPal Plus!';
$_['error_warning']         = 'La transacción informada no es válida.';
$_['error_search']          = 'La transacción no se pudo ver en PayPal.<br>Si la depuración está habilitada, consulte el registro para obtener más información.';
$_['error_refund']          = 'No se pudo realizar el reembolso de la transacción en PayPal.<br>Si la depuración está habilitada, consulte el registro para obtener más información.';
$_['error_refunded']        = 'El importe total de la transacción ya se ha reembolsado a PayPal..';
$_['error_value']           = 'El monto del reembolso debe ser menor o igual al monto de la transacción..';
$_['error_value_zero']      = 'Ingrese un monto de reembolso válido.';
