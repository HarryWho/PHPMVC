<?php if (Auth::atLeast('admin')): ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Responsive Hover Table</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User #ID</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>User Role</th>
                                <th>User Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($adminUsers as $auser) : ?>
                                <tr>
                                    <td><?= $auser->user_id ?></td>
                                    <td><?= $auser->user_name ?></td>
                                    <td><?= $auser->user_email ?></td>
                                    <td><?= $auser->user_role ?></td>
                                    <td><?= format_date($auser->user_joinedAt) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- ./col -->
    </div><!-- ./row -->

<?php else: ?>


<?php endif; ?>