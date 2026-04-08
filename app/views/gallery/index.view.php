<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Our Family Gallery
      <small>A private place for our memories</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    
    <!-- Hero Banner -->
    <div class="box box-widget widget-user-2" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.8)), url('https://picsum.photos/id/1015/1200/400') center/cover; color: white; border: none;">
      <div class="widget-user-header text-center" style="padding: 60px 20px;">
        <h1 class="text-5xl font-bold tracking-tight">Our Forever</h1>
        <p class="text-2xl mt-3 opacity-90">Moments that belong only to us</p>
      </div>
    </div>

    <!-- Gallery Grid -->
    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title">Family Photos</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-primary btn-sm" onclick="showUploadModal()">
            <i class="fa fa-upload"></i> Upload New Photos
          </button>
        </div>
      </div>
      
      <div class="box-body">
        <div class="row" id="family-gallery">
          <!-- PHP will fill this dynamically -->
        </div>
      </div>
    </div>

  </section>
</div>
<!-- /.content-wrapper -->