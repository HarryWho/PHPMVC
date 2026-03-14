<style>
.error {
  color: red;
  font-size: 12px;
}
</style>
<div class="register-box">
  <div class="register-logo">
    <a href="/"><b>Admin</b>LTE</a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Register a new membership</p>

    <form action="/users/register" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="User name" name="user_name" value="<?= isset($data['field_values']['user_name']) ? $data['field_values']['user_name'] : ''; ?>" required >
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="user_email" value="<?= isset($data['field_values']['user_email']) ? $data['field_values']['user_email'] : ''; ?>" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <span class="error"><?= isset($data['errors']['email_error']) ? $data['errors']['email_error'] : ''; ?></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="user_password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span class="error"><?= isset($data['errors']['password_error']) ? $data['errors']['password_error'] : ''; ?></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Retype password" name="confirm_password" required>
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> I agree to the <a href="#">terms</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

   
    <a href="/users/login" class="text-center">I already have a membership</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->