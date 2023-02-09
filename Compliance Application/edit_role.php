<?php
$main_page = "Master";
$page = "Update Role";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['user_id']) {
        $user_id = $_GET['user_id'];
        $user_SQL = "SELECT * FROM member_login_access WHERE id='$user_id'";
        $fetch_user = json_decode(ret_json_str($user_SQL));
        foreach ($fetch_user as $fetch_users) {
            $role_id = $fetch_users->role;
            $username = $fetch_users->username;
        }
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
                                <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                                <li class="active"><?php echo $page; ?></li>
                            </ol>
                        </section>

                        <!-- Main content -->
                        <section class="content">

                            <div class="box box-default">
                                <div class="box-body">
                                    <form class="forms-sample">
                                        <div class="form-group">
                                            <label for="username">Username</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" disabled="disabled" value="<?php echo $username;?>" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="role">Role</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="role">
                                                <option value="" 
                                                <?php
                                                if ($role_id == "") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>SELECT ROLE</option>
                                                        <?php
                                                        $role_SQL = "SELECT * FROM mas_role WHERE status=1 AND id>0";
                                                        $fetch_role = json_decode(ret_json_str($role_SQL));
                                                        foreach ($fetch_role as $fetch_roles) {
                                                            ?>
                                                    <option value="<?php echo $fetch_roles->id; ?>"
                                                    <?php
                                                    if ($role_id == $fetch_roles->id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>
                                                    <?php echo $fetch_roles->role_name; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <b class="text-danger" id="roleErr"></b>
                                        </div>

                                        <button type="button" id="save_role" class="btn btn-primary mr-2">Save</button>
                                        <b class="text-success" id="success_message"></b>
                                        <b class="text-danger" id="error_message"></b>
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

                        /* *********************************** UPDATE DEPARTMENT *********************************** */

                        $('#save_role').click(function () {
                            //alert("Working");
                            var user_id = "<?php echo $user_id; ?>";
                            var role_id = $('#role').val();

                            if (role === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#roleErr').text(role === "" ? "Required" : "");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_role",
                                    dataType: "json",
                                    data: {
                                        user_id: user_id,
                                        role_id: role_id
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            $("#success_message").text(RetVal.data);
                                            $("#error_message").text("");
                                            $('#roleErr').text("");
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var roleErr = JSON.parse(RetVal.data).RoleErr;

                                            if (roleErr === null) {
                                                roleErr = "";
                                            }
                                            $('#roleErr').text("");
                                            $('#roleErr').text(roleErr);
                                        }
                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE ROLE *********************************** */
                    });
                </script>
            </body>
        </html>
        <?php
    }
} else {
    header("location:index.php");
}
?>