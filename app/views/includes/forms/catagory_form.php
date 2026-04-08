 <form id="catagory_form" role="form" action="/create/catagory" method="post" enctype="multipart/form-data">
     <input type="hidden" name="catagory_authorId" value="<?= Auth::user()->user_id ?>">
     <input type="hidden" name="catagory_image" id="catagory_image">
     <!-- Widget: user widget style 1 -->
     <div class="box box-widget widget-user">
         <!-- Add the bg color to the header using any of the bg-* classes -->
         <div class="widget-user-header bg-black" id="catagory_background">
             <h3 class="widget-user-username"><?= Auth::user()->user_name ?></h3>

         </div>
         <div class="widget-user-image">
             <img class="img-circle" src="<?= BASE_URL ?>/dist/img/<?= Auth::user()->user_image ?>" alt="User Avatar">
         </div>
         <div class="box-footer">
             <div class="row">
                 <div class="form-group">
                     <label for="catagory_title">Catagory Title<span class='text-danger'>*</span></label>
                     <input type="text" class="form-control" id="catagory_title" name="catagory_title" placeholder="Enter Catagory Title">
                 </div>
                 <div class="form-group">
                     <label for="catagory_description">Catagory Description<span class='text-danger'>*</span></label>
                     <textarea class="form-control" name="catagory_description" id="catagory_description" placeholder="Describe the Catagory"></textarea>


                 </div>
                 <div class="form-group">
                     <label for="image">Upload Image</label>
                     <button id="upload-btn" class="btn btn-sm btn-success" return false>Upload</button>


                     <p class="help-block">Optional*: Image shown on the catagory page.</p>
                 </div>

             </div>
             <!-- /.row -->

         </div>
     </div>
     <!-- /.widget-user -->
 </form>


 <!-- #region Modal -->
 <div class="modal modal-default fade" id="modal-default">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"></h4>
             </div>
             <div class="modal-body">
                 <?php require_once "upload_form.php" ?>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="confirm_upload" class="btn btn-danger">Save changes</button>
             </div>
         </div>
         <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
 </div>
 <!-- /.modal -->

 <script>
     const catagory_background = document.getElementById("catagory_background");
     const catagory_image = document.getElementById('catagory_image');
     $("#upload-btn").on('click', (e) => {
         e.preventDefault();
         $("#modal-default").modal('show');
     })

     $("#upload_form").on('submit', async (e) => {
         e.preventDefault();

         const formData = new FormData(e.target);

         // Quick debug - see exactly what is being sent
         for (const [key, value] of formData.entries()) {
             console.log(key, value);
         }

         try {
             const response = await fetch('/uploads/upload', { // ← change to your actual endpoint
                 method: 'POST',
                 body: formData
                 // Do NOT set Content-Type header! Let browser set it with boundary
             });

             const result = await response.json();
             if (result.success) {
                 $('#modal-default').modal('hide');
                 catagory_background.style.backgroundImage = `url('<?= BASE_URL ?>/dist/img/uploads/${result.messageBody}')`;
                 catagory_background.style.backgroundPosition = "center center";
                 catagory_background.style.backgroundSize = "cover";
                 catagory_image.value = result.messageBody;
                 console.log(`<?= BASE_URL ?>/dist/img/uploads/${result.messageBody}`);
                 console.log('✅ Success!', result.messageBody);
             } else {
                 console.error('❌ Upload failed:', result.message);
                 console.log('Full debug info:', result.debug); // ← Add this line
             }
         } catch (err) {
             console.error('Network or server error:', err);
         }
     })
 </script>