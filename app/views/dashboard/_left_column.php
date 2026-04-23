<!-- Profile Image -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Catagories</h3>
    </div>
    <div class="box-body box-profile">
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <ul class="sidebar-menu">
                    <?php if (Auth::atLeast('admin')): ?>
                        <li><a href="/create/catagory"><i class="fa  fa-check-square-o"></i> Create a Catagory</a></li>
                    <?php endif ?>
                </ul>
            </li>
            <?php if (!empty($data['catagories'])): ?>
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
            <?php else: ?>
                <li>There are no Catagories Listed</li>
            <?php endif; ?>
        </ul>

    </div>
    <!-- /.box-body -->


</div>
<!-- /.box -->