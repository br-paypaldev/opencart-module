<?php
// Text
$_['text_redirect']          = 'Recebendo dados do PayPal, aguarde...';
$_['text_pending']           = 'Aguardando a confirmação do pagamento.';
$_['text_denied']            = 'Seu pagamento não foi aprovado.';
$_['text_analyze']           = 'Seu pagamento está em análise no PayPal.';
$_['text_completed']         = 'Seu pagamento foi confirmado, porém, está em análise para liberação do pedido.';
$_['text_sandbox']           = '<b>Atenção:</b><br>Você está no ambiente sandbox.<br>Utilize um cartão de teste para realizar o pagamento.';

// Button
$_['button_try_again']       = 'Tentar novamente';

// Log
$_['log_header_invalid']     = 'Webhook Error: O header não contém os dados esperados.';
$_['log_body_empty']         = 'Webhook Error: O body está vazio.';
$_['log_body_invalid']       = 'Webhook Error: O body não contém os dados esperados.';
$_['log_signature_fail']     = 'Webhook Error: A verificação da assinatura falhou.';
$_['log_signature_success']  = 'A assinatura do Webhook foi confirmada.';
$_['log_webhook_id_invalid'] = 'Webhook Error: O ID do webhook não foi autorizado pela loja.';
$_['log_status_empty']       = 'Webhook Error: O evento notificado não foi autorizado pela loja.';
$_['log_sale_id_empty']      = 'Webhook Error: A notificação recebida não contém o ID da transação.';
$_['log_order_id_empty']     = 'Webhook Error: Não foi possível localizar o pedido vinculado ao ID da transação.';
$_['log_success']            = 'O Webhook foi processado com sucesso.';

// Error
$_['error_api']              = '<b>Houve uma falha na comunicação com o PayPal.</b><br>Tente novamente ou selecione outra forma de pagamento.<br>Caso o problema persista, entre em contato com nosso atendimento.';
$_['error_order']            = '<b>Houve uma falha causada por problemas nos dados do pedido.</b><br>Entre em contato com nosso atendimento e comunique o problema.';
$_['error_card_issuer']      = '<b>O pagamento não foi aprovado pelo emissor do cartão.</b><br>Em caso de dúvidas, entre em contato com o emissor do cartão utilizado.';
$_['error_paypal']           = '<b>O pagamento não foi aprovado pelo PayPal.</b><br>Tente outro cartão ou selecione outra forma de pagamento.';
$_['error_approval']         = '<b>O pagamento não foi aprovado.</b><br>Verifique se você preencheu todos os campos corretamente e se o cartão utilizado possui limite suficiente para o pagamento do pedido.<br><b>Importante: </b> Se o seu cartão estiver bloqueado ou com alguma restrição, seu pagamento não será autorizado.<br><br>Você pode tentar utilizar outro cartão ou selecionar outra forma de pagamento.<br>Em caso de dúvidas, entre em contato com nosso atendimento.';
$_['error_register']         = '<b>Atenção:</b><br>Os dados abaixo precisam ser preenchidos corretamente: <br><b>%s</b><br>Após corrigir os dados você poderá finalizar seu pedido novamente.<br>Em caso de dúvidas, entre em contato com nosso atendimento.';
$_['error_configuration']    = '<b>Atenção:</b><br>Estamos com problemas técnicos.<br>Tente novamente mais tarde ou selecione outra forma de pagamento.<br>Em caso de dúvidas, entre em contato com nosso atendimento.';
$_['error_try_new_card']     = '<b>Atenção:</b><br>Seu pagamento não foi aprovado. Por favor, utilize outro cartão.<br>Caso o problema persista, entre em contato com nosso atendimento.';
$_['error_try_limit']        = '<b>Atenção:</b><br>Você ultrapassou o limite de tentativas. Por favor, utilize outro cartão.<br>Caso o problema persista, entre em contato com nosso atendimento.';
$_['error_session_expire']   = '<b>Atenção:</b><br>O tempo para tentar efetuar o pagamento se esgotou.<br>Por favor, tente novamente.<br>Caso o problema persista, entre em contato com nosso atendimento.';
$_['error_unexpected']       = '<b>Atenção:</b><br>Ocorreu um erro inesperado.<br>Por favor, tente novamente.<br>Caso o problema persista, entre em contato com nosso atendimento.';
