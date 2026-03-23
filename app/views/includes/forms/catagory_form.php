 <form role="form" action="/create/catagory">

     <div class="box-body">
         <div class="form-group">
             <label for="catagory_title">Catagory Title<span class='text-danger'>*</span></label>
             <input type="text" class="form-control" id="catagory_title" name="catagory_title" placeholder="Enter Catagory Title">
         </div>
         <div class="form-group">
             <label for="catagory_description">Catagory Description<span class='text-danger'>*</span></label>
             <textarea class="form-control" name="catagory_description" id="catagory_description" placeholder="Describe the Catagory"></textarea>


         </div>
         <div class="form-group">
             <label for="image">File input</label>
             <input type="file" id="image" name="image" accept="image/*">

             <p class="help-block">Optional*: Image shown on the catagory page.</p>
         </div>

     </div>
     <!-- /.box-body -->

     <div class="box-footer">
         <button type="submit" class="btn btn-primary">Submit</button>
     </div>
 </form>