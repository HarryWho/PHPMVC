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
                                    <td><?= esc($auser->user_name) ?></td>
                                    <td><?= esc($auser->user_email) ?></td>
                                    <td><?= format_date($auser->user_joinedAt) ?></td>
                                    <td>
                                        <select name="roles" class="roles form-control">
                                            <option value="member" <?= esc($auser->user_role) == 'member' ? 'selected' : '' ?>>Member</option>
                                            <option value="author" <?= esc($auser->user_role) == 'author' ? 'selected' : '' ?>>Author</option>
                                            <option value="moderator" <?= esc($auser->user_role) == 'moderator' ? 'selected' : '' ?>>Moderator</option>
                                            <option value="admin" <?= esc($auser->user_role) == 'admin' ? 'selected' : '' ?>>Administrator</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="/users/delete/<?= $auser->user_id ?>" return false class='delete-btn'>Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- #region Modal -->
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Update Users</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this user &hellip;</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="button" id="confirm_delete" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div><!-- ./col -->
    </div><!-- ./row -->
    <script>
        const roles = document.querySelectorAll(".roles");
        const deleteBtns = document.querySelectorAll('.delete-btn');



        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();

                $('#modal-default').modal('show'); // ✅ Correct
                $("#confirm_delete").on('click', () => {
                    $('#modal-default').modal('hide');

                    let tr = e.target.parent; // find the row
                    alert(tr);
                    if (doAjaxCall(e.target.href)) {
                        // after AJAX success:
                        tr.remove();
                    }
                })

            })
        })

        roles.forEach(role => {
            role.addEventListener('change', (e) => {

                let formData = new FormData();

                formData.append("user_role", e.target.value);
                url = "/users/update/" + parseInt(e.target.parentNode.parentNode.children[0].innerText, 10)
                doAjaxCall(url, formData)



            })
        })

        function doAjaxCall(p_url, formData = null) {
            alert(p_url)
            $.ajax({
                url: p_url,
                type: "POST",
                data: formData,
                processData: false, // required
                contentType: false, // required
                dataType: "json",
                success: function(response) {
                    console.log("Server says:", response);
                    if (response.success) {
                        alert(response.message);
                        return response.success;
                    } else {
                        alert("Update failed: " + response.message);
                        return false;
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        }
    </script>
<?php else: ?>


    You are not Authorized to view that page!
<?php endif; ?>
</div>