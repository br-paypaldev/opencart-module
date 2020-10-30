<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($sandbox) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?php echo $text_sandbox; ?></div>
    <?php } ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_filter; ?></div>
    <div id="progress"></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $entry_initial_date; ?></label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="filter_initial_date" value="<?php echo $filter_initial_date; ?>" class="form-control date" readonly />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $entry_final_date; ?></label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="filter_final_date" value="<?php echo $filter_final_date; ?>" class="form-control date" readonly />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $entry_status; ?></label>
                <select name="filter_status" class="form-control">
                  <?php foreach ($statuses as $key => $value) { ?>
                  <?php if ($key == $filter_status) { ?>
                  <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table id="paypal-plus" style="min-width:100% !important;" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="min-width: 80px !important;" class="text-center"><?php echo $column_order_id; ?></th>
                <th style="min-width: 120px !important;" class="text-center"><?php echo $column_date_added; ?></th>
                <th style="min-width: 200px !important;" class="text-left"><?php echo $column_customer; ?></th>
                <th style="min-width: 200px !important;" class="text-center"><?php echo $column_payment_id; ?></th>
                <th style="min-width: 80px !important;" class="text-center"><?php echo $column_total; ?></th>
                <th style="min-width: 120px !important;" class="text-center"><?php echo $column_status; ?></th>
                <th style="min-width: 120px !important;" class="text-center"><?php echo $column_action; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if ($transactions) { ?>
              <?php foreach ($transactions as $transaction) { ?>
              <tr>
                <td class="text-center"><a href="<?php echo $transaction['view_order']; ?>"><?php echo $transaction['order_id']; ?></a></td>
                <td class="text-center"><?php echo $transaction['date_added']; ?></td>
                <td class="text-left"><?php echo $transaction['customer']; ?></td>
                <td class="text-center"><?php echo $transaction['payment_id']; ?></td>
                <td class="text-center"><?php echo $transaction['total_value']; ?></td>
                <td class="text-center" id="text-status-<?php echo $transaction['id']; ?>"><?php echo $transaction['status']; ?></td>
                <td class="text-center">
                  <?php foreach ($transaction['action'] as $action) { ?>
                  <button type="button" data-toggle="tooltip" title="<?php echo $action['title']; ?>" class="<?php echo $action['class']; ?>" name="<?php echo $action['name']; ?>" id="<?php echo $action['id']; ?>"><i class="<?php echo $action['icon']; ?>"></i></button>
                  <?php } ?>
                </td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="refund" tabindex="-1" role="dialog" aria-labelledby="refund" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?php echo $modal_title_refund; ?></h4>
        </div>
        <form id="form_refund" method="post">
          <div class="modal-body">
            <div class="progress-modal"></div>
            <p><?php echo $modal_invoice_number; ?> <strong class="text-invoice"></strong></p>
            <p><?php echo $modal_approved; ?> <strong class="text-approved"></strong></p>
            <p><?php echo $modal_installments; ?> <strong class="text-installments"></strong></p>
            <p><?php echo $modal_refunded; ?> <strong class="text-refunded"></strong></p>
            <p><?php echo $modal_status; ?> <strong class="text-status"></strong></p>
            <input type="hidden" name="paypal_plus_id" id="paypal_plus_id">
            <input type="hidden" name="total_value" id="total_value">
            <div class="form-group">
              <p><?php echo $modal_solution; ?></p>
              <label class="control-label" for="refund_value"><?php echo $modal_refund; ?></label>
              <input type="tel" value="" id="refund_value" required maxlength="12" name="refund_value" placeholder="<?php echo $modal_refund; ?>" class="form-control value" autocomplete="off">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $modal_close; ?></button>
            <button type="submit" class="btn btn-primary" id="btnSubmit"><?php echo $modal_send; ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?php echo $modal_title_details; ?></h4>
        </div>
        <div class="modal-body">
          <div class="progress-modal"></div>
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?php echo $tab_details; ?></a></li>
            <li><a href="#tab-last-json" data-toggle="tab"><?php echo $tab_last_json; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-details">
              <p><?php echo $modal_payer_name; ?> <strong class="text-client"></strong></p>
              <p><?php echo $modal_payer_email; ?> <strong class="text-email"></strong></p>
              <p><?php echo $modal_invoice_number; ?> <strong class="text-invoice"></strong></p>
              <p><?php echo $modal_approved; ?> <strong class="text-approved"></strong></p>
              <p><?php echo $modal_installments; ?> <strong class="text-installments"></strong></p>
              <p><?php echo $modal_refunded; ?> <strong class="text-refunded"></strong></p>
              <p><?php echo $modal_status; ?> <strong class="text-status"></strong></p>
            </div>
            <div class="tab-pane" id="tab-last-json">
              <pre><code id="last-json"></code></pre>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $modal_close; ?></button>
          <button type="submit" class="btn btn-primary" value="" name="button-search"><?php echo $button_search; ?></button>
        </div>
      </div>
    </div>
  </div>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
  <script type="text/javascript"><!--
    $('.date').datetimepicker({
      language: 'pt-br',
      pickTime: false,
      format: 'YYYY-MM-DD'
    });

    $(document).ready(function() {
      $('#paypal-plus').DataTable({
        "order": [],
        "columnDefs": [ {"targets": 6, "searchable": false, "orderable": false} ],
        "deferRender": true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
        },
        "dom": 'Bfrtip',
        "buttons": [
          {
            text: '<?php echo $button_print; ?>',
            extend: 'print',
            autoPrint: false,
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            text: '<?php echo $button_copy; ?>',
            extend: 'copyHtml5',
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            text: '<?php echo $button_csv; ?>',
            extend: 'csvHtml5',
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            text: '<?php echo $button_excel; ?>',
            extend: 'excelHtml5',
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            text: '<?php echo $button_pdf; ?>',
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            text: '<?php echo $button_columns; ?>',
            extend: 'colvis'
          }
        ]
      });
    });

    $('button[name="button-info"]').click(function() {
      $('.progress-modal').html('');
      getDataTransaction($(this).attr('id'));
      $('#details').modal('show');
      $('#details ul.nav.nav-tabs a:first').tab('show');
    });

    $('button[name="button-search"]').click(function() {
      $.ajax({
        url: 'index.php?route=extension/paypal_plus/transaction/search&token=<?php echo $token; ?>&paypal_plus_id=' + $('#paypal_plus_id').val(),
        dataType: 'json',
        beforeSend: function() {
          $(this).prop('disabled', true);
          $('.progress-modal').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_consulting; ?></div>');
        },
        complete: function() {
          $(this).prop('disabled', false);
        },
        success: function(json) {
          if (json['error']) {
            $('.progress-modal').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
          } else {
            $('.alert alert-danger').remove();
            getDataTransaction($('#paypal_plus_id').val());
            $('.progress-modal').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
          }
        }
      });
    });

    $('button[name="button-refund"]').click(function() {
      $('.progress-modal').html('');
      getDataTransaction($(this).attr('id'));
      $('#refund').modal('show');
    });

    $('#form_refund').on('submit', function(event) {
      event.preventDefault();

      if (
        validateValue($('#refund_value').val())
      ) {
        const paypal_plus_id = $('#paypal_plus_id').val();
        const formRefund = $(this).serialize();

        bootbox.confirm({
          message: "<?php echo $text_confirm_refund; ?>",
          buttons: {
            confirm: { label: '<i class="fa fa-check"></i> <?php echo $text_confirm_yes; ?>', className: 'btn-success' },
            cancel: { label: '<?php echo $text_no; ?>', className: 'btn-danger' }
          },
          callback: function (result) {
            if (result === true) {
              $.ajax({
                url: 'index.php?route=extension/paypal_plus/transaction/refund&token=<?php echo $token; ?>&paypal_plus_id=' + paypal_plus_id,
                dataType: 'json',
                method: 'post',
                data: formRefund,
                beforeSend: function() {
                  $('input').prop('disabled', true);
                  $('button').prop('disabled', true);
                  $('.progress-modal').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_request_refund; ?></div>');
                },
                complete: function() {
                  $('input').prop('disabled', false);
                  $('button').prop('disabled', false);
                },
                success: function(json) {
                  if (json['error']) {
                    $('.progress-modal').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                  } else {
                    $('.alert alert-danger').remove();
                    $('.progress-modal').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    getDataTransaction(paypal_plus_id);
                  }
                }
              });
            }
          }
        });
      }
    });

    function getDataTransaction(id) {
      $.get('index.php?route=extension/paypal_plus/transaction/dataTransaction&token=<?php echo $token; ?>&paypal_plus_id=' + id, function(data) {
        $('.text-client').text(data.payer_name);
        $('.text-email').text(data.payer_email);
        $('.text-invoice').text(data.invoice_number);
        $('.text-approved').text(data.total_value_txt);
        $('.text-refunded').text(data.refunded_value_txt);
        $('.text-installments').text(data.installments + 'x');
        $('.text-status').text(data.status);
        $('#text-status-' + data.paypal_plus_id).text(data.status);
        $('#total_value').val(data.total_value);
        $('#refund_value').val('0.00');
        $('#paypal_plus_id').val(data.paypal_plus_id);
        $('#last-json').text(data.json);
      });
    }

    $('button[name="button-delete"]').click(function() {
      const id = $(this).attr('id');

      bootbox.confirm({
        message: "<?php echo $text_confirm_delete; ?>",
        buttons: {
          confirm: { label: '<i class="fa fa-check"></i> <?php echo $text_confirm_yes; ?>', className: 'btn-success' },
          cancel: { label: '<?php echo $text_no; ?>', className: 'btn-danger' }
        },
        callback: function (result) {
          if (result === true) {
            $.ajax({
              url: 'index.php?route=extension/paypal_plus/transaction/delete&token=<?php echo $token; ?>&paypal_plus_id=' + id,
              dataType: 'json',
              beforeSend: function() {
                $(this).prop('disabled', true);
                $('#progress').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_excluding; ?></div>');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
              },
              complete: function() {
                $(this).prop('disabled', false);
              },
              success: function(json) {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                if (json['error']) {
                  $('#progress').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                } else {
                  $('.alert alert-danger').remove();
                  $('#progress').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                  location.href = location.href;
                }
              }
            });
          }
        }
      });
    });

    function validateValue(refund) {
      $('.progress-modal').html('');

      let error_refund = false;

      if (parseFloat(refund) == 0) {
        error_refund = '<?php echo $error_value_zero; ?>';
      }

      if (error_refund) {
        $('.progress-modal').html('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + error_refund + '</div>');

        return false;
      }

      return true;
    }

    $('#refund_value').keyup(function() {
      let refund = $(this).val().replace(/[\D]+/g, '');

      if (refund.length < 3) {
        refund = ('000' + refund).slice(-3);
      }

      refund = refund.replace(/([0-9]{2})$/g, ".$1");

      $('#refund_value').val(Number(refund).toFixed(2));
    });

    $('#button-filter').on('click', function() {
      let filter_url = 'index.php?route=extension/paypal_plus/transaction&token=<?php echo $token; ?>';

      const filter_initial_date = $('input[name="filter_initial_date"]').val();
      const filter_final_date = $('input[name="filter_final_date"]').val();
      const filter_status = $('select[name="filter_status"]').val();

      if (filter_initial_date) {
        filter_url += `&filter_initial_date=${filter_initial_date}`;
      }

      if (filter_final_date) {
        filter_url += `&filter_final_date=${filter_final_date}`;
      }

      if (filter_status) {
        filter_url += `&filter_status=${filter_status}`;
      }

      location.href = filter_url.replace(/&amp;/g, '&');
    });
  //--></script>
  <style>
    pre {
      max-height: 350px;
    }
    code {
      white-space: inherit !important;
    }
  </style>
</div>
<?php echo $footer; ?>