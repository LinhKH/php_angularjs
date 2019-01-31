<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="left-sidebar-toggle-button pull-left">
            <div id="nav-icon3" class="sidebar-toggle-button">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="navbar-logo pull-left">
            <?php echo Asset::img('logo.png')?>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#" class="text-color-black">{{app.userInfo.emp_nm}}</a></li>
            <li class="divider-vertical"></li>
            <li><a class="text-color-black" href="{{app.baseUrl}}/logout"><i class="fas fa-sign-out-alt"></i></a></li>
        </ul>
    </div>
</nav>