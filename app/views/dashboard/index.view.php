 <div class="row" style="margin:auto;">

     <div class="col-lg-8 col-md-9 col-sm-12">

         <div class="nav-tabs-custom" style="padding:10px;">
             <ul class="nav nav-tabs">
                 <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
                 <li><a href="#editor" data-toggle="tab">Editor</a></li>
             </ul>
             <div class="tab-content">
                 <div class="active tab-pane" id="activity">

                     <?php include_once "_dashboard.php" ?>
                 </div>
                 <!-- /.tab-pane -->
                 <div class="tab-pane" id="editor">
                     <?php include_once "../app/views/includes/editors/summernote.php" ?>
                 </div>

             </div>
             <!-- /.tab-content -->
         </div>
         <!-- /.nav-tabs-custom -->
     </div>
     <!-- /.col -->
     <div class="col-lg-4 col-md-3 hidden-sm hidden-xs">
         <?php include_once "_activity.php" ?>
     </div>
 </div>
 <!-- /.row -->