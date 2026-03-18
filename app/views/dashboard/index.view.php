 <div class="row">
     <div class="col-md-3">
         <?php include_once "_left_column.php" ?>
     </div>
     <div class="col-md-9">
         <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
                 <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
                 <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
             </ul>
             <div class="tab-content">
                 <div class="active tab-pane" id="activity">
                     <?php include_once "_activity.php" ?>
                 </div>
                 <!-- /.tab-pane -->
                 <div class="tab-pane" id="timeline">
                     <?php include_once "_timeline.php" ?>
                 </div>
                 <!-- /.tab-pane -->
             </div>
             <!-- /.tab-content -->
         </div>
         <!-- /.nav-tabs-custom -->
     </div>
     <!-- /.col -->

 </div>
 <!-- /.row -->