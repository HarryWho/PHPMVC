<li class="dropdown tasks-menu">
  <!-- Menu Toggle Button -->
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-flag-o"></i>
    <span class="label label-danger"><?= empty($data['tasks']) ? '0' : count($data['tasks']) ?></span>
  </a>
  <ul class="dropdown-menu">
    <li class="header">You have <?= $tasks ?> tasks</li>
    <li>
      <!-- Inner menu: contains the tasks -->
      <ul class="menu">
        <?php if (!empty($data['tasks'])): ?>
          <?php foreach ($data['tasks'] as $task): ?>
            <li><!-- Task item -->
              <a href="#">
                <!-- Task title and progress text -->
                <h3>
                  <?= esc($task->task_message) ?>
                  <small class="pull-right">20%</small>
                </h3>
                <!-- The progress bar -->
                <div class="progress xs">
                  <!-- Change the css width attribute to simulate progress -->
                  <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                    aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                    <span class="sr-only">20% Complete</span>
                  </div>
                </div>
              </a>
            </li>
            <!-- end task item -->
          <?php endforeach; ?>
        <?php else: ?>
          <ul>
            <li>You have no tasks</li>
          </ul>
        <?php endif; ?>
      </ul>
    </li>
    <li class="footer">
      <a href="#">View all tasks</a>
    </li>
  </ul>
</li>