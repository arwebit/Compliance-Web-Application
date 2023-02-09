<?php
$main_page = "Reports";
$page = "Statutory Compliance Status Report";
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
                            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="active"><?php echo $page; ?></li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <!-- SELECT2 EXAMPLE -->
                        <div class="box box-default">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form class="forms-sample" action="report_status_details.php" method="post">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="from_date">Due date</label><b class="text-danger"></b>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" name="sdue_date" id="reservation">
                                                </div>
                                                <!-- /.input group -->
                                            </div>  
                                            <div class="form-group">
                                                <label for="department">Department</label> <b class="text-danger"></b>
                                                <select class="form-control select2" id="department_id" name="department_id" tabindex="1" onchange="get_legislation(this.value);">
                                                    <option value="">SELECT ALL DEPARTMENTS</option>
                                                    <?php
                                                    $department_SQL = "SELECT * FROM mas_department WHERE status=1 AND company_id='$login_company_id'";
                                                    $fetch_department = json_decode(ret_json_str($department_SQL));
                                                    foreach ($fetch_department as $fetch_departments) {
                                                        ?>
                                                        <option value="<?php echo $fetch_departments->id; ?>">
                                                            <?php echo $fetch_departments->department; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="form-group">
                                                <label for="mode">Mode</label><b class="text-danger"></b>
                                                <select class="form-control select2" tabindex="5" id="mode_id" name="mode_id">
                                                    <option value="">SELECT ALL MODES</option>
                                                    <?php
                                                    $mode_SQL = "SELECT * FROM mas_mode WHERE status=1 AND company_id='$login_company_id'";
                                                    $fetch_mode = json_decode(ret_json_str($mode_SQL));
                                                    foreach ($fetch_mode as $fetch_modes) {
                                                        ?>
                                                        <option value="<?php echo $fetch_modes->id; ?>">
                                                            <?php echo $fetch_modes->mode; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <button type="submit" value="search" name="search_status_report" id="search_status_report" class="btn btn-primary mr-2" tabindex="17">
                                                Search</button> 
                                            <?php
                                            if ($_REQUEST['error']) {
                                                ?>
                                                <b class="text-danger"><?php echo $_REQUEST['error']; ?></b>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <select class="form-control select2" id="location" name="location">
                                                    <option value="">SELECT ALL LOCATIONS</option>
                                                    <?php
                                                    $location_SQL = "SELECT * FROM mas_location WHERE status=1 AND company_id='$login_company_id' ORDER BY location";
                                                    $fetch_location = json_decode(ret_json_str($location_SQL));
                                                    foreach ($fetch_location as $fetch_locations) {
                                                        ?>
                                                        <option value="<?php echo $fetch_locations->id; ?>">
                                                            <?php echo $fetch_locations->location; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="legislation">Legislation</label><b class="text-danger"> <span  id="legislationErr"></span></b>
                                                <select class="form-control select2" id="legislation_id" tabindex="2" name="legislation_id">
                                                    <option value="">SELECT ALL LEGISLATIONS</option>
                                                    <?php
                                                    $legislation_SQL = "SELECT * FROM mas_legislation WHERE status=1";
                                                    $fetch_legislation = json_decode(ret_json_str($legislation_SQL));
                                                    foreach ($fetch_legislation as $fetch_legislations) {
                                                        ?>
                                                        <option value="<?php echo $fetch_legislations->id; ?>">
                                                            <?php echo $fetch_legislations->legislation; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="activity">Activity</label><b class="text-danger"> <span  id="activityErr"></span></b>
                                                <select class="form-control select2" id="activity_id" tabindex="4" name="activity_id">
                                                    <option value="">SELECT ALL ACTIVITIES</option>
                                                    <?php
                                                    $activity_SQL = "SELECT * FROM mas_activity WHERE status=1 AND company_id='$login_company_id'";
                                                    $fetch_activity = json_decode(ret_json_str($activity_SQL));
                                                    foreach ($fetch_activity as $fetch_activities) {
                                                        ?>
                                                        <option value="<?php echo $fetch_activities->id; ?>">
                                                            <?php echo $fetch_activities->activity; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
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
                function get_legislation(department_id) {
                    if (department_id === "") {
                        $('#legislation_id').html("");
                        $('#legislation_id').html("<option value=''>SELECT ALL LEGISLATIONS</option>");
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "getAPI.php?get_legislation",
                            dataType: "json",
                            data: {
                                department_id: department_id,
                                legislation_id: ""
                            },
                            success: function (RetVal) {
                                if (RetVal.message === "Success") {
                                    var legislationData = RetVal.data;
                                    $('#legislation_id').html("");
                                    $('#legislation_id').html(legislationData);
                                } else {
                                    $('#legislation_id').html("");
                                    $('#legislation_id').html("<option value=''>SELECT ALL LEGISLATIONS</option>");
                                }
                            }
                        });
                    }
                }
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>