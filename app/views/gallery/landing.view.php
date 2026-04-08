<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Forever • Private Family Space</title>
  
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-header {
      background: linear-gradient(135deg, #1e3a8a, #3b82f6);
      border-bottom: none;
    }
    .hero {
      background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.75)), url('https://picsum.photos/id/1015/1920/1080') center/cover no-repeat;
      height: 85vh;
      display: flex;
      align-items: center;
      color: white;
    }
    .card-family {
      transition: all 0.3s ease;
    }
    .card-family:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3);
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini"><b>OF</b></span>
      <span class="logo-lg"><b>Our Forever</b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">Toggle navigation</span></a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li><a href="#">Logout</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left Sidebar -->
  <?php include_once '../app/views/includes/aside/_aside.php'; ?>   <!-- Keep your existing sidebar if you have one -->

  <!-- Content Wrapper -->
  <div class="content-wrapper">

    <!-- Hero Section -->
    <section class="hero text-center">
      <div class="container">
        <h1 class="text-6xl font-bold mb-6 tracking-tight">Welcome Home</h1>
        <p class="text-3xl mb-10 max-w-2xl mx-auto">
          A private space for our family<br>
          <span class="text-amber-300">No matter where we are in the world</span>
        </p>
        <div class="flex justify-center gap-6">
          <a href="gallery.php" class="btn btn-lg btn-primary px-10 py-4 text-xl rounded-full">
            <i class="fa fa-images"></i> Enter Gallery
          </a>
          <a href="upload.php" class="btn btn-lg btn-success px-10 py-4 text-xl rounded-full">
            <i class="fa fa-upload"></i> Upload Photos
          </a>
        </div>
      </div>
    </section>

    <!-- Features / Why This Site -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">
            <div class="box box-widget card-family bg-white">
              <div class="box-body text-center p-8">
                <i class="fa fa-lock text-5xl text-blue-600 mb-4"></i>
                <h3 class="font-semibold text-2xl mb-3">Private & Secure</h3>
                <p class="text-gray-600">Only family members can access. Protected with password.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box box-widget card-family bg-white">
              <div class="box-body text-center p-8">
                <i class="fa fa-heart text-5xl text-red-500 mb-4"></i>
                <h3 class="font-semibold text-2xl mb-3">Made with Love</h3>
                <p class="text-gray-600">Every photo, every memory — just for us.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box box-widget card-family bg-white">
              <div class="box-body text-center p-8">
                <i class="fa fa-globe text-5xl text-green-600 mb-4"></i>
                <h3 class="font-semibold text-2xl mb-3">Anywhere in the World</h3>
                <p class="text-gray-600">Share moments no matter how far apart we are.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-10">
          <div class="col-md-12">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3 id="photo-count">248</h3>
                <p>Family Photos</p>
              </div>
              <div class="icon">
                <i class="fa fa-camera"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      Made with ❤️ for our family
    </div>
    <strong>Our Forever</strong> — Private Family Memory Space
  </footer>
</div>

<!-- AdminLTE JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/js/adminlte.min.js"></script>

</body>
</html>