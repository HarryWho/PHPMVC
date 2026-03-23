<?php if (Auth::atLeast('admin')): ?>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Site Users</h3>

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
                                <th>User Joined</th>
                                <th>User Role</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['users'] as $auser) : ?>
                                <tr>
                                    <td><?= esc($auser->user_id) ?></td>
                                    <td><a href="/admin/ajax_get_user/<?= esc($auser->user_id) ?>" class="show_user" return false><?= esc($auser->user_name) ?></a></td>
                                    <td><?= esc($auser->user_email) ?></td>
                                    <td><?= format_date(esc($auser->user_joinedAt)) ?></td>
                                    <td>
                                        <select name="roles" class="roles form-control">
                                            <option value="member" <?= esc($auser->user_role) == 'member' ? 'selected' : '' ?>>Member</option>
                                            <option value="author" <?= esc($auser->user_role) == 'author' ? 'selected' : '' ?>>Author</option>
                                            <option value="moderator" <?= esc($auser->user_role) == 'moderator' ? 'selected' : '' ?>>Moderator</option>
                                            <option value="admin" <?= esc($auser->user_role) == 'admin' ? 'selected' : '' ?>>Administrator</option>
                                            <option value="super_admin" <?= esc($auser->user_role) == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                        </select>
                                    </td>
                                    <td>
                                        <?php if (Auth::user()->user_role === 'admin' or Auth::user()->user_role === 'super_admin' and $auser->user_id !== Auth::user()->user_id or $auser->user_role !== 'super_user'): ?>
                                            <a href="/users/ajax_delete/<?= esc($auser->user_id) ?>" class='delete-btn btn btn-warning' style='border-radius:50%;' return false><i class="fa fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- #region Modal -->
            <div class="modal modal-warning fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
                            <button type="button" id="confirm_delete" class="btn btn-danger">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <!-- #region Modal Info -->
            <div class="modal fade" id="modal-dialog" style="width:auto;margin:auto;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">

                            <button type="button" id="confirm_delete" data-dismiss="modal" class="btn btn-info">OK</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div><!-- ./col -->
    </div><!-- ./row -->
    <?php include_once '../app/views/includes/_ajax.php' ?>
    <script>
        const roles = document.querySelectorAll(".roles");
        const deleteBtns = document.querySelectorAll('.delete-btn');

        const users = document.querySelectorAll('.show_user');

        users.forEach(user => {
            user.addEventListener('click', (e) => {
                e.preventDefault();
                doAjaxCall(e.target.href, null, 'user_details');


            });
        })

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                let name = e.target.parentNode.parentNode.children[1].innerText; // find the row
                let tr = e.target.parentNode.parentNode;
                let user_id = <?= Auth::user()->user_id ?>;
                $("#confirm_delete").text("Delete " + name);
                $("#modal-default .modal-title").text("Deleting " + name + "?");
                $("#modal-default .modal-body").text("Are you sure you want to delete " + name + "?");
                $("#confirm_delete").prop(
                    'disabled', user_id === parseInt(tr.children[0].innerText)
                );
                if (user_id === parseInt(tr.children[0].innerText)) {
                    $("#modal-default .modal-body").text("You cannot delete yourself")
                    $("#modal-default .modal-title").text("Deleting Yourself?... You cannot do that");
                }

                $('#modal-default').modal('show'); // ✅ Correct
                $("#confirm_delete").on('click', () => {
                    $('#modal-default').modal('hide');

                    if (doAjaxCall(e.target.href)) {
                        // after AJAX success:
                        tr.parentNode.removeChildNode(tr);
                    }
                })

            })
        })

        roles.forEach(role => {
            role.addEventListener('change', (e) => {

                let formData = new FormData();

                formData.append("user_role", e.target.value);
                url = "/users/ajax_update_role/" + parseInt(e.target.parentNode.parentNode.children[0].innerText, 10)
                doAjaxCall(url, formData)
            })
        })

        function showModal(response, type) {

            $("#modal-dialog .modal-title").html(response.message);
            $("#modal-dialog .modal-body").html(response.messageBody);
            $("#modal-dialog").addClass(type);
            $('#modal-dialog').modal('show'); // ✅ Correct
        }
    </script>
<?php else: ?>


    You are not Authorized to view that page!
<?php endif; ?>
</div>