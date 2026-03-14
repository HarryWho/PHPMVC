<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= BASE_URL ?>/dist/img/<?= isLoggedIn() ? $user->user_image : 'generic.png' ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= isLoggedIn() ? $user->user_name : 'Guest' ?></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle <?= isLoggedIn() ? 'text-success' : 'text-danger' ?>"></i> <?= isLoggedIn() ? 'Online' : 'Offline' ?></a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="<?= $data['title'] == 'Dashboard' || $data['title'] == 'Home' ? 'active' : '' ?>"><a href="<?= isLoggedIn() ? '/dashboard' : '/' ?>"><i class="fa fa-<?= isLoggedIn() ? 'dashboard' : 'home' ?>"></i> <span><?= isLoggedIn() ? 'Dashboard' : 'Home' ?></span></a></li>
        <li class="<?= $data['title'] == 'Profile' || $data['title'] == 'Register' ? 'active' : '' ?>"><a href="<?= isLoggedIn() ? '/users/profile/' . $user->user_id : '/users/register' ?>"><i class="fa fa-<?= isLoggedIn() ? 'user' : 'user-plus' ?>"></i> <span><?= isLoggedIn() ? 'Profile' : 'Register' ?></span></a></li>
        <li class="<?= $data['title'] == 'Login' || $data['title'] == 'Logout' ? 'active' : '' ?>"><a href="<?= isLoggedIn() ? '/users/logout' : '/users/login' ?>"><i class="fa fa-<?= isLoggedIn() ? 'sign-out' : 'sign-in' ?>"></i> <span><?= isLoggedIn() ? 'Logout' : 'Login' ?></span></a></li>
         
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>