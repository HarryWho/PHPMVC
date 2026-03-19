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
        <?php endif; ?>

    </div>
    <!-- /.box-body -->


</div>
<!-- /.box -->

<!-- About Me Box -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">About Me</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->