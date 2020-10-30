<?php
// Heading
$_['heading_title']                = 'PayPal Plus';

// Text
$_['text_extension']               = 'Extensões';
$_['text_success']                 = 'Pagamento por PayPal Plus modificado com sucesso!';
$_['text_edit']                    = 'Configurações do pagamento por PayPal Plus';
$_['text_paypal_plus']             = '<a target="_blank" href="https://www.paypal.com/br/webapps/mpp/paypal-payments-pro"><img src="view/image/payment/paypal_plus.jpg" alt="PayPal Plus" title="PayPal Plus" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_requirements']            = 'Requisitos obrigatórios:';
$_['text_prefix']                  = 'Prefixo no código pedido';
$_['text_field']                   = 'Campo:';
$_['text_column']                  = 'Coluna na tabela de pedidos';
$_['text_document']                = 'Coluna CPF:';
$_['text_number_payment']          = 'Coluna Número para fatura:';
$_['text_number_shipping']         = 'Coluna Número para entrega:';
$_['text_complement_payment']      = 'Coluna Complemento para fatura:';
$_['text_complement_shipping']     = 'Coluna Complemento para entrega:';
$_['text_btn_default']             = 'Default';
$_['text_btn_primary']             = 'Primary';
$_['text_btn_success']             = 'Success';
$_['text_btn_info']                = 'Info';
$_['text_btn_warning']             = 'Warning';
$_['text_btn_danger']              = 'Danger';
$_['text_webhook_loading']         = 'Carregando os dados do Webhook...';
$_['text_webhook_add']             = 'Carregando os eventos para habilitar o Webhook...';
$_['text_webhook_saving']          = 'Salvando os eventos do Webhook...';
$_['text_webhook_deleting']        = 'Deletando o Webhook...';
$_['text_webhook_new']             = 'Habilitar Webhook';
$_['text_webhook_save']            = 'Salvar Webhook';
$_['text_confirm_yes']             = 'Sim, desejo confirmar';
$_['text_confirm_delete']          = '<h4>Você tem certeza que deseja excluir o Webhook?<h4><p><b>Atenção:</b> Se confirmada, esta ação não poderá ser desfeita.</p>';
$_['text_webhook_deleted']         = 'Webhook deletado com sucesso!';
$_['text_webhook_created']         = 'Webhook habilitado com sucesso!';
$_['text_dispute_created']         = 'Quando o cliente abre uma disputa.';
$_['text_sale_completed']          = 'Quando o pagamento é concluído.';
$_['text_sale_denied']             = 'Quando o pagamento é negado.';
$_['text_sale_pending']            = 'Quando o pagamento está pendente.';
$_['text_sale_refunded']           = 'Quando o pagamento é reembolsado.';
$_['text_sale_reversed']           = 'Quando o pagamento é revertido.';

// Info
$_['info_general']                 = 'Preencha as configurações básicas da extensão.';
$_['info_store']                   = 'Selecione as lojas em que a extensão será uma opção de pagamento.';
$_['info_api']                     = 'Preencha as configurações para integração com o PayPal Plus.<br>
                                      <strong>Importante:</strong> Caso não possua credenciais para o PayPal Plus, entre em contato com o atendimento para empresas do PayPal.';
$_['info_order_status']            = 'Selecione as situações que serão atribuídas automaticamente ao pedido conforme o retorno do PayPal.';
$_['info_custom_field']            = 'Configurações para indetificar as informações extras no cadastro do cliente que devem ser enviadas para o PayPal.<br>
                                      <strong>Importante:</strong> Para cadastrar campos personalizados, vá ao menu <strong>Clientes > Personalizar cadastro</strong> e cadastre os campos extras como CPF e número do endereço.<br>
                                      <strong>Observação:</strong> Se os campos extras foram criados diretamente na tabela de pedidos, selecione a opção "<strong>Coluna na tabela de pedidos</strong>", e selecione a coluna na tabela <i>*_order</i> correspondente.';
$_['info_checkout']                = 'As informações abaixo serão utilizadas na finalização do pedido.';
$_['info_webhook']                 = 'Para receber notificações do PayPal sobre mudanças na situação dos pagamentos você deve habilitar e salvar o Webhook.';
$_['info_webhook_credencials']     = 'Para receber notificações do PayPal através do Webhook é necessário primeiro preencher as credenciais na aba <strong>API</strong> e clicar no botão <strong>Salvar</strong>.';

// Tab
$_['tab_general']                  = 'Configurações';
$_['tab_store']                    = 'Lojas';
$_['tab_api']                      = 'API';
$_['tab_order_status']             = 'Situações';
$_['tab_custom_field']             = 'Dados do cliente';
$_['tab_checkout']                 = 'Finalização';
$_['tab_webhook']                  = 'Webhook';

// Columns
$_['column_name']                  = 'Evento';
$_['column_description']           = 'Descrição';
$_['column_status']                = 'Status';
$_['column_webhook_id']            = 'ID do Webhook';
$_['column_webhook_url']           = 'URL do Webhook';
$_['column_webhook_actions']       = 'Ações';

// Entry
$_['entry_customer_groups']        = 'Tipos de clientes:';
$_['entry_total']                  = 'Total mínimo:';
$_['entry_geo_zone']               = 'Região geográfica:';
$_['entry_status']                 = 'Situação:';
$_['entry_sort_order']             = 'Posição:';
$_['entry_stores']                 = 'Lojas:';
$_['entry_store']                  = 'Loja:';
$_['entry_client_id']              = 'Client ID:';
$_['entry_client_secret']          = 'Client Secret:';
$_['entry_sandbox']                = 'Sandbox:';
$_['entry_debug']                  = 'Debug:';
$_['entry_description']            = 'Descrição:';
$_['entry_order_status_pending']   = 'Transação pendente:';
$_['entry_order_status_denied']    = 'Transação não autorizada:';
$_['entry_order_status_analyze']   = 'Transação em análise:';
$_['entry_order_status_completed'] = 'Transação completa:';
$_['entry_order_status_refunded']  = 'Transação reembolsada:';
$_['entry_order_status_dispute']   = 'Transação em disputa:';
$_['entry_order_status_reversed']  = 'Transação revertida:';
$_['entry_custom_document_id']     = 'CPF:';
$_['entry_custom_number_id']       = 'Número:';
$_['entry_custom_complement_id']   = 'Complemento:';
$_['entry_extension_title']        = 'Título da extensão:';
$_['entry_information']            = 'Instruções:';
$_['entry_button_style']           = 'Estilo do botão confirmar:';
$_['entry_button_text']            = 'Texto do botão confirmar:';

// Help
$_['help_customer_groups']         = 'Tipos de clientes que poderão selecionar a extensão no checkout.';
$_['help_total']                   = 'Valor mínimo que o pedido deve alcançar para que a extensão seja habilitada. Deixe em branco se não houver valor mínimo.';
$_['help_stores']                  = 'Lojas em que a extensão será exibida no checkout.';
$_['help_prefix']                  = 'Só preencha caso utilize a extensão em várias lojas em que o código do pedido possa repetir, ou caso queira distinguir os pedidos de cada loja.';
$_['help_client_id']               = 'É gerada com orientação do PayPal.';
$_['help_client_secret']           = 'É gerada com orientação do PayPal.';
$_['help_sandbox']                 = 'Se Sim, ativa o modo de teste.';
$_['help_debug']                   = 'Se Sim, armazena no log as informações recebidas através da API do PayPal Plus.';
$_['help_description']             = 'Descrição do pagamento para o PayPal.';
$_['help_order_status_pending']    = 'Quando for iniciada.';
$_['help_order_status_denied']     = 'Quando for negada.';
$_['help_order_status_analyze']    = 'Em análise pelo PayPal.';
$_['help_order_status_completed']  = 'Quando estiver concluída no PayPal.';
$_['help_order_status_refunded']   = 'Quando for reembolsada.';
$_['help_order_status_dispute']    = 'Quando o cliente abre uma disputa.';
$_['help_order_status_reversed']   = 'Quando a disputa é revertida.';
$_['help_custom_document_id']      = 'Selecione o campo que armazena o CPF no cadastro do cliente.';
$_['help_custom_number_id']        = 'Selecione o campo que armazena o número no endereço do cliente.';
$_['help_custom_complement_id']    = 'Só selecione se você tiver o campo para preencher o complemento no endereço do cliente.';
$_['help_extension_title']         = 'Será exibido no checkout na seleção da forma de pagamento.';
$_['help_information']             = 'Será exibida no checkout antes do formulário para preenchimento dos dados do cartão.';
$_['help_button_style']            = 'É baseado no padrão Bootstrap.';
$_['help_button_text']             = 'Será exibido dentro do botão.';

// Error
$_['error_permission']             = 'Atenção: Você não tem permissão para modificar a extensão PayPal Plus!';
$_['error_warning']                = '<b>Atenção:</b> A extensão não foi configurada corretamente! Verifique todos os campos para corrigir os erros.';
$_['error_requirements']           = '<b>Atenção:</b> Os requisitos obrigatórios precisam ser atendidos.';
$_['error_stores']                 = 'É necessário selecionar pelo menos uma loja.';
$_['error_customer_groups']        = 'É necessário selecionar pelo menos um tipo de cliente.';
$_['error_field_column']           = 'Selecione a coluna.';
$_['error_required']               = 'Preenchimento obrigatório.';
$_['error_prefix']                 = 'Preencha o prefixo desta loja.';
$_['error_webhook_event_types']    = 'Não foi possível listar os eventos para o Webhook. Verifique se você preencheu as credenciais corretamente e tente novamente.';
$_['error_webhook_indisponible']   = 'Não foi possível habilitar o Webhook. Verifique se você preencheu as credenciais corretamente e tente novamente.';
$_['error_webhook_remove']         = 'Não foi possível remover o Webhook. Verifique se você preencheu as credenciais corretamente e tente novamente.';
$_['error_webhook_empty']          = 'Selecione pelo menos um evento.';
$_['error_webhook_localhost']      = 'Não é possível salvar o Webhook a partir de um servidor local.';
