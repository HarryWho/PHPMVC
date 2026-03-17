<li class="dropdown user user-menu">
  <!-- Menu Toggle Button -->
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <!-- The user image in the navbar-->
    <img src="<?= BASE_URL ?>/dist/img/<?= isLoggedIn() ? escAttr($user->user_image) : 'generic.png' ?>" class="user-image" alt="User Image">
    <!-- hidden-xs hides the username on small devices so only the image appears. -->
    <span class="hidden-xs"><?= isLoggedIn() ? esc($user->user_name) : 'Guest' ?></span>
  </a>
  <ul class="dropdown-menu">
    <!-- The user image in the menu -->
    <li class="user-header">
      <img src="<?= BASE_URL ?>/dist/img/<?= isLoggedIn() ? escAttr($user->user_image) : 'generic.png' ?>" class="img-circle" alt="User Image">

      <p>
        <?= isLoggedIn() ? esc($user->user_name) : 'Guest' ?>
        <small><?= isLoggedIn() ? esc(date("M jS Y", strtotime($user->user_joinedAt))) : '' ?></small>
        <small><?= isLoggedIn() ? esc(ucfirst($user->user_role)) : '' ?></small>
      </p>
    </li>
    <!-- Menu Body -->
    <!-- <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li>-->
    <!-- /.row -->
    <!-- Menu Footer-->
    <li class="user-footer">
      <div class="pull-left">
        <a href="<?= isLoggedIn() ? '/users/profile/' . $user->user_id : '/users/register' ?>" class="btn btn-default btn-flat"><?= isLoggedIn() ? 'View Profile' : 'Register' ?></a>
      </div>
      <div class="pull-right">
        <a href="<?= isLoggedIn() ? '/users/logout' : '/users/login' ?>" class="btn btn-default btn-flat"><?= isLoggedIn() ? 'Sign out' : 'Sign in' ?></a>
      </div>
    </li>
  </ul>
</li>