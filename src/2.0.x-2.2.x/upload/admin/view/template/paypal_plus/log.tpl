<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $download ?>" data-toggle="tooltip" title="<?php echo $button_download ?>" class="btn btn-primary"><i class="fa fa-download"></i></a>
        <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $clear; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_empty) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_empty; ?>
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
        <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="form-group">
            <div class="col-sm-12">
              <h5><span class="text-danger">*</span> <strong><?php echo $entry_filter; ?></strong></h5>
            </div>
            <div class="col-sm-3">
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" name="filter_date" value="" class="form-control date" readonly />
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-12">
            <pre class="well"><code><?php echo $log; ?></code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
    const defaultDate = '<?php echo $filter_date; ?>';

    $('.date').datetimepicker({
      language: 'pt-br',
      pickTime: false,
      format: 'YYYY-MM-DD',
      enabledDates: <?php echo $dates; ?>,
      defaultDate
    }).on('dp.change', function(newDate, oldDate) {
      if (newDate !== oldDate) {
        location.href = `<?php echo $action; ?>&filter_date=${$(this).val()}`.replace(/&amp;/, '&');
      }
    });
  //--></script>
  <style>
    pre {
      max-height: 350px;
    }
    code {
      white-space: inherit !important;
    }
    .datepicker td.day:not(.disabled):not(.active) {
      background-color: #428bca75;
    }
  </style>
</div>
<?php echo $footer; ?>