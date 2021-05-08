<?php
// Heading
$_['heading_title']                = 'PayPal Plus';

// Text
$_['text_extension']               = 'Extensions';
$_['text_success']                 = 'Genial: Usted ha modificado PayPal Plus!';
$_['text_edit']                    = 'Editar PayPal Plus';
$_['text_paypal_plus']             = '<a target="_blank" href="https://www.paypal.com/merchantapps/appcenter/acceptpayments/paypalplus"><img src="view/image/payment/paypal_plus.jpg" alt="PayPal Plus" title="PayPal Plus" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_requirements']            = 'Requisitos obligatorios:';
$_['text_upgrade']                 = 'La nueva versión <b>%s</b> está disponible para descargar.';
$_['text_brazil']                  = 'Brasil';
$_['text_mexico']                  = 'México';
$_['text_united_states']           = 'Estados Unidos';
$_['text_prefix']                  = 'Prefijo en el código de pedido';
$_['text_field']                   = 'Campo:';
$_['text_column']                  = 'Columna en la tabla de pedidos';
$_['text_document']                = 'Columna CPF:';
$_['text_number_payment']          = 'Columna de número de factura:';
$_['text_number_shipping']         = 'Columna de número de entrega:';
$_['text_complement_payment']      = 'Columna de complemento de factura:';
$_['text_complement_shipping']     = 'Columna de complemento para entrega:';
$_['text_btn_default']             = 'Default';
$_['text_btn_primary']             = 'Primary';
$_['text_btn_success']             = 'Success';
$_['text_btn_info']                = 'Info';
$_['text_btn_warning']             = 'Warning';
$_['text_btn_danger']              = 'Danger';
$_['text_webhook_loading']         = 'Cargando datos de Webhook...';
$_['text_webhook_add']             = 'Cargando eventos para habilitar Webhook...';
$_['text_webhook_saving']          = 'Guardar eventos de Webhook...';
$_['text_webhook_deleting']        = 'Eliminar el webhook...';
$_['text_webhook_new']             = 'Habilitar Webhook';
$_['text_webhook_save']            = 'Guardar Webhook';
$_['text_confirm_yes']             = 'Si, quiero confirmar';
$_['text_confirm_delete']          = '<h4>¿Está seguro de que desea eliminar el webhook??<h4><p><b>Advertencia:</b> Si se confirma, esta acción no se puede deshacer.</p>';
$_['text_webhook_deleted']         = 'Webhook eliminado correctamente!';
$_['text_webhook_created']         = 'Webhook habilitado correctamente!';
$_['text_dispute_created']         = 'Cuando el cliente abre una disputa.';
$_['text_sale_completed']          = 'Cuando se completa el pago.';
$_['text_sale_denied']             = 'Cuando se niega el pago.';
$_['text_sale_pending']            = 'Cuando el pago está pendiente.';
$_['text_sale_refunded']           = 'Cuando se reembolsa el pago.';
$_['text_sale_reversed']           = 'Cuando se invierte el pago.';

// Info
$_['info_general']                 = 'Complete la configuración básica de la extensión.';
$_['info_store']                   = 'Seleccione las tiendas donde la extensión será una opción de pago.';
$_['info_api']                     = 'Complete la configuración para la integración con PayPal Plus.<br>
                                      <b>Importante:</b> Si no tiene las credenciales de PayPal Plus, comuníquese con el soporte comercial de PayPal.';
$_['info_order_status']            = 'Seleccione las situaciones que se asignarán automáticamente al pedido como devoluciones de PayPal.';
$_['info_custom_field']            = 'Configuración para identificar información adicional en el registro del cliente que debe enviarse a PayPal.<br>
                                      <b>Importante:</b> Para registrar campos personalizados, vaya al menú <b>Clientes > Campos Personalizados</b>.<br>
                                      <b>Observación:</b> Si los campos adicionales se crearon directamente en la tabla de pedidos, seleccione la opción "<b>Columna en la tabla de pedidos</b>", y seleccione la columna en la tabla <i>*_order</i> correspondiente.';
$_['info_checkout']                = 'La siguiente información se utilizará para completar el pedido..';
$_['info_webhook']                 = 'Para recibir notificaciones de PayPal sobre cambios en la situación de pago, debe habilitar y guardar Webhook.';
$_['info_webhook_credencials']     = 'Para recibir notificaciones de PayPal a través de Webhook, primero debe completar las credenciales en la pestaña <b>API</b> y haga clic en el botón <b>Guardar</b>.';

// Tab
$_['tab_general']                  = 'Ajustes';
$_['tab_store']                    = 'Tiendas';
$_['tab_api']                      = 'API';
$_['tab_order_status']             = 'Situaciones';
$_['tab_custom_field']             = 'Datos del cliente';
$_['tab_checkout']                 = 'Caja';
$_['tab_webhook']                  = 'Webhook';

// Columns
$_['column_name']                  = 'Evento';
$_['column_description']           = 'Descripción';
$_['column_status']                = 'Estado';
$_['column_webhook_id']            = 'ID de Webhook';
$_['column_webhook_url']           = 'URL de Webhook';
$_['column_webhook_actions']       = 'Acción';

// Entry
$_['entry_customer_groups']        = 'Tipos de clientes:';
$_['entry_total']                  = 'Total mínimo:';
$_['entry_geo_zone']               = 'Geo zonas:';
$_['entry_status']                 = 'Estado:';
$_['entry_sort_order']             = 'Orden:';
$_['entry_stores']                 = 'Tiendas:';
$_['entry_store']                  = 'Tienda:';
$_['entry_client_id']              = 'Client ID:';
$_['entry_client_secret']          = 'Secret:';
$_['entry_sandbox']                = 'Sandbox:';
$_['entry_country']                = 'País:';
$_['entry_debug']                  = 'Depurar:';
$_['entry_save_card']              = 'Guardar tarjeta:';
$_['entry_description']            = 'Descripción:';
$_['entry_order_status_pending']   = 'Transacción pendiente:';
$_['entry_order_status_denied']    = 'Transacción no autorizada:';
$_['entry_order_status_analyze']   = 'Transacción en revisión:';
$_['entry_order_status_completed'] = 'Transacción completa:';
$_['entry_order_status_refunded']  = 'Transacción reembolsada:';
$_['entry_order_status_dispute']   = 'Transacción de disputa:';
$_['entry_order_status_reversed']  = 'Transacción revertida:';
$_['entry_custom_document_id']     = 'CPF:';
$_['entry_custom_number_id']       = 'Número de dirección:';
$_['entry_custom_complement_id']   = 'Complemento de direcciones:';
$_['entry_extension_title']        = 'Título de la extensión:';
$_['entry_information']            = 'Instrucciones:';
$_['entry_button_style']           = 'Estilo del botón confirmar:';
$_['entry_button_text']            = 'Texto del botón confirmar:';

// Help
$_['help_customer_groups']         = 'Tipos de clientes que podrán seleccionar la extensión al finalizar la compra.';
$_['help_total']                   = 'Valor mínimo que debe alcanzar el pedido para que se habilite la extensión. Déjelo en blanco si no hay un valor mínimo.';
$_['help_stores']                  = 'Tiendas donde se mostrará la extensión al finalizar la compra.';
$_['help_prefix']                  = 'Rellena solo si utilizas la extensión en varias tiendas donde se puede repetir el código de pedido, o si quieres distinguir pedidos de cada tienda.';
$_['help_client_id']               = 'Se genera con la orientación de PayPal.';
$_['help_client_secret']           = 'Se genera con la orientación de PayPal.';
$_['help_sandbox']                 = 'Si es así, active el modo de prueba.';
$_['help_country']                 = 'Seleccione el país de su cuenta de PayPal, para que el iframe se represente correctamente.';
$_['help_debug']                   = 'En caso afirmativo, almacena en el registro la información recibida a través de la API de PayPal Plus.';
$_['help_save_card']               = 'En caso afirmativo, PayPal ofrece la opción de almacenar los datos de la tarjeta para compras posteriores.';
$_['help_description']             = 'Descripción de pago para PayPal.';
$_['help_order_status_pending']    = 'Quando for iniciada.';
$_['help_order_status_denied']     = 'Cuando se niega.';
$_['help_order_status_analyze']    = 'En revisión por PayPal.';
$_['help_order_status_completed']  = 'Cuando se complete en PayPal.';
$_['help_order_status_refunded']   = 'Cuando se reembolsa.';
$_['help_order_status_dispute']    = 'Cuando el cliente abre una disputa.';
$_['help_order_status_reversed']   = 'Cuando se revierte la disputa.';
$_['help_custom_document_id']      = 'Seleccione el campo que almacena el CPF en el registro del cliente. (Solo en Brasil)';
$_['help_custom_number_id']        = 'Seleccione el campo que almacena el número en la dirección del cliente.';
$_['help_custom_complement_id']    = 'Seleccione solo si tiene el campo para completar el complemento en la dirección del cliente.';
$_['help_extension_title']         = 'Se mostrará al finalizar la compra al seleccionar el método de pago.';
$_['help_information']             = 'Se mostrará al finalizar la compra antes del formulario para completar los detalles de la tarjeta.';
$_['help_button_style']            = 'Se basa en el estándar Bootstrap.';
$_['help_button_text']             = 'Se mostrará dentro del botón.';

// Error
$_['error_permission']             = 'Advertencia: No se le permite modificar la extensión PayPal Plus!';
$_['error_warning']                = '<b>Advertencia:</b> La extensión no se configuró correctamente! Verifique todos los campos para corregir errores.';
$_['error_requirements']           = '<b>Advertencia:</b> Deben cumplirse los requisitos obligatorios.';
$_['error_stores']                 = 'Debes seleccionar al menos una tienda.';
$_['error_customer_groups']        = 'Debes seleccionar al menos un tipo de cliente.';
$_['error_field_column']           = 'Seleccione la columna.';
$_['error_required']               = 'Campo obligatorio.';
$_['error_prefix']                 = 'Complete el prefijo de esta tienda.';
$_['error_webhook_event_types']    = 'No fue posible enumerar los eventos para el Webhook. Compruebe que ha introducido las credenciales correctamente y vuelva a intentarlo.';
$_['error_webhook_indisponible']   = 'No se pudo habilitar Webhook. Compruebe que ha introducido las credenciales correctamente y vuelva a intentarlo.';
$_['error_webhook_remove']         = 'No se pudo eliminar el Webhook. Compruebe que ha introducido las credenciales correctamente y vuelva a intentarlo.';
$_['error_webhook_empty']          = 'Seleccione al menos un evento.';
$_['error_webhook_localhost']      = 'No es posible guardar el Webhook desde un servidor local.';
