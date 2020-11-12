<?php
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} else {
?>
<style>
  #ppplus { height: 440px !important; min-height: 440px !important; }
  #ppplus iframe { width: 100% !important; height: 450px !important; min-height: 450px !important; }
  #update_checkout { cursor: pointer; }
  @media only screen and (device-width: 320px) and (orientation : portrait) {
    #ppplus { height: 590px !important; min-height: 590px !important; }
    #ppplus iframe { height: 600px !important; min-height: 600px !important; }
  }
  @media only screen and (device-width: 360px) and (orientation : portrait) {
    #ppplus { height: 560px !important; min-height: 560px !important; }
    #ppplus iframe { height: 570px !important; min-height: 570px !important; }
  }
  @media only screen and (min-device-width: 375px) and (orientation : portrait) {
    #ppplus { height: 550px !important; min-height: 550px !important; }
    #ppplus iframe { height: 560px !important; min-height: 560px !important; }
  }
  @media only screen and (min-device-width: 414px) and (orientation : portrait) {
    #ppplus { height: 530px !important; min-height: 530px !important }
    #ppplus iframe { height: 540px !important; min-height: 540px !important }
  }
  @media only screen and (max-width: 767px) {
    #payment .pull-right, #payment input[type="submit"] { width: 100% !important; white-space: nowrap !important; text-overflow: ellipsis !important; overflow: hidden !important; }
  }
</style>
<?php if ($mode == 'sandbox') { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?php echo $text_sandbox; ?></div>
<?php } ?>
<?php echo $information; ?>
<div id="ppplus">
</div>
<script type="application/javascript">
  var ppp = PAYPAL.apps.PPP({
    "useraction": "continue",
    "approvalUrl": "<?php echo $approval_url; ?>",
    "placeholder": "ppplus",
    "mode": "<?php echo $mode; ?>",
    "payerFirstName": "<?php echo $payerFirstName; ?>",
    "payerLastName": "<?php echo $payerLastName; ?>",
    "payerEmail": "<?php echo $payerEmail; ?>",
    "payerTaxId": "<?php echo $payerTaxId; ?>",
    "payerTaxIdType": "BR_CPF",
    "payerPhone": "<?php echo $payerPhone; ?>",
    "language": "pt_BR",
    "country": "BR",
    "disallowRememberedCards": <?php echo $disallowRememberedCards; ?>,
    "rememberedCards": "<?php echo $card_id; ?>",
    "merchantInstallmentSelectionOptional": true
  });
</script>
<div class="buttons" id="payment">
  <input type="hidden" id="pp-payment-id" name="pp-payment-id" value="<?php echo $payment_id; ?>">
  <input type="hidden" id="pp-rememberedcards" name="pp-rememberedcards" value="">
  <input type="hidden" id="pp-payer-id" name="pp-payer-id" value="">
  <input type="hidden" id="pp-installments" name="pp-installments" value="">
  <div class="pull-right">
    <input type="submit" id="button-confirm" class="btn btn-<?php echo $button_style; ?> btn-lg" value="<?php echo $button_text; ?>" onclick="ppp.doContinue(); return false;" />
  </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.7/loadingoverlay.min.js" integrity="sha512-hktawXAt9BdIaDoaO9DlLp6LYhbHMi5A36LcXQeHgVKUH6kJMOQsAtIw2kmQ9RERDpnSTlafajo6USh9JUXckw==" crossorigin="anonymous"></script>
<script type="text/javascript"><!--
  $('#button-confirm').hide();
  setTimeout(function() { $('#button-confirm').show(); }, 3000);

  var confirmed = false;
  var iframe_height = $('#ppplus iframe').height() + 70;

  if (window.addEventListener) {
    window.addEventListener("message", messageListener, false);
  } else if (window.attachEvent) {
    window.attachEvent("onmessage", messageListener);
  } else {
    throw new Error("Não é possível anexar o ouvinte de mensagens!");
  }

  function messageListener(event) {
    try {
      resize_iframe();

      var data = JSON.parse(event.data);

      action = data.action.replace (/['"]+/g,"");

      switch (action) {
        case "checkout":
          var rememberedCard = null;
          var payerID = null;
          var payment_approved = false;
          var installmentsValue = 1;

          rememberedCard = data.result.rememberedCards;
          payerID = data.result.payer.payer_info.payer_id;
          payment_approved = data.result.payment_approved;

          if (data.result.term == 'undefined') {
            installmentsValue = data.result.term.term;
          }

          if (payment_approved === true) {
            $('#pp-rememberedcards').val(rememberedCard);
            $('#pp-payer-id').val(payerID);
            $('#pp-installments').val(installmentsValue);

            execute();
          }
          break;
        case "disableContinueButton":
          $('#button-confirm').prop('disabled', true);
          break;
        case "disableContinueButton":
          $('#button-confirm').prop('disabled', false);
          break;
        case "onError":
          var ppplusError = data.cause.replace (/['"]+/g,"");

          switch (ppplusError) {
            case "INTERNAL_SERVICE_ERROR":
            case "SOCKET_HANG_UP":
            case "socket hang up":
            case "connect ECONNREFUSED":
            case "connect ETIMEDOUT":
            case "UNKNOWN_INTERNAL_ERROR":
            case "fiWalletLifecycle_unknown_error":
            case "Failed to decrypt term info":
            case "RESOURCE_NOT_FOUND":
            case "INTERNAL_SERVER_ERROR":
              $('#ppplus').html(
                '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_unexpected; ?></div>' +
                '<div class="center-block"><button onclick="window.location.reload();" class="btn btn-primary"> <?php echo $button_try_again; ?> </button></div>'
              );

              $('#button-confirm').hide();
              break;
            case "RISK_N_DECLINE":
            case "NO_VALID_FUNDING_SOURCE_OR_RISK_REFUSED":
            case "TRY_ANOTHER_CARD":
            case "NO_VALID_FUNDING_INSTRUMENT":
              $('#ppplus').html(
                '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_try_new_card; ?></div>' +
                '<div class="center-block"><button onclick="window.location.reload();" class="btn btn-primary"> <?php echo $button_try_again; ?> </button></div>'
              );

              $('#button-confirm').hide();
              break;
            case "CARD_ATTEMPT_INVALID":
              $('#ppplus').html(
                '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_try_limit; ?></div>' +
                '<div class="center-block"><button onclick="window.location.reload();" class="btn btn-primary"> <?php echo $button_try_again; ?> </button></div>'
              );

              $('#button-confirm').hide();
              break;
            case "INVALID_OR_EXPIRED_TOKEN":
              $('#ppplus').html(
                '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_session_expire; ?></div>' +
                '<div class="center-block"><button onclick="window.location.reload();" class="btn btn-primary"> <?php echo $button_try_again; ?> </button></div>'
              );

              $('#button-confirm').hide();
              break;
            case "CHECK_ENTRY":
              resize_iframe();

              $('#button-confirm').prop('disabled', false);
              break;
            default:
              $('#ppplus').html(
                '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_unexpected; ?></div>' +
                '<div class="center-block"><button onclick="window.location.reload();" class="btn btn-primary"> <?php echo $button_try_again; ?> </button></div>'
              );

              $('#button-confirm').hide();
          }
          break;
        default:
        //
      }

      if (data.result === 'error') {
        resize_iframe();

        $('#button-confirm').prop('disabled', false);
      }
    } catch (error) {
      //
    }
  }

  function resize_iframe() {
    $('#ppplus, #ppplus iframe').css('height', iframe_height + 'px').css('min-height', iframe_height + 'px').css('max-height', iframe_height + 'px');
  }

  function execute() {
    if (confirmed == false) {
      confirmed = true;

      $('#danger').remove();

      $.ajax({
        url: 'index.php?route=extension/payment/paypal_plus/execute',
        type: 'post',
        data: $('#payment input[type="hidden"]'),
        dataType: 'json',
        beforeSend: function() {
          $.LoadingOverlay("show");
        },
        complete: function() {
          $.LoadingOverlay("hide");
        },
        success: function(json) {
          $.LoadingOverlay("hide");

          if (json['redirect']) {
            $('#button-confirm').hide();

            $('#ppplus').html('<div class="alert alert-success" role="alert"><?php echo $text_redirect; ?></div>');

            location.href = json['redirect'];
          } else if (json['error']) {
            $('#ppplus').html(
              '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>' +
              '<div class="center-block"><button onclick="window.location.reload();" class="btn btn-primary"> <?php echo $button_try_again; ?> </button></div>'
            );

            $('#button-confirm').hide();
          } else {
            search();
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          if (jqXHR.status == 404 || jqXHR.status == 500 || errorThrown == 'Not Found') {
            search();
          }
        }
      });
    }
  }

  function search() {
    $.ajax({
      url: 'index.php?route=extension/payment/paypal_plus/search',
      type: 'post',
      data: $('#payment input[type="hidden"]'),
      dataType: 'json',
      beforeSend: function() {
        $.LoadingOverlay("show");
      },
      complete: function() {
        $.LoadingOverlay("hide");
      },
      success: function(json) {
        $.LoadingOverlay("hide");

        if (json['redirect']) {
          $('#button-confirm').hide();

          $('#ppplus').html('<div class="alert alert-success" role="alert"><?php echo $text_redirect; ?></div>');

          location.href = json['redirect'];
        } else {
          $('#payment').before('<div class="alert alert-danger" id="danger"><?php echo $error_approval; ?></div>');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status == 404 || jqXHR.status == 500 || errorThrown == 'Not Found') {
          $('#payment').before('<div class="alert alert-danger" id="danger"><?php echo $error_approval; ?></div>');
        }
      }
    });
  }
//--></script>
<?php } ?>
