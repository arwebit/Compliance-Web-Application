<?php
$main_page = "Master";
$page = "Update Location";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['location_id']) {
        $location_id = $_GET['location_id'];
        $location_SQL = "SELECT * FROM mas_location WHERE id='$location_id'";
        $fetch_location = json_decode(ret_json_str($location_SQL));
        foreach ($fetch_location as $fetch_locations) {
            $location = $fetch_locations->location;
            $status = $fetch_locations->status;
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
                                            <label for="location">Location</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="location" placeholder="ENTER LOCATION" value="<?php echo $location; ?>" />
                                            <b class="text-danger" id="locationErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="location">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="location_status">
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
                                        <button type="button" id="save_location" class="btn btn-primary mr-2">Save</button>
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

                        /* *********************************** UPDATE LOCATION *********************************** */

                        $('#save_location').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var location_id = "<?php echo $location_id; ?>";
                            var location = $('#location').val();
                            var hlocation = "<?php echo $location; ?>";
                            var location_status = $('#location_status').val();
                            var company = "<?php echo $login_company_id; ?>";
                            if (location === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#locationErr').text(location === "" ? "Required" : "");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_location",
                                    dataType: "json",
                                    data: {
                                        location_id: location_id,
                                        user_name: user_name,
                                        location: location,
                                        hlocation: hlocation,
                                        location_status: location_status,
                                        company: company
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /* $("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#locationErr').text("");*/
                                            window.location.href = "view_locations.php";
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var locationErr = JSON.parse(RetVal.data).LocationErr;
                                            if (locationErr === null) {
                                                locationErr = "";
                                            }
                                            $('#locationErr').text("");
                                            $('#locationErr').text(locationErr);
                                        }
                                    }
                                });
                            }
                        });
                        /* *********************************** UPDATE LOCATION *********************************** */
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