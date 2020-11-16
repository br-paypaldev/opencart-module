# Checkout Transparente PayPal para OpenCart 2 e 3
![](https://raw.githubusercontent.com/wiki/paypal/PayPal-PHP-SDK/images/homepage.jpg)

A experiência de um Checkout Transparente processado com a segurança do PayPal. Seu cliente efetua o pagamento diretamente no seu site, sem redirecionamento e sem a necessidade de abertura de uma conta PayPal, utilizando os dados cartão de crédito, que podem ser salvos para agilizar o pagamento em futuras compras.


## Requisitos

Para o correto funcionamento das soluções, é necessário verificar se a sua loja e servidor suportam alguns recursos:
1. Para o checkout transparente (PayPal Plus), a sua loja precisa ter suporte aos campos CPF (tipo Conta) e Número (tipo Endereço), portanto antes de ativar a solução garanta que a sua loja está devidamente configurada para suportar estes campos;
2. O servidor precisa ter suporte à PHP 7.3 ou superior;
3. O servidor precisa ter suporte à TLS 1.2 ou superior e HTTPS 1.1 [(Referência Oficial)](https://www.paypal.com/sg/webapps/mpp/tls-http-upgrade).

## Checkout Transparente (PayPal Plus)

Diferente dos outros produtos, o PayPal Plus só está disponível para contas PayPal criadas com CNPJ (Conta Empresa) e a sua utilização funciona mediante aprovação comercial. Caso já tenha uma conta PayPal do tipo Empresa, você pode solicitar a utilização do PayPal Plus pelo email: comercial@paypal.com.

Caso a sua conta seja de pessoa física, você deve abrir uma conta PayPal de pessoa jurídica por este [link](https://www.paypal.com/bizsignup/).

***O Checkout Transparente só irá funcionar caso tenha sido aprovado pelo PayPal.**

## Compatibilidade

Este módulo é compatível com OpenCart versões 2.0.1.1 até 3.0.3.6. 

## Instalação

1. Faça o download do módulo compatível com a sua versão do OpenCart, depois acesse a administração da sua loja;
2. Vá ao menu **Extensões→Instalador**, clique no botão "**Upload**", localize o arquivo que você baixou, e aguarde a conclusão da instalação automática;
3. Vá ao menu **Extensões→Modificações** e clique no botão "**Atualizar**";
4. No OpenCart 3, vá ao menu **Painel de controle**, no lado direito da tela abaixo do botão "**Sair**", clique no botão na cor azul com o desenho de uma engrenagem branca nele, no modal clique nos dois botões na cor laranja que estão dentro da coluna "**Ação**" para atualizar o cache do tema;
5. Vá ao menu **Extensões→Pagamentos** (nas versões 2.3 ou superior vá ao menu **Extensões→Extensões** e filtre por **Pagamentos**), localize a extensão "**PayPal Plus**", clique no botão "**Instalar**", depois no botão "**Editar**", preencha os campos e clique no botão "**Salvar**".

## Configuração
### - Credenciais de API
Para configurar as soluções PayPal, você deverá gerar as credenciais de API do tipo REST, no caso o Client ID e o Secret ID.

Para obtê-las siga este passo-a-passo:
                
1. Efetuar o login com sua conta PayPal em https://developer.paypal.com e clique no link na parte superior "**Dashboard**";
2. Clique em "**My Apps & Credentials**";
3. Abaixo de "**Rest API apps**" clique em "**Create App**";
4. Em seguida, insira o termo "**ppplus**" no campo "**App Name**" e clique em "**Create App**";
5. No canto superior direito da tela, clique em "**Live**";
6. Você deve copiar os códigos que aparecerem em "**Client ID**" e em "**Secret**" (para visualizar o "Secret" será necessário clicar em "**Show**") e colar estes códigos na página de configuração da solução que irá utilizar. 

### - Solução

Para o Checkout Transparente funcionar, sua loja precisará ter cadastrado os campos CPF (tipo Conta) e Número (tipo Endereço).

Para cadastrá-los siga este passo-a-passo dentro do painel administrativo do seu OpenCart:

Vá ao menu **Clientes→Personalizar cadastro**, e clique no botão **Novo**.

Para cadastrar o **CPF**, preencha o formulário com as informações abaixo:

| Campo | Valor |
| -------- | ----- |
| Nome do campo | CPF |
| Localização | Conta |
| Tipo de campo | Texto em uma linha |
| Tipo de cliente | Marque os tipos de clientes que verão o campo durante o cadastro |
| Tipos obrigatórios | Marque os tipos de clientes que terão o campo como preenchimento obrigatório |
| Situação | Habilitado |

Após preencher o formulário, clique no botão **Salvar**.

Para cadastrar o **Número**, clique novamente no botão **Novo** e preencha o formulário com as informações abaixo:

| Campo | Valor |
| -------- | ----- |
| Nome do campo | Número |
| Localização | Endereço |
| Tipo de campo | Texto em uma linha |
| Tipo de cliente | Marque os tipos de clientes que verão o durante o cadastro |
| Tipos obrigatórios | Marque os tipos de clientes que terão o campo como preenchimento obrigatório |
| Situação | Habilitado |

Após preencher o formulário, clique no botão **Salvar**.

## Dúvidas/Suporte

Caso a sua dúvida não tenha sido respondida aqui, entre em contato com o PayPal pelo número 0800 047 4482.

E caso necessite de algum suporte técnico e/ou acredita ter encontrado algum problema com este módulo acesse o nosso [portal de suporte técnico](https://www.paypal-support.com/s/?language=pt_BR) e abra um ticket detalhando o seu problema na seção "Fale Conosco".

## Changelog

Para visulizar as últimas atualizações acesse o [**CHANGELOG.md**](CHANGELOG.md).
