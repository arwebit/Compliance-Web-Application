<?php
$main_page = "Master";
$page = "Update Legislation";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['legislation_id']) {
        $legislation_id = $_GET['legislation_id'];
        $legislation_SQL = "SELECT * FROM mas_legislation WHERE id='$legislation_id'";
        $fetch_legislation = json_decode(ret_json_str($legislation_SQL));
        foreach ($fetch_legislation as $fetch_legislations) {
            $legislation = $fetch_legislations->legislation;
            $slegislation = $fetch_legislations->short_legislation;
            $department_id = $fetch_legislations->department_id;
            $status = $fetch_legislations->status;
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
                                            <label for="legislation">Department</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="department_id">
                                                <option value="" 
                                                <?php
                                                if ($department_id == "") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>SELECT DEPARTMENT</option>
                                                        <?php
                                                        $department_SQL = "SELECT * FROM mas_department WHERE status=1 AND company_id='$login_company_id'";
                                                        $fetch_department = json_decode(ret_json_str($department_SQL));
                                                        foreach ($fetch_department as $fetch_departments) {
                                                            ?>
                                                    <option value="<?php echo $fetch_departments->id; ?>"
                                                    <?php
                                                    if ($department_id == $fetch_departments->id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>
                                                                <?php echo $fetch_departments->department; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <b class="text-danger" id="departmentErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="legislation">Legislation (Long description)</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="legislation" placeholder="ENTER LEGISLATION (LONG DESCRIPTION)" value="<?php echo $legislation; ?>" />
                                            <b class="text-danger" id="legislationErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="legislation">Legislation (Short description)</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="slegislation" placeholder="ENTER LEGISLATION (SHORT DESCRIPTION)" value="<?php echo $slegislation; ?>" />
                                            <b class="text-danger" id="slegislationErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="legislation">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="legislation_status">
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
                                        <button type="button" id="save_legislation" class="btn btn-primary mr-2">Save</button>
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

                        $('#save_legislation').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var legislation_id = "<?php echo $legislation_id; ?>";
                            var legislation = $('#legislation').val();
                            var slegislation = $('#slegislation').val();
                            var department_id = $('#department_id').val();
                            var legislation_status = $('#legislation_status').val();

                            if ((department_id === "") || (legislation === "") || (slegislation === "")) {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#departmentErr').text(department_id === "" ? "Required" : "");
                                $('#legislationErr').text(legislation === "" ? "Required" : "");
                                $('#slegislationErr').text(slegislation === "" ? "Required" : "");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_legislation",
                                    dataType: "json",
                                    data: {
                                        legislation_id: legislation_id,
                                        user_name: user_name,
                                        department_id: department_id,
                                        legislation: legislation,
                                        slegislation: slegislation,
                                        legislation_status: legislation_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /*$("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#legislationErr').text("");
                                             $('#departmentErr').text("");*/
                                            window.location.href = "view_legislations.php";
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var legislationErr = JSON.parse(RetVal.data).LegislationErr;
                                            var departmentErr = JSON.parse(RetVal.data).DepartmentErr;
                                            var slegislationErr = JSON.parse(RetVal.data).ShortLegislationErr;
                                            if (departmentErr === null) {
                                                departmentErr = "";
                                            }
                                            if (legislationErr === null) {
                                                legislationErr = "";
                                            }
                                            if (slegislationErr === null) {
                                                slegislationErr = "";
                                            }
                                            $('#legislationErr').text("");
                                            $('#departmentErr').text("");
                                            $('#slegislationErr').text("");
                                            $('#slegislationErr').text(slegislationErr);
                                            $('#legislationErr').text(legislationErr);
                                            $('#departmentErr').text(departmentErr);
                                        }
                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE LEGISLATION *********************************** */
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