<?php
// Heading
$_['heading_title']         = 'Transactions';

// Text
$_['text_list']             = 'List';
$_['text_paypal_plus']      = 'PayPal Plus';
$_['text_all']              = 'All';
$_['text_pending']          = 'Pending';
$_['text_analyze']          = 'In analysis';
$_['text_approved']         = 'Approved';
$_['text_completed']        = 'Complete';
$_['text_refunded']         = 'Refunded';
$_['text_canceled']         = 'Canceled';
$_['text_denied']           = 'Declined';
$_['text_reversed']         = 'Reversed';
$_['text_partially_refund'] = 'Partially refunded';
$_['text_dispute']          = 'In dispute';
$_['text_consulting']       = 'Searching transaction information on PayPal to update in store...';
$_['text_consulted']        = 'Concluded! The transaction information has been updated in store.';
$_['text_request_refund']   = 'Requesting a refund to PayPal...';
$_['text_solicited_refund'] = 'Concluded! PayPal received refund request.';
$_['text_excluding']        = 'Deleting transaction in store...';
$_['text_excluded']         = 'Transaction deleted.';
$_['text_confirm_yes']      = 'Yes, confirm';
$_['text_confirm_delete']   = '<h4>Are you sure you want to delete this transaction??<h4><p><b>Warning!</b> If confirmed, this action cannot be undone.</p>';
$_['text_confirm_refund']   = '<h4>Are you sure you want to make a refund?<h4><p><b>Warning!</b> If confirmed, this action cannot be undone.</p>';
$_['text_sandbox']          = '<b>Warning!</b><br>Sandbox environment is enabled.';

// Info
$_['info_filter']           = '<b>By default, the last 3 days of transactions filtered by order date are displayed</b>.<br>
                               To view transactions older than the last 3 days, enter desired date range and click the button <b>Filter</b>.<br>
                               <b>Warning!</b> The longer filtered date range, greater number of returned transactions, which may cause transactions to be displayed slowly.';

// Modal
$_['modal_title_refund']    = 'Refund transaction';
$_['modal_title_details']   = 'Transaction information';
$_['modal_payer_name']      = 'Paid by:';
$_['modal_payer_email']     = 'Email:';
$_['modal_invoice_number']  = 'Invoice on PayPal:';
$_['modal_approved']        = 'Transaction Amount:';
$_['modal_installments']    = 'Installments:';
$_['modal_refunded']        = 'Total refunded:';
$_['modal_status']          = 'PayPal Status:';
$_['modal_solution']        = 'Make a full or partial refund of amount paid by customer through PayPal.';
$_['modal_refund']          = 'Refund amount:';
$_['modal_close']           = 'Close';
$_['modal_send']            = 'Confirm refund';

// Tab
$_['tab_details']           = 'Details';
$_['tab_last_json']         = 'Last Json';

// Column
$_['column_order_id']       = 'Order ID';
$_['column_date_added']     = 'Request Date';
$_['column_customer']       = 'Customer';
$_['column_payment_id']     = 'Payment ID';
$_['column_total']          = 'Total';
$_['column_status']         = 'Status';
$_['column_action']         = 'Action';

// Button
$_['button_print']          = 'Print';
$_['button_copy']           = 'Copy';
$_['button_csv']            = 'CSV';
$_['button_excel']          = 'Excel';
$_['button_pdf']            = 'PDF';
$_['button_columns']        = 'Columns';
$_['button_filter']         = 'Filter';
$_['button_info']           = 'Transaction information';
$_['button_search']         = 'Search transaction on PayPal';
$_['button_refund']         = 'Refund transaction';
$_['button_delete']         = 'Delete transaction';

// Filter
$_['entry_initial_date']    = 'Order start date:';
$_['entry_final_date']      = 'Order end date:';
$_['entry_status']          = 'Transaction status:';

// Error
$_['error_permission']      = 'Warning! You are not allowed to search PayPal Plus transactions!';
$_['error_warning']         = 'Transaction is not valid.';
$_['error_search']          = 'Transaction could not be viewed on PayPal.<br>If debugging is enabled, check the log for more information.';
$_['error_refund']          = 'Transaction refund on PayPal could not be performed.<br>If debugging is enabled, check the log for more information.';
$_['error_refunded']        = 'Full amount of the transaction has already been refunded to PayPal.';
$_['error_value']           = 'Refund amount must be less than or equal to transaction amount.';
$_['error_value_zero']      = 'Enter a valid refund amount.';
