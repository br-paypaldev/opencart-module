<?php
// Heading
$_['heading_title']                = 'PayPal Plus';

// Text
$_['text_extension']               = 'Extensions';
$_['text_success']                 = 'Success: You have modified bank PayPal Plus details!';
$_['text_edit']                    = 'Edit PayPal Plus';
$_['text_paypal_plus']             = '<a target="_blank" href="https://www.paypal.com/merchantapps/appcenter/acceptpayments/paypalplus"><img src="view/image/payment/paypal_plus.jpg" alt="PayPal Plus" title="PayPal Plus" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_requirements']            = 'Requirements:';
$_['text_upgrade']                 = 'New version <b>%s</b> is available for download.';
$_['text_brazil']                  = 'Brazil';
$_['text_mexico']                  = 'Mexico';
$_['text_united_states']           = 'United States';
$_['text_prefix']                  = 'Prefix in requested code';
$_['text_field']                   = 'Field:';
$_['text_column']                  = 'Column in order table';
$_['text_document']                = 'Column CPF:';
$_['text_number_payment']          = 'Column Payment Address Number:';
$_['text_number_shipping']         = 'Column Shipping Address Number:';
$_['text_complement_payment']      = 'Column Payment Address Complement:';
$_['text_complement_shipping']     = 'Column Shipping Address Complement:';
$_['text_btn_default']             = 'Default';
$_['text_btn_primary']             = 'Primary';
$_['text_btn_success']             = 'Success';
$_['text_btn_info']                = 'Info';
$_['text_btn_warning']             = 'Warning';
$_['text_btn_danger']              = 'Danger';
$_['text_webhook_loading']         = 'Loading Webhook data...';
$_['text_webhook_add']             = 'Loading events to enable Webhook...';
$_['text_webhook_saving']          = 'Saving Webhook events...';
$_['text_webhook_deleting']        = 'Deleting Webhook...';
$_['text_webhook_new']             = 'Enable Webhook';
$_['text_webhook_save']            = 'Save Webhook';
$_['text_confirm_yes']             = 'Yes, confirm';
$_['text_confirm_delete']          = '<h4>Are you sure you want to delete Webhook?<h4><p><b>Warning!</b> If confirmed, this action cannot be undone.</p>';
$_['text_webhook_deleted']         = 'Webhook successfully deleted!';
$_['text_webhook_created']         = 'Webhook successfully enabled!';
$_['text_dispute_created']         = 'When customer open a dispute.';
$_['text_sale_completed']          = 'When payment is completed.';
$_['text_sale_denied']             = 'When payment is denied.';
$_['text_sale_pending']            = 'When payment is pending.';
$_['text_sale_refunded']           = 'When payment is refunded.';
$_['text_sale_reversed']           = 'When payment is reversed.';

// Info
$_['info_general']                 = 'Fill in basic settings for extension.';
$_['info_store']                   = 'Select stores where extension will be a payment option.';
$_['info_api']                     = 'Fill in settings for integration with PayPal Plus.<br>
                                      <b>Important!</b> If you do not have PayPal Plus credentials, contact PayPal business support.';
$_['info_order_status']            = 'Select situations that will be automatically assigned to order as PayPal returns.';
$_['info_custom_field']            = 'Settings to identify extra information in customer registration that must be sent to PayPal.<br>
                                      <b>Important!</b> To register custom fields, go to menu <b>Customer > Custom Fields</b> and register custom field.<br>
                                      <b>Note!</b> If custom fields were created directly in order table, select option "<b>Column in order table</b>", and select column in table <i>*_order</i>.';
$_['info_checkout']                = 'Information below will be used to complete order.';
$_['info_webhook']                 = 'To receive notifications from PayPal about changes in payment situation, you must enable and save Webhook.';
$_['info_webhook_credencials']     = 'To receive PayPal notifications via Webhook, you must first fill in credentials on tab <b>API</b>.';

// Tab
$_['tab_general']                  = 'Settings';
$_['tab_store']                    = 'Stores';
$_['tab_api']                      = 'API';
$_['tab_order_status']             = 'Order Status';
$_['tab_custom_field']             = 'Customer Data';
$_['tab_checkout']                 = 'Checkout';
$_['tab_webhook']                  = 'Webhook';

// Columns
$_['column_name']                  = 'Event';
$_['column_description']           = 'Description';
$_['column_status']                = 'Status';
$_['column_webhook_id']            = 'Webhook ID';
$_['column_webhook_url']           = 'Webhook URL';
$_['column_webhook_actions']       = 'Action';

// Entry
$_['entry_customer_groups']        = 'Customer Group:';
$_['entry_total']                  = 'Total:';
$_['entry_geo_zone']               = 'Geo Zone:';
$_['entry_status']                 = 'Status:';
$_['entry_sort_order']             = 'Order:';
$_['entry_stores']                 = 'Stores:';
$_['entry_store']                  = 'Store:';
$_['entry_client_id']              = 'Client ID:';
$_['entry_client_secret']          = 'Secret:';
$_['entry_sandbox']                = 'Sandbox:';
$_['entry_country']                = 'Country:';
$_['entry_debug']                  = 'Debug:';
$_['entry_save_card']              = 'Save card:';
$_['entry_description']            = 'Description:';
$_['entry_order_status_pending']   = 'Pending transaction:';
$_['entry_order_status_denied']    = 'Unauthorized transaction:';
$_['entry_order_status_analyze']   = 'Transaction under review:';
$_['entry_order_status_completed'] = 'Complete transaction:';
$_['entry_order_status_refunded']  = 'Refunded transaction:';
$_['entry_order_status_dispute']   = 'Dispute transaction:';
$_['entry_order_status_reversed']  = 'Transaction reversed:';
$_['entry_custom_document_id']     = 'Document CPF:';
$_['entry_custom_number_id']       = 'Address Number:';
$_['entry_custom_complement_id']   = 'Address Complement:';
$_['entry_extension_title']        = 'Extension title:';
$_['entry_information']            = 'Instructions:';
$_['entry_button_style']           = 'Confirm button style:';
$_['entry_button_text']            = 'Confirm button text:';

// Help
$_['help_customer_groups']         = 'Customers Groups who will be able to select extension at checkout.';
$_['help_total']                   = 'Minimum amount that order must reach for extension to be enabled. Leave blank if there is no minimum value.';
$_['help_stores']                  = 'Stores where extension will be shown at checkout.';
$_['help_prefix']                  = 'Only fill in if you use extension in several stores where order code can be repeated, or if you want to distinguish orders from each store.';
$_['help_client_id']               = 'It is generated with PayPal guide.';
$_['help_client_secret']           = 'It is generated with PayPal guide.';
$_['help_sandbox']                 = 'If Yes, activate sandbox mode.';
$_['help_country']                 = 'Select country of your PayPal account, so that iframe is rendered correctly.';
$_['help_debug']                   = 'If Yes, log information received through PayPal Plus API.';
$_['help_save_card']               = 'If Yes, PayPal offers option to save card data for later purchases.';
$_['help_description']             = 'Payment description for PayPal.';
$_['help_order_status_pending']    = 'When it starts.';
$_['help_order_status_denied']     = 'When it is denied.';
$_['help_order_status_analyze']    = 'Under review by PayPal.';
$_['help_order_status_completed']  = 'When it is completed in PayPal.';
$_['help_order_status_refunded']   = 'When it is refunded.';
$_['help_order_status_dispute']    = 'When customer open dispute.';
$_['help_order_status_reversed']   = 'When dispute is reversed.';
$_['help_custom_document_id']      = 'Select field Document CPF in Customer Account. (Mandatory in Brazil)';
$_['help_custom_number_id']        = 'Select field Address Number in Customer Address.';
$_['help_custom_complement_id']    = 'Only select field if exists Address Complement in customer address.';
$_['help_extension_title']         = 'It will be displayed at checkout when selecting payment method.';
$_['help_information']             = 'It will be displayed at checkout before the form for filling out the card details.';
$_['help_button_style']            = 'It is based on Bootstrap Framework.';
$_['help_button_text']             = 'It will be displayed inside button.';

// Error
$_['error_permission']             = 'Warning! You do not have permission to modify payment PayPal Plus!';
$_['error_warning']                = '<b>Warning!</b> The extension was not set up correctly! Check all fields to correct errors.';
$_['error_requirements']           = '<b>Warning!</b> Mandatory requirements must be met.';
$_['error_stores']                 = 'You must select at least one store.';
$_['error_customer_groups']        = 'You must select at least one customer group.';
$_['error_field_column']           = 'Select column.';
$_['error_required']               = 'Required field.';
$_['error_prefix']                 = 'Fill in prefix for this store.';
$_['error_webhook_event_types']    = 'It was not possible to list the events for Webhook. Check that you have filled in credentials correctly and try again.';
$_['error_webhook_indisponible']   = 'Could not enable Webhook. Check that you have filled in credentials correctly and try again.';
$_['error_webhook_remove']         = 'Webhook could not be removed. Check that you have filled in credentials correctly and try again.';
$_['error_webhook_empty']          = 'Please select at least one event.';
$_['error_webhook_localhost']      = 'It is not possible to save Webhook from a local server.';
