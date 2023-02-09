<?php
$main_page = "Profile";
$page = "Change password";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php include './header_links.php'; ?>
        </head>
        <body class="hold-transition skin-blue sidebar-mini">
            <div class="wrapper">
                <?php
                include './top_menu.php';
                include './side_menu.php';
                ?>
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            <?php echo $page; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li><a href="#"><?php echo $main_page; ?></a></li>
                            <li class="active"><?php echo $page; ?></li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <!-- SELECT2 EXAMPLE -->
                        <div class="box box-default">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form class="forms-sample">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="oldpass">Old password</label><b class="text-danger"> * <span id="old_passErr"></span></b>
                                                            <input type="password" class="form-control" name="old_pass" id="old_pass" placeholder="ENTER PASSWORD" />
                                                 
                                                        </div>
                                                        <button type="button" id="change_pass" class="btn btn-primary mr-2">Change password</button>
                                            <b id="pass_success_message" class="text-success"></b>
                                            <b id="pass_error_message" class="text-danger"></b>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="newpass">New password</label><b class="text-danger"> * <span id="new_passErr"></span></b>
                                                            <input type="password" class="form-control" name="new_pass" id="new_pass" placeholder="ENTER PASSWORD" />
                                                </div>
                                                    </div>
                                                </div>
                                            </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
                <?php
                include './footer.php';
                ?>

                <div class="control-sidebar-bg"></div>
            </div>
            <!-- ./wrapper -->
            <?php
            include './footer_links.php';
            ?>
             <script type="text/javascript">
                $(document).ready(function () {

                    /* *********************************** CHANGE PASSWORD *********************************** */

                    $('#change_pass').click(function () {
                        var login_user = "<?php echo $login_user; ?>";
                        var old_pass = $("#old_pass").val().trim();
                        var new_pass = $("#new_pass").val().trim();

                        if ((new_pass === "") || (old_pass === "")) {
                            $("#pass_error_message").html("Provide all the fields<br/><br/>");
                            $('#old_passErr').text("Required");
                            $('#new_passErr').text("Required");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?change_pass",
                                dataType: "json",
                                data: {
                                    login_user: login_user,
                                    old_pass: old_pass,
                                    new_pass: new_pass
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#pass_success_message").html(RetVal.data + "<br/><br/>");
                                        $("#pass_error_message").text("");
                                        $('#old_passErr').text("");
                                        $('#new_passErr').text("");
                                        $('#old_pass').val("");
                                        $('#new_pass').val("");
                                    } else {
                                        $("#pass_error_message").html(RetVal.message + "<br/><br/>");
                                        $("#pass_success_message").text("");
                                        var old_passErr = JSON.parse(RetVal.data).OldpassErr;
                                        var new_passErr = JSON.parse(RetVal.data).NewpassErr;

                                        if (old_passErr === null) {
                                            old_passErr = "";
                                        }
                                        if (new_passErr === null) {
                                            new_passErr = "";
                                        }
                                        $('#old_passErr').text(old_passErr);
                                        $('#new_passErr').text(new_passErr);
                                    }

                                }
                            });
                        }
                    });
                    /* *********************************** CHANGE PASSWORD *********************************** */
                });
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>