<!-- Groups i belong to -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Pages i have Joined </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul>
            <?php if (!empty($data['groups-i-am-in'])): ?>
            <?php else: ?>
                <!-- TODO -->
                <li>You do not belong to any groups</li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<!-- Groups i belong to -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">My Pages</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul>
            <?php if (!empty($data['my-pages'])): ?>
            <?php else: ?>
                <!-- TODO Create One-->
                <li>You have No pages</li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->