<?php
$main_page = "Login";
$page = "Login";
include './file_includes.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include './header_links.php';
        ?>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
               <a href="<?php echo site_url();?>">
                    <img src="dist/img/logo.png" class="image" width="200" />
                </a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg" style="font-weight: bold; font-size: 17px;">
                    Compliance Web Application</p>
                <form action="getAPI.php" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Username" name="user_name" id="user_name">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Password" name="user_pass" id="user_pass">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="submit" id="member_login" name="member_login" class="btn btn-primary btn-block btn-flat">
                                Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <br/><br/>
                <b class="text-danger">
                  <?php
                if ($_REQUEST['loginErr']) {
                    echo $_REQUEST['loginErr'];
                }
                ?>  
                </b> 
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <?php
        include './footer_links.php';
        ?>

    </body>
</html>
