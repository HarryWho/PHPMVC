 <div class="row">
     <div class="col-md-3">
         <?php include_once "../app/views/dashboard/_left_column.php" ?>
     </div>
     <div class="col-md-9">

         <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
                 <li class="active"><a href="#activity" data-toggle="tab">Create Catagory</a></li>
             </ul>
             <div class="tab-content">
                 <div class="active tab-pane" id="activity">
                        <?php
                            switch($data['which_form']){
                                default:
                                    require_once '../app/views/includes/catagory_form.php';
                                    break;
                            }
                        ?>
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