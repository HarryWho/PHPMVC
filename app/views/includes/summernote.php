<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">

<div id="summernote"></div>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
  $(document).ready(function() {
    $('#summernote').summernote({

        airMode: false,
        toolbar: [],
        placeholder: "Leave a Meaage",
        disableDragAndDrop: true,
        disableResizeEditor: true
    });
    $("#summernote").css({
      'font-size': '14pt',
      'boder-radius': '50px',
      'height': '200px'
    })
});



</script>