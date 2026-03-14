<header class="main-header">

    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>PC</b>O</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>PC-O</b>ptimizers</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <?php if (Auth::atLeast('member')): ?>
              
            <!-- Messages: style can be found in dropdown.less-->
            <?php include '../app/views/includes/_navbar_messages.php'; ?>
            <!-- /.messages-menu -->

            <!-- Notifications Menu -->
            <?php include '../app/views/includes/_navbar_notifications.php'; ?>
            <!-- Tasks Menu -->
            <?php include '../app/views/includes/_navbar_tasks.php'; ?>
          <?php endif; ?>
          <!-- User Account Menu -->
          <?php include '../app/views/includes/_navbar_user.php'; ?>

          <?php if(isLoggedIn()): ?>
          <!-- Control Sidebar Toggle Button -->
            <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>