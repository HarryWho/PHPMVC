<li class="dropdown notifications-menu">
  <!-- Menu toggle button -->
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span class="label label-warning"><?= empty($data['notifications']) ? '0' : count($data['notifications']) ?></span>
  </a>
  <ul class="dropdown-menu">
    <li class="header">You have <?= empty($data['notifications']) ? '0' : count($data['notifications']) ?> notifications</li>
    <li>
      <!-- Inner Menu: contains the notifications -->
      <ul class="menu">
        <?php if (!empty($data['notifications'])): ?>
          <?php foreach ($data['notifications'] as $note): ?>
            <li><!-- start notification -->
              <a href="#">
                <!-- The message -->
                <div style="width:5px;">
                  <i class="fa fa-users text-aqua"></i>
                  <?= esc($note->notification_message) ?>
                </div>
                <!-- Message title and timestamp -->
                <div class="pull-right">
                  <small><i class="fa fa-clock-o"></i> <?= timeAgo($note->notification_createdAt) ?></small>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <ul>
            <li>You have no notifications</li>
          </ul>
        <?php endif; ?>
        <!-- end notification -->
      </ul>
    </li>
    <li class="footer"><a href="#">View all</a></li>
  </ul>
</li>