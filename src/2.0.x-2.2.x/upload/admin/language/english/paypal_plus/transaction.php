<?php
// Heading
$_['heading_title']         = 'Transações PayPal Plus';

// Text
$_['text_list']             = 'Listagem de transações';
$_['text_all']              = 'Todos';
$_['text_pending']          = 'Pendente';
$_['text_analyze']          = 'Em análise';
$_['text_approved']         = 'Aprovada';
$_['text_completed']        = 'Completa';
$_['text_refunded']         = 'Reembolsada';
$_['text_canceled']         = 'Cancelada';
$_['text_denied']           = 'Recusada';
$_['text_reversed']         = 'Revertida';
$_['text_partially_refund'] = 'Parcialmente reembolsada';
$_['text_dispute']          = 'Em disputa';
$_['text_consulting']       = 'Consultando informações sobre a transação no PayPal para atualizar na loja...';
$_['text_consulted']        = 'Concluído! As informações da transação foram atualizadas na loja.';
$_['text_request_refund']   = 'Solicitando o reembolso para o PayPal...';
$_['text_solicited_refund'] = 'Concluído! O PayPal recebeu a solicitação de reembolso.';
$_['text_excluding']        = 'Excluindo a transação na loja...';
$_['text_excluded']         = 'Transação excluída.';
$_['text_confirm_yes']      = 'Sim, desejo confirmar';
$_['text_confirm_delete']   = '<h4>Você tem certeza que deseja excluir esta transação?<h4><p><b>Atenção:</b> Se confirmada, esta ação não poderá ser desfeita.</p>';
$_['text_confirm_refund']   = '<h4>Você tem certeza que deseja realizar o reembolso?<h4><p><b>Atenção:</b> Se confirmada, esta ação não poderá ser desfeita.</p>';
$_['text_sandbox']          = '<b>Atenção:</b><br>O ambiente sandbox está habilitado.';

// Info
$_['info_filter']           = '<b>Por padrão é exibido os últimos 3 dias de transações filtradas pela data do pedido</b>.<br>
                               Para visualizar transações anteriores aos últimos 3 dias, informe o período de datas desejado e clique no botão <b>Filtrar</b>.<br>
                               <b>Atenção:</b> Quanto maior o período de datas filtrado, maior será a quantidade de transações retornadas, o que poderá ocasionar lentidão na exibição das transações.';

// Modal
$_['modal_title_refund']    = 'Reembolsar transação';
$_['modal_title_details']   = 'Informações da transação';
$_['modal_payer_name']      = 'Pago por:';
$_['modal_payer_email']     = 'E-mail:';
$_['modal_invoice_number']  = 'Fatura no PayPal:';
$_['modal_approved']        = 'Valor da transação:';
$_['modal_installments']    = 'Parcelado em:';
$_['modal_refunded']        = 'Total reembolsado:';
$_['modal_status']          = 'Status no PayPal:';
$_['modal_solution']        = 'Realize o reembolso total ou parcial do valor pago pelo cliente através do PayPal.';
$_['modal_refund']          = 'Valor para reembolso:';
$_['modal_close']           = 'Fechar';
$_['modal_send']            = 'Confirmar reembolso';

// Tab
$_['tab_details']           = 'Detalhes';
$_['tab_last_json']         = 'Último Json';

// Column
$_['column_order_id']       = 'Pedido nº';
$_['column_date_added']     = 'Data do pedido';
$_['column_customer']       = 'Cliente';
$_['column_payment_id']     = 'Payment ID';
$_['column_total']          = 'Total';
$_['column_status']         = 'Status';
$_['column_action']         = 'Ação';

// Button
$_['button_print']          = 'Imprimir';
$_['button_copy']           = 'Copiar';
$_['button_csv']            = 'CSV';
$_['button_excel']          = 'Excel';
$_['button_pdf']            = 'PDF';
$_['button_columns']        = 'Colunas';
$_['button_filter']         = 'Filtrar';
$_['button_info']           = 'Informações da transação';
$_['button_search']         = 'Consultar transação no PayPal';
$_['button_refund']         = 'Reembolsar transação';
$_['button_delete']         = 'Excluir transação';

// Filter
$_['entry_initial_date']    = 'Data inicial do pedido:';
$_['entry_final_date']      = 'Data final do pedido:';
$_['entry_status']          = 'Status da transação:';

// Error
$_['error_permission']      = 'Atenção: Você não tem permissão para pesquisar as transações do PayPal Plus!';
$_['error_warning']         = 'A transação informada não é válida.';
$_['error_search']          = 'Não foi possível consultar a transação no PayPal.<br>Caso o debug esteja ativado, verifique o log para mais informações.';
$_['error_refund']          = 'Não foi possível executar o reembolso da transação no PayPal.<br>Caso o debug esteja ativado, verifique o log para mais informações.';
$_['error_refunded']        = 'O valor total da transação já foi reembolsado no PayPal.';
$_['error_value']           = 'O valor para reembolso deve ser menor ou igual ao valor da transação.';
$_['error_value_zero']      = 'Informe um valor para reembolso que seja válido.';
