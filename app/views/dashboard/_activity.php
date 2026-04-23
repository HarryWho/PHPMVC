<?php if (!empty($data['catagories'])): ?>
    <?php foreach ($data['catagories'] as $cat): ?>
        <div>
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-black" style="background: url('../dist/img/photo1.png') center center;">
                    <h3 class="widget-user-username"><?= $cat->user_name ?></h3>
                    <h5 class="widget-user-desc"><?= $cat->catagory_title ?></h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle" src="<?= BASE_URL ?>/dist/img/<?= $cat->user_image ?>" alt="User Avatar">
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <?php if (Auth::atLeast('author')): ?>
                                    <a href="#">Create Room</a>
                                <?php endif; ?>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">0</h5>
                                <span class="description-text">Rooms</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col">
                            <?= $cat->catagory_description ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.widget-user -->
        </div>
    <?php endforeach ?>
<?php endif ?>