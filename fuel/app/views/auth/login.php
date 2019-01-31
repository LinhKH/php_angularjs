<form action="<?php echo \Uri::create('/api/auth/login', [], []); ?>" method="post" id="login-form">
  <h1>Admin Login</h1>
  <div>
    <input type="text" placeholder="Username" name="user_id"/>
    <span class="error user_id" style="font-style: italic;font-size: 12px;color: red; float: left; padding-top:5px"></span>
  </div>
  <div>
    <input type="password" placeholder="Password" name="password"/>
    <span class="error password" style="font-style: italic;font-size: 12px;color: red; float: left; padding-top:5px"></span>
  </div>
  <div>
    <button type="submit" >Log in</button>
  </div>
  <div class="form-group">
      <a href="<?php echo \Uri::create('auth/forgot') ?>" class="">パスワードを忘れたらこちら</a>
  </div>
</form>