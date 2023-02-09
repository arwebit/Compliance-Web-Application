<?php
$main_page = "Master";
$page = "Update Mode";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['mode_id']) {
        $mode_id = $_GET['mode_id'];
        $mode_SQL = "SELECT * FROM mas_mode WHERE id='$mode_id'";
        $fetch_mode = json_decode(ret_json_str($mode_SQL));
        foreach ($fetch_mode as $fetch_modes) {
            $mode = $fetch_modes->mode;
            $status = $fetch_modes->status;
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

                            <!-- SELECT2 EXAMPLE -->
                            <div class="box box-default">
                                 <div class="box-body">
                                    <form class="forms-sample">
                                        <div class="form-group">
                                            <label for="mode">Mode</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="mode" placeholder="ENTER MODE" value="<?php echo $mode; ?>" />
                                            <b class="text-danger" id="modeErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="mode">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="mode_status">
                                                <option value="1" <?php
                                                if ($status == "1") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Active</option>
                                                <option value="0" <?php
                                                if ($status == "0") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Inactive</option>
                                            </select>
                                        </div>
                                        <button type="button" id="save_mode" class="btn btn-primary mr-2">Save</button>
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

                        /* *********************************** UPDATE MODE *********************************** */

                        $('#save_mode').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var mode_id = "<?php echo $mode_id; ?>";
                            var mode = $('#mode').val();
                            var hmode = "<?php echo $mode; ?>";
                            var mode_status = $('#mode_status').val();
                            var company = "<?php echo $login_company_id; ?>";
                            if (mode === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#modeErr').text(mode === "" ? "Required" : "");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_mode",
                                    dataType: "json",
                                    data: {
                                        mode_id: mode_id,
                                        user_name: user_name,
                                        mode: mode,
                                        hmode: hmode,
                                        mode_status: mode_status,
                                        company: company
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /*$("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#modeErr').text("");*/
                                            window.location.href = "view_modes.php";
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var modeErr = JSON.parse(RetVal.data).ModeErr;

                                            if (modeErr === null) {
                                                modeErr = "";
                                            }
                                            $('#modeErr').text("");
                                            $('#modeErr').text(modeErr);
                                        }
                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE MODE *********************************** */
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