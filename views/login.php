<div class="col-md-offset-3 col-md-6">
    <form class="form-horizontal" action="login" method="post">
        <span class="heading">АВТОРИЗАЦИЯ</span><br /><br />
        <div class="form-group">
            <input type="name" class="form-control" placeholder="Логин" name="login" value="<?=$_POST['login']; ?>" />
            <i class="fa fa-user"></i>
        </div>
        <div class="form-group help">
            <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="pass" value="" />
            <i class="fa fa-lock"></i>
            <a href="#" class="fa fa-question-circle"></a>
        </div>
        <div>
            <button type="submit" class="btn btn-primary mb-2">ВХОД</button>
        </div>
    </form>
</div>