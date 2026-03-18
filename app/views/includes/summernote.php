<link rel="stylesheet" href="<?= BASE_URL ?>/dist/css/summernote.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/dist/css/font-awesome.min.css">
<!--<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.css" rel="stylesheet">-->
<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">-->
<div class="box box-primary direct-chat direct-chat-primary collapsed-box">
  <div class="box-header with-border">
    <h3 class="box-title cursor-pointer" data-widget="collapse">Leave a Note</h3>
    <div class="box-tools pull-right">
      <!--<span data-toggle="tooltip" title="3 New Messages" class="badge bg-light-blue">3</span>-->
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
        <i class="fa fa-comments"></i></button>
      <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
    </div>
  </div>
  <div class="box-body">
    <div id="summernote"></div>
    <div class="box-footer">
      Footer
    </div>
  </div>
</div>
<div id="summernote"></div>
<script src="<?= BASE_URL ?>/dist/js/summernote.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.js"></script>-->
<!-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>-->
<script>
  $(document).ready(function() {
    $("#summernote").css({
      'font-size': '14pt',
      'border-radius': '15px',
      'height': '200px',
      'padding': '10px'
    });

    $('#summernote').summernote({

      airMode: true,

      placeholder: "Leave a Message",
      disableDragAndDrop: false,
      disableResizeEditor: true
    });

  });
</script>