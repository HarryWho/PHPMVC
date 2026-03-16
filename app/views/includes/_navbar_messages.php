<li class="dropdown messages-menu">
  <!-- Menu toggle button -->
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-envelope-o"></i>
    <span class="label label-success"><?= empty($messages) ? '0' : count($messages) ?></span>
  </a>
  <ul class="dropdown-menu">
    <li class="header">You have <?= count($messages) ?> messages</li>
    <li>
      <!-- inner menu: contains the messages -->
      <ul class="menu">
        <?php if (!empty($messages)) : ?>
          <?php foreach ($messages as $msg): ?>
            <li><!-- start message -->
              <a href="#">
                <div class="pull-left">
                  <!-- User Image -->
                  <img src="<?= BASE_URL ?>/dist/img/<?= $msg->user_image ?>" class="img-circle" alt="User Image">
                </div>
                <!-- Message title and timestamp -->
                <h4>
                  <?= $msg->user_name ?>
                  <small><i class="fa fa-clock-o"></i> <?= timeAgo($msg->message_createdAt) ?></small>
                </h4>
                <!-- The message -->
                <p><?= $msg->message_message ?></p>
              </a>
            </li>
            <!-- end message -->
          <?php endforeach; ?>

        <?php else: ?>
          <li>You have no messages</li>
        <?php endif; ?>
      </ul>
      <!-- /.menu -->
    </li>
    <li class="footer"><a href="#">See All Messages</a></li>
  </ul>
</li>