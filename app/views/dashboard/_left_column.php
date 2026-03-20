<!-- Profile Image -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Catagories</h3>
    </div>
    <div class="box-body box-profile">
        <?php if (!empty($data['catagories'])): ?>
            <ul class="sidebar-menu" data-widget="tree">
                <?php foreach ($data['catagories'] as $catagory): ?>
                    <li>
                        <div class="user-panel">
                            <div class="pull-left image">
                                <img src="<?= BASE_URL ?>/dist/img/<?= escAttr($catagory->user_image) ?>" class="img-circle" alt="User Image" style="width:24px;">
                                <a href="#<?= $catagory->catagory_title ?>">
                                    <?= $catagory->catagory_title ?>
                                </a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>There are no Catagories Listed</p>
        <?php endif; ?>

    </div>
    <!-- /.box-body -->


</div>
<!-- /.box -->

<!-- Groups i belong to -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Pages i have Joined </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?php if (!empty($data['groups-i-am-in'])): ?>
        <?php else: ?>
            <!-- TODO -->
            <p>You do not belong to any groups</p>
        <?php endif; ?>
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
        <?php if (!empty($data['my-pages'])): ?>
        <?php else: ?>
            <!-- TODO Create One-->
            <p>You have No pages</p>
        <?php endif; ?>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->