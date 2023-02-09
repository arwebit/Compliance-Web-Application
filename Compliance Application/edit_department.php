<?php
$main_page = "Master";
$page = "Update Department";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['department_id']) {
        $department_id = $_GET['department_id'];
        $department_SQL = "SELECT * FROM mas_department WHERE id='$department_id'";
        $fetch_department = json_decode(ret_json_str($department_SQL));
        foreach ($fetch_department as $fetch_departments) {
            $department = $fetch_departments->department;
            $status = $fetch_departments->status;
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
                                            <label for="department">Department</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="department" placeholder="ENTER DEPARTMENT" value="<?php echo $department; ?>" />
                                            <b class="text-danger" id="departmentErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="department">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="department_status">
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
                                        <button type="button" id="save_department" class="btn btn-primary mr-2">Save</button>
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

                        $('#save_department').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var department_id = "<?php echo $department_id; ?>";
                            var department = $('#department').val();
                            var hdepartment = "<?php echo $department; ?>";
                            var department_status = $('#department_status').val();
                            var company = "<?php echo $login_company_id; ?>";
                            if (department === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#departmentErr').text(department === "" ? "Required" : "");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_department",
                                    dataType: "json",
                                    data: {
                                        department_id: department_id,
                                        user_name: user_name,
                                        department: department,
                                        hdepartment: hdepartment,
                                        company: company,
                                        department_status: department_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /*$("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#departmentErr').text("");*/
                                            window.location.href = "view_departments.php";
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var departmentErr = JSON.parse(RetVal.data).DepartmentErr;

                                            if (departmentErr === null) {
                                                departmentErr = "";
                                            }
                                            $('#departmentErr').text("");
                                            $('#departmentErr').text(departmentErr);
                                        }
                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE DEPARTMENT *********************************** */
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