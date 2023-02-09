<?php
$main_page = "Master";
$page = "Add Employee";
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
                                <form class="forms-sample">

                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="uname">Username</label> <b class="text-danger"> *<span id="user_nameErr"> </span></b>
                                                <input type="text" class="form-control" id="user_name" placeholder="ENTER YOUR USERNAME" />
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email address</label> <b class="text-danger"> *<span id="mem_emailErr"> </span></b>
                                                <input type="email" class="form-control" id="mem_email" placeholder="ENTER YOUR EMAIL" />
                                            </div>
                                            <div class="form-group">
                                                <label for="designation">Designation</label> <b class="text-danger"> *<span id="mem_designationErr"></span></b>
                                                <input type="text" class="form-control" id="mem_designation" placeholder="ENTER YOUR DESIGNATION" />
                                            </div>
                                            <div class="form-group">
                                                <label for="assign_person">Second reporting officer</label> <b class="text-danger"> <span id="assign_personErr"></span></b>
                                                <select class="form-control select2" id="sreporting_officer" tabindex="17">
                                                    <option value="">SELECT REPORTING OFFICER</option>
                                                    <?php
                                                    $sro_SQL = "";
                                                    $sro_SQL .= "SELECT * FROM member_registration a INNER JOIN member_login_access b ON a.user_name=b.username ";
                                                    $sro_SQL .= "WHERE a.update_status=1 AND $ro_clause a.user_name!='admin' AND a.company_id='$login_company_id' ORDER BY a.user_name";
                                                    $fetch_sro = json_decode(ret_json_str($sro_SQL));
                                                    foreach ($fetch_sro as $fetch_sros) {
                                                        ?>
                                                        <option value="<?php echo $fetch_sros->user_name; ?>">
                                                            <?php echo $fetch_sros->mem_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="department">Department</label> <b class="text-danger">  <span  id="departmentErr"></span></b>
                                                <select class="form-control select2" id="department_id" tabindex="1" onchange="get_legislation(this.value);">
                                                    <option value="">SELECT DEPARTMENT</option>
                                                    <?php
                                                    $department_SQL = "SELECT * FROM mas_department WHERE company_id='$login_company_id' AND status=1  $dept_clause ORDER BY department";
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
                                            <button type="button" id="save_profile" class="btn btn-primary mr-2">Save</button>
                                            <b id="prof_success_message" class="text-success"></b>
                                            <b id="prof_error_message" class="text-danger"></b>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="role">Role</label> <b class="text-danger"> *<span id="mem_roleErr"></span></b>
                                                <select class="form-control select2" id="mem_role">
                                                    <?php
                                                    $role_SQL = "SELECT * FROM mas_role WHERE id>0 AND status=1 ORDER BY id";
                                                    $fetch_role = json_decode(ret_json_str($role_SQL));
                                                    foreach ($fetch_role as $fetch_roles) {
                                                        ?>
                                                        <option value="<?php echo $fetch_roles->id; ?>">
                                                            <?php echo $fetch_roles->role_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="mobile">Mobile</label> <b class="text-danger"> *<span id="mem_mobileErr"></span></b>
                                                <input type="number" min="0" class="form-control" id="mem_mobile" placeholder="ENTER YOUR MOBILE NUMBER" />
                                            </div>
                                            <div class="form-group">
                                                <label for="assign_person">First reporting officer</label> <b class="text-danger"> <span id="assign_personErr"></span></b>
                                                <select class="form-control select2" id="freporting_officer" tabindex="17">
                                                    <option value="">SELECT REPORTING OFFICER</option>
                                                    <?php
                                                    $fro_SQL = "";
                                                    $fro_SQL .= "SELECT * FROM member_registration a INNER JOIN member_login_access b ON a.user_name=b.username ";
                                                    $fro_SQL .= "WHERE a.update_status=1 AND a.company_id='$login_company_id' AND $ro_clause a.user_name!='admin' ORDER BY a.user_name";
                                                    $fetch_fro = json_decode(ret_json_str($fro_SQL));
                                                    foreach ($fetch_fro as $fetch_fros) {
                                                        ?>
                                                        <option value="<?php echo $fetch_fros->user_name; ?>">
                                                            <?php echo $fetch_fros->mem_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="assign_person">Third reporting officer</label> <b class="text-danger"> <span id="assign_personErr"></span></b>
                                                <select class="form-control select2" id="treporting_officer" tabindex="17">
                                                    <option value="">SELECT REPORTING OFFICER</option>
                                                    <?php
                                                    $tro_SQL = "";
                                                    $tro_SQL .= "SELECT * FROM member_registration a INNER JOIN member_login_access b ON a.user_name=b.username ";
                                                    $tro_SQL .= "WHERE a.update_status=1 AND a.company_id='$login_company_id' AND $ro_clause a.user_name!='admin' ORDER BY a.user_name";
                                                    $fetch_tro = json_decode(ret_json_str($tro_SQL));
                                                    foreach ($fetch_tro as $fetch_tros) {
                                                        ?>
                                                        <option value="<?php echo $fetch_tros->user_name; ?>">
                                                            <?php echo $fetch_tros->mem_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="location">Location</label><b class="text-danger"> <span id="locationErr"></span></b>
                                                <select class="form-control select2" id="mem_location">
                                                    <option value=""
                                                    <?php
                                                    if ($mem_location == "") {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>SELECT LOCATION</option>
                                                            <?php
                                                            $location_SQL = "SELECT * FROM mas_location WHERE company_id='$login_company_id' AND status=1 $location_clause ORDER BY location";
                                                            $fetch_location = json_decode(ret_json_str($location_SQL));
                                                            foreach ($fetch_location as $fetch_locations) {
                                                                ?>
                                                        <option value="<?php echo $fetch_locations->id; ?>"
                                                        <?php
                                                        if ($mem_location == $fetch_locations->id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>>
                                                                    <?php echo $fetch_locations->location; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            if ($login_role_id == -1) {
                                                ?>
                                                <div class="form-group">
                                                    <label for="company">Company</label><b class="text-danger"> *<span id="mem_companyErr"></span></b>
                                                    <select class="form-control select2" id="mem_company" >
                                                        <option value="" 
                                                        <?php
                                                        if ($company_id == "") {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>>SELECT COMPANY</option>
                                                                <?php
                                                                $company_SQL = "SELECT * FROM mas_company WHERE status=1 AND id!='20210316182915'";
                                                                $fetch_company = json_decode(ret_json_str($company_SQL));
                                                                foreach ($fetch_company as $fetch_companys) {
                                                                    ?>
                                                            <option value="<?php echo $fetch_companys->id; ?>"
                                                            <?php
                                                            if ($company_id == $fetch_companys->id) {
                                                                echo "selected='selected'";
                                                            }
                                                            ?>>
                                                                        <?php echo $fetch_companys->company_name; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <b class="text-danger" id="companyErr"></b>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <input type="hidden" class="form-control" id="mem_company" placeholder="ENTER COMPANY" value="<?php echo $login_company_id; ?>" />

                                                <?php
                                            }
                                            ?>
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

                    /* *********************************** INSERT MEMBER PROFILE *********************************** */

                    $('#save_profile').click(function () {
                        //alert("Working");
                        var login_user = "<?php echo $login_user; ?>";
                        var user_name = $('#user_name').val().trim();
                        var mem_name = user_name;
                        var mem_email = $('#mem_email').val().trim();
                        var mem_mobile = $('#mem_mobile').val().trim();
                        var mem_designation = $('#mem_designation').val().trim();
                        var mem_department = $('#department_id').val().trim();
                        var mem_fro = $('#freporting_officer').val().trim();
                        var mem_sro = $('#sreporting_officer').val().trim();
                        var mem_tro = $('#treporting_officer').val().trim();
                        var mem_role = $('#mem_role').val().trim();
                        var mem_company = $('#mem_company').val().trim();
                        var mem_location = $('#mem_location').val().trim();

                        if ((mem_company === "") || (mem_name === "") || (mem_email === "") || (mem_mobile === "") || (mem_designation === "")) {
                            $("#prof_error_message").html("Provide all the fields<br/><br/>");
                            $('#user_nameErr').text(user_name === "" ? "Required" : "");
                            $('#mem_emailErr').text(mem_email === "" ? "Required" : "");
                            $('#mem_mobileErr').text(mem_mobile === "" ? "Required" : "");
                            $('#mem_designationErr').text(mem_designation === "" ? "Required" : "");
                            $('#mem_companyErr').text(mem_company === "" ? "Required" : "");
                        }
                        if (mem_mobile < 0) {
                            $('#mem_mobileErr').text("Cannot enter negetive value");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?add_member",
                                dataType: "json",
                                data: {
                                    user_name: user_name,
                                    mem_name: mem_name,
                                    mem_email: mem_email,
                                    mem_mobile: mem_mobile,
                                    mem_designation: mem_designation,
                                    mem_department: mem_department,
                                    mem_fro: mem_fro,
                                    mem_sro: mem_sro,
                                    mem_tro: mem_tro,
                                    mem_role: mem_role,
                                    mem_location: mem_location,
                                    mem_company: mem_company,
                                    login_user: login_user
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#prof_success_message").text(RetVal.data);
                                        $("#prof_error_message").text("");
                                        $('#user_nameErr').text("");
                                        $('#mem_emailErr').text("");
                                        $('#mem_mobileErr').text("");
                                        $('#mem_designationErr').text("");
                                        $('#mem_departmentErr').text("");
                                        $('#mem_locationErr').text("");
                                        $('#mem_companyErr').val("");

                                        $('#user_name').val("");
                                        $('#mem_email').val("");
                                        $('#mem_mobile').val("");
                                        $('#mem_designation').val("");
                                        $('#mem_department').val("");
                                        $('#mem_location').val("");
                                        $('#mem_company').val("");

                                    } else {
                                        $("#prof_error_message").text(RetVal.message);
                                        $("#prof_success_message").text("");
                                        var user_nameErr = JSON.parse(RetVal.data).UsernameErr;
                                        var mem_emailErr = JSON.parse(RetVal.data).MemEmailErr;
                                        var mem_mobileErr = JSON.parse(RetVal.data).MemMobileErr;
                                        var mem_designationErr = JSON.parse(RetVal.data).MemDesignationErr;
                                        var mem_companyErr = JSON.parse(RetVal.data).CompanyErr;

                                        if (user_nameErr === null) {
                                            user_nameErr = "";
                                        }
                                        if (mem_emailErr === null) {
                                            mem_emailErr = "";
                                        }
                                        if (mem_mobileErr === null) {
                                            mem_mobileErr = "";
                                        }
                                        if (mem_designationErr === null) {
                                            mem_designationErr = "";
                                        }
                                        if (mem_companyErr === null) {
                                            mem_companyErr = "";
                                        }
                                        $('#user_nameErr').text("");
                                        $('#mem_emailErr').text("");
                                        $('#mem_mobileErr').text("");
                                        $('#mem_designationErr').text("");
                                        $('#mem_companyErr').text("");

                                        $('#user_nameErr').text(user_nameErr);
                                        $('#mem_emailErr').text(mem_emailErr);
                                        $('#mem_mobileErr').text(mem_mobileErr);
                                        $('#mem_designationErr').text(mem_designationErr);
                                        $('#mem_companyErr').text(mem_companyErr);
                                    }

                                }
                            });
                        }
                    });

                    /* *********************************** INSERT MEMBER PROFILE *********************************** */

                });
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>