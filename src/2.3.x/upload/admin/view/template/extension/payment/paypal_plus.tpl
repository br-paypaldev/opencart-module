<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-paypal-plus" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1> <span class="badge"><?php echo $version; ?></span>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($requirements) { ?>
    <div class="alert alert-warning">
      <h4><b><?php echo $text_requirements; ?></b></h4>
      <?php foreach ($requirements as $requirement) { ?>
      <p><i class="fa fa-exclamation-triangle"></i> <?php echo $requirement; ?></p>
      <?php } ?>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paypal-plus" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
            <li><a href="#tab-api" data-toggle="tab"><?php echo $tab_api; ?></a></li>
            <li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
            <li><a href="#tab-custom-field" data-toggle="tab"><?php echo $tab_custom_field; ?></a></li>
            <li><a href="#tab-checkout" data-toggle="tab"><?php echo $tab_checkout; ?></a></li>
            <li><a href="#tab-webhook" data-toggle="tab"><?php echo $tab_webhook; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_general; ?></div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><span class="text-danger">*</span> <strong><?php echo $entry_customer_groups; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_customer_groups; ?></span>
                </div>
                <div class="col-sm-3">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($customer_groups_data as $customer_group) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($customer_group['customer_group_id'], $customer_groups)) { ?>
                        <input type="checkbox" name="customer_groups[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                        <?php echo $customer_group['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="customer_groups[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                        <?php echo $customer_group['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                  <?php if ($error_customer_groups) { ?>
                  <div class="text-danger"><?php echo $error_customer_groups; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_total; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_total; ?></span>
                </div>
                <div class="col-sm-3">
                  <div class="input-group">
                    <input type="text" name="total" value="<?php echo $total; ?>" placeholder="" class="form-control" maxlength="6" />
                    <div class="input-group-addon"><i class="fa fa-money"></i></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_geo_zone; ?></strong></h5>
                </div>
                <div class="col-sm-3">
                  <select name="geo_zone_id" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones_data as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_status; ?></strong></h5>
                </div>
                <div class="col-sm-3">
                  <select name="status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_sort_order; ?></strong></h5>
                </div>
                <div class="col-sm-3">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="" class="form-control" maxlength="4" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-store">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_store; ?></div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><span class="text-danger">*</span> <strong><?php echo $entry_stores; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_stores; ?></span>
                </div>
                <div class="col-sm-3">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $stores)) { ?>
                        <input type="checkbox" name="stores[]" value="0" checked="checked" />
                        <?php echo $default_store; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="stores[]" value="0" />
                        <?php echo $default_store; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores_data as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $stores)) { ?>
                        <input type="checkbox" name="stores[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="stores[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                  <?php if ($error_stores) { ?>
                  <div class="text-danger"><?php echo $error_stores; ?></div>
                  <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><strong><?php echo $text_prefix; ?></strong></legend>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left col-sm-3"><?php echo $entry_store; ?></td>
                        <td class="text-left col-sm-9"><span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_prefix; ?></span></td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">
                          <span class="text-danger" id="required-prefix-0"><?php if (in_array(0, $stores)) { ?> * <?php } ?></span> <?php echo $default_store; ?> <?php echo $text_default; ?>
                        </td>
                        <td class="text-left">
                          <input type="text" name="prefix[0]" value="<?php echo $new_prefix[0]; ?>" placeholder="" id="store-prefix-0" class="form-control" maxlength="8" />
                          <?php if (isset($error_prefix[0])) { ?>
                          <div class="text-danger"><?php echo $error_prefix[0]; ?></div>
                          <?php } ?>
                        </td>
                      </tr>
                      <?php foreach ($stores_data as $store) { ?>
                      <tr>
                        <td class="text-left"><span class="text-danger" id="required-prefix-<?php echo $store['store_id']; ?>"><?php if (in_array($store['store_id'], $stores)) { ?> * <?php } ?></span> <?php echo $store['name']; ?></td>
                        <td class="text-left">
                          <input type="text" name="prefix[<?php echo $store['store_id']; ?>]" value="<?php echo $new_prefix[$store['store_id']]; ?>" placeholder="" <?php if (in_array($store['store_id'], $stores)) { ?>required <?php } ?> id="store-prefix-<?php echo $store['store_id']; ?>" class="form-control" maxlength="8" />
                          <?php if (isset($error_prefix[$store['store_id']])) { ?>
                          <div class="text-danger"><?php echo $error_prefix[$store['store_id']]; ?></div>
                          <?php } ?>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-api">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_api; ?></div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><span class="text-danger">*</span> <strong><?php echo $entry_client_id; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_client_id; ?></span>
                </div>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-plug"></i></span>
                    <input type="text" name="client_id" value="<?php echo $client_id; ?>" placeholder="" class="form-control" maxlength="200" />
                  </div>
                  <?php if ($error_client_id) { ?>
                  <div class="text-danger"><?php echo $error_client_id; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><span class="text-danger">*</span> <strong><?php echo $entry_client_secret; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_client_secret; ?></span>
                </div>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-plug"></i></span>
                    <input type="text" name="client_secret" value="<?php echo $client_secret; ?>" placeholder="" class="form-control" maxlength="200" />
                  </div>
                  <?php if ($error_client_secret) { ?>
                  <div class="text-danger"><?php echo $error_client_secret; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_sandbox; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_sandbox; ?></span>
                </div>
                <div class="col-sm-8">
                  <select name="sandbox" class="form-control">
                    <?php if ($sandbox) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_debug; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_debug; ?></span>
                </div>
                <div class="col-sm-8">
                  <select name="debug" class="form-control">
                    <?php if ($debug) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_description; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_description; ?></span>
                </div>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                    <input type="text" name="description" value="<?php echo $description; ?>" placeholder="" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-order-status">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_order_status; ?></div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_pending; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_pending; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_pending_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_pending_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_denied; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_denied; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_denied_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_denied_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_analyze; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_analyze; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_analyze_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_analyze_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_completed; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_completed; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_completed_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_completed_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_refunded; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_refunded; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_refunded_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_refunded_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_dispute; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_dispute; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_dispute_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_dispute_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_order_status_reversed; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_order_status_reversed; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="order_status_reversed_id" class="form-control">
                    <?php foreach ($order_statuses_data as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_reversed_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-custom-field">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_custom_field; ?></div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_custom_document_id; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_custom_document_id; ?></span>
                </div>
                <div class="col-sm-3">
                  <label><?php echo $text_field; ?></label>
                  <select name="custom_document_id" class="form-control">
                    <option value=""><?php echo $text_none; ?></option>
                    <?php if ($custom_document_id == 'C') { ?>
                    <option value="C" selected="selected"><?php echo $text_column; ?></option>
                    <?php } else { ?>
                    <option value="C"><?php echo $text_column; ?></option>
                    <?php } ?>
                    <?php foreach ($custom_fields_data as $custom_field) { ?>
                    <?php if ($custom_field['location'] == 'account') { ?>
                    <?php if ($custom_field['type'] == 'text') { ?>
                    <?php if ($custom_field['custom_field_id'] == $custom_document_id) { ?>
                    <option value="<?php echo $custom_field['custom_field_id']; ?>" selected="selected"><?php echo $custom_field['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-3" id="document_column">
                  <label><?php echo $text_document; ?></label>
                  <select name="document_column" class="form-control">
                    <option value=""></option>
                    <?php foreach ($columns_data as $column) { ?>
                    <?php if ($column['Field'] == $document_column) { ?>
                    <option value="<?php echo $column['Field']; ?>" selected="selected"><?php echo $column['Field']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_document) { ?>
                  <div class="text-danger"><?php echo $error_document; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_custom_number_id; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_custom_number_id; ?></span>
                </div>
                <div class="col-sm-3">
                  <label><?php echo $text_field; ?></label>
                  <select name="custom_number_id" class="form-control">
                    <option value=""><?php echo $text_none; ?></option>
                    <?php if ($custom_number_id == 'C') { ?>
                    <option value="C" selected="selected"><?php echo $text_column; ?></option>
                    <?php } else { ?>
                    <option value="C"><?php echo $text_column; ?></option>
                    <?php } ?>
                    <?php foreach ($custom_fields_data as $custom_field) { ?>
                    <?php if ($custom_field['location'] == 'address') { ?>
                    <?php if ($custom_field['type'] == 'text') { ?>
                    <?php if ($custom_field['custom_field_id'] == $custom_number_id) { ?>
                    <option value="<?php echo $custom_field['custom_field_id']; ?>" selected="selected"><?php echo $custom_field['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-3" id="number_payment_column">
                  <label><?php echo $text_number_payment; ?></label>
                  <select name="number_payment_column" class="form-control">
                    <option value=""></option>
                    <?php foreach ($columns_data as $column) { ?>
                    <?php if ($column['Field'] == $number_payment_column) { ?>
                    <option value="<?php echo $column['Field']; ?>" selected="selected"><?php echo $column['Field']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_number_payment) { ?>
                  <div class="text-danger"><?php echo $error_number_payment; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-3" id="number_shipping_column">
                  <label><?php echo $text_number_shipping; ?></label>
                  <select name="number_shipping_column" class="form-control">
                    <option value=""></option>
                    <?php foreach ($columns_data as $column) { ?>
                    <?php if ($column['Field'] == $number_shipping_column) { ?>
                    <option value="<?php echo $column['Field']; ?>" selected="selected"><?php echo $column['Field']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_number_shipping) { ?>
                  <div class="text-danger"><?php echo $error_number_shipping; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_custom_complement_id; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_custom_complement_id; ?></span>
                </div>
                <div class="col-sm-3">
                  <label><?php echo $text_field; ?></label>
                  <select name="custom_complement_id" class="form-control">
                    <option value=""><?php echo $text_none; ?></option>
                    <?php if ($custom_complement_id == 'C') { ?>
                    <option value="C" selected="selected"><?php echo $text_column; ?></option>
                    <?php } else { ?>
                    <option value="C"><?php echo $text_column; ?></option>
                    <?php } ?>
                    <?php foreach ($custom_fields_data as $custom_field) { ?>
                    <?php if ($custom_field['location'] == 'address') { ?>
                    <?php if ($custom_field['type'] == 'text') { ?>
                    <?php if ($custom_field['custom_field_id'] == $custom_complement_id) { ?>
                    <option value="<?php echo $custom_field['custom_field_id']; ?>" selected="selected"><?php echo $custom_field['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-3" id="complement_payment_column">
                  <label><?php echo $text_complement_payment; ?></label>
                  <select name="complement_payment_column" class="form-control">
                    <option value=""></option>
                    <?php foreach ($columns_data as $column) { ?>
                    <?php if ($column['Field'] == $complement_payment_column) { ?>
                    <option value="<?php echo $column['Field']; ?>" selected="selected"><?php echo $column['Field']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_complement_payment) { ?>
                  <div class="text-danger"><?php echo $error_complement_payment; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-3" id="complement_shipping_column">
                  <label><?php echo $text_complement_shipping; ?></label>
                  <select name="complement_shipping_column" class="form-control">
                    <option value=""></option>
                    <?php foreach ($columns_data as $column) { ?>
                    <?php if ($column['Field'] == $complement_shipping_column) { ?>
                    <option value="<?php echo $column['Field']; ?>" selected="selected"><?php echo $column['Field']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_complement_shipping) { ?>
                  <div class="text-danger"><?php echo $error_complement_shipping; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-checkout">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_checkout; ?></div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><span class="text-danger">*</span> <strong><?php echo $entry_extension_title; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_extension_title; ?></span>
                </div>
                <div class="col-sm-3">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                    <input type="text" name="extension_title" value="<?php echo $extension_title; ?>" placeholder="" class="form-control" maxlength="50" />
                  </div>
                  <?php if ($error_extension_title) { ?>
                  <div class="text-danger"><?php echo $error_extension_title; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_information; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_information; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="information_id" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <?php if ($information['information_id'] == $information_id) { ?>
                    <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><strong><?php echo $entry_button_style; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_button_style; ?></span>
                </div>
                <div class="col-sm-3">
                  <select name="button_style" class="form-control">
                    <?php foreach ($styles as $key => $value) { ?>
                    <?php if ($key == $button_style) { ?>
                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <h5><span class="text-danger">*</span> <strong><?php echo $entry_button_text; ?></strong></h5>
                  <span class="help"><i class="fa fa-info-circle"></i> <?php echo $help_button_text; ?></span>
                </div>
                <div class="col-sm-3">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                    <input type="text" name="button_text" value="<?php echo $button_text; ?>" placeholder="" class="form-control" maxlength="50" />
                  </div>
                  <?php if ($error_button_text) { ?>
                  <div class="text-danger"><?php echo $error_button_text; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-webhook">
              <div id="progress"></div>
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info_webhook; ?></div>
              <?php if (!isset($hide_webhook)) { ?>
              <input type="hidden" name="webhook_id" id="webhook_id" value="" />
              <div class="table-responsive">
                <div id="add-webhook" style="height: 200px !important; vertical-align: middle;">
                  <button type="button" class="btn btn-primary btn-lg btn-block" onclick="addWebhook();"><?php echo $text_webhook_new; ?></button>
                </div>
                <table id="table-my-webhooks" style="min-width:100% !important;" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="min-width: 140px !important;" class="text-center"><?php echo $column_webhook_id; ?></th>
                      <th style="min-width: 400px !important;" class="text-left"><?php echo $column_webhook_url; ?></th>
                      <th style="min-width: 50px !important;" class="text-center"><?php echo $column_webhook_actions; ?></th>
                    </tr>
                  </thead>
                </table>
                <table id="table-webhooks-events" style="min-width:100% !important;" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('.editor-active[type=checkbox]').prop('checked', this.checked);" /></th>
                      <th style="min-width: 120px !important;" class="text-left"><?php echo $column_name; ?></th>
                      <th style="min-width: 400px !important;" class="text-left"><?php echo $column_description; ?></th>
                      <th style="min-width: 20px !important;" class="text-center"><?php echo $column_status; ?></th>
                    </tr>
                  </thead>
                </table>
                <button type="button" id="btn-send" class="btn btn-primary btn-lg btn-block"><?php echo $text_webhook_save; ?></button>
              </div>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
      $('#add-webhook, #table-my-webhooks, #table-webhooks-events, #btn-send').hide();
      loadWebhooks();
    });

    function loadWebhooks() {
      $('#table-my-webhooks').DataTable().destroy();
      $.ajax({
        url: 'index.php?route=extension/payment/paypal_plus/listWebhooks&token=<?php echo $token; ?>', dataType: 'json',
        beforeSend: function() {
          $('#progress').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_webhook_loading; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        },
        complete: function() {
          $('#progress').html('');
        },
        success: function(jsonData) {
          if (jsonData.empty) {
            $('#table-my-webhooks').DataTable().destroy();
            $('#table-my-webhooks').hide();
            $('#add-webhook').show();
          }
          else if (jsonData.error) {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            $('#progress').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> ' + jsonData.error + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          } else {
            if (jsonData.webhook_id) {
              $('#webhook_id').val(jsonData.webhook_id);
            }
            $('#table-my-webhooks').show();
            $('#add-webhook').hide();
            $('#table-my-webhooks').DataTable({
              "order": [],
              "columnDefs": [
                {
                  "targets": 0,
                  "className": "text-center"
                },
                {
                  "targets": 2,
                  "searchable": false,
                  "orderable": false
                }
              ],
              "data": jsonData.data,
              "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
              }
            });
          }
        }
      });
    }

    function addWebhook() {
      $.ajax({
        url: 'index.php?route=extension/payment/paypal_plus/listWebhooksEventTypes&token=<?php echo $token; ?>', dataType: 'json',
        beforeSend: function() {
          $(this).prop('disabled', true);
          $('#add-webhook').hide();
          $('html, body').animate({ scrollTop: 0 }, 'slow');
          $('#progress').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_webhook_add; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        },
        complete: function() {
          $(this).prop('disabled', false);
          $('#progress').html('');
        },
        success: function(jsonData) {
          if (jsonData.error) {
            $('#add-webhook').show();
            $('#progress').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + jsonData.error + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          } else {
            $('#table-webhooks-events, #btn-send').show();
            $('#table-webhooks-events').DataTable({
              "order": [],
              "pageLength": 100,
              "columnDefs": [
                {
                  "targets": [ 0, 3 ],
                  "className": "text-center"
                },
                {
                  "targets": 0,
                  "searchable": false,
                  "orderable": false
                }
              ],
              "data": jsonData.data,
              "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
              }
            });
          }
        }
      });
    }

    $("#btn-send").click(function() {
      var webhooks = new Array();
      $(".editor-active[type=checkbox]:checked").each(function() {
        webhooks.push({'name': $(this).attr("value")});
      });

      if (webhooks.length > 0) {
        $.ajax({
          url: 'index.php?route=extension/payment/paypal_plus/addwebhooks&token=<?php echo $token; ?>',
          dataType: 'json',
          method: 'POST',
          data: {'event_types': webhooks},
          beforeSend: function() {
            $(this).prop('disabled', true);
            $('#table-webhooks-events, #btn-send').hide();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            $('#progress').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_webhook_saving; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          },
          complete: function() {
            $(this).prop('disabled', false);
          },
          success: function(jsonData) {
            if (jsonData.error) {
              $('#table-webhooks-events, #btn-send').show();
              $('#progress').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + jsonData.error + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            } else {
              $('#table-webhooks-events').DataTable().destroy();
              $('#progress').html('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + jsonData.success + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
              loadWebhooks();
            }
          }
        });
      } else {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        $('#progress').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_webhook_empty; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    });

    function deleteWebhook(id) {
      if (confirm('<?php echo $text_webhook_confirm ?>')) {
        $.ajax({
          url: 'index.php?route=extension/payment/paypal_plus/deletewebhook&token=<?php echo $token; ?>',
          dataType: 'json',
          method: 'POST',
          data: { webhook_id: id },
          beforeSend: function() {
            $('#del-' + id).prop('disabled', true);
            $('#table-my-webhooks').hide();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            $('#progress').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_webhook_deleting; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          },
          complete: function() {
            $('#del-' + id).prop('disabled', false);
          },
          success: function(jsonData) {
            if (jsonData.error) {
              $('#table-my-webhooks').show();
              $('#progress').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + jsonData.error + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            } else {
              $('#progress').html('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + jsonData.success + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
              loadWebhooks();
            }
          }
        });
      }
    };

    $('input[name=total][type=text]').mask("###0.00", {reverse: true});

    $('input[name=sort_order][type=text]').on('keyup change', function() {
      const value = $(this).val().replace(/[^0-9]/g,'');
      $(this).val(value);
    });

    $('input[name^=prefix][type=text]').on('keyup change', function() {
      const value = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g,'');
      $(this).val(value);
    });

    $('input[name=description][type=text]').on('keyup change', function() {
      const value = $(this).val().replace(/[^A-Za-z0-9 ]/g,'');
      $(this).val(value);
    });

    $('#document_column').hide();
    $('#number_shipping_column').hide();
    $('#number_payment_column').hide();
    $('#complement_shipping_column').hide();
    $('#complement_payment_column').hide();

    $('select[name="custom_document_id"]').change(function() {
      if ($(this).val() == 'C') { $('#document_column').show(); } else { $('#document_column').hide(); }
    });
    $('select[name="custom_number_id"]').change(function() {
      if ($(this).val() == 'C') { $('#number_payment_column').show(); } else { $('#number_payment_column').hide(); }
      if ($(this).val() == 'C') { $('#number_shipping_column').show(); } else { $('#number_shipping_column').hide(); }
    });
    $('select[name="custom_complement_id"]').change(function() {
      if ($(this).val() == 'C') { $('#complement_payment_column').show(); } else { $('#complement_payment_column').hide(); }
      if ($(this).val() == 'C') { $('#complement_shipping_column').show(); } else { $('#complement_shipping_column').hide(); }
    });

    $('select[name="custom_document_id"]').trigger('change');
    $('select[name="custom_number_id"]').trigger('change');
    $('select[name="custom_complement_id"]').trigger('change');

    $('input[name="stores[]"]').change(function() {
      let store = '#required-prefix-' + $(this).val();
      let prefix = '#store-prefix-' + $(this).val();

      if ($(this).prop('checked') == true) {
        $(store).html('*');
        $(prefix).attr("required", true);
      } else {
        $(store).html('');
        $(prefix).attr("required", false);
      }
    });
  //--></script>
</div>
<?php echo $footer; ?>