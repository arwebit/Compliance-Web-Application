<?php
$main_page = "Master";
$page = "Update Activity";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['activity_id']) {
        $activity_id = $_GET['activity_id'];
        $activity_SQL = "SELECT * FROM mas_activity WHERE id='$activity_id'";
        $fetch_activity = json_decode(ret_json_str($activity_SQL));
        foreach ($fetch_activity as $fetch_activitys) {
            $activity = $fetch_activitys->activity;
            $status = $fetch_activitys->status;
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
                                            <label for="activity">Activity</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="activity" placeholder="ENTER ACTIVITY" value="<?php echo $activity; ?>" />
                                            <b class="text-danger" id="activityErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="activity">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="activity_status">
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
                                        <button type="button" id="save_activity" class="btn btn-primary mr-2">Save</button>
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

                        /* *********************************** UPDATE ACTIVITY *********************************** */

                        $('#save_activity').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var activity_id = "<?php echo $activity_id; ?>";
                            var activity = $('#activity').val();
                            var hactivity = "<?php echo $activity; ?>";
                            var activity_status = $('#activity_status').val();
                            var company = "<?php echo $login_company_id; ?>";
                            if (activity === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#activityErr').text(activity === "" ? "Required" : "");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_activity",
                                    dataType: "json",
                                    data: {
                                        activity_id: activity_id,
                                        user_name: user_name,
                                        activity: activity,
                                        hactivity: hactivity,
                                        activity_status: activity_status,
                                        company: company
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /*$("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#activityErr').text("");*/
                                            window.location.href = "view_activities.php";
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var activityErr = JSON.parse(RetVal.data).ActivityErr;

                                            if (activityErr === null) {
                                                activityErr = "";
                                            }
                                            $('#activityErr').text("");
                                            $('#activityErr').text(activityErr);
                                        }
                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE ACTIVITY *********************************** */
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