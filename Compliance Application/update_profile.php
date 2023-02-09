<?php
$main_page = "Profile";
$page = "Update profile";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    $mem_pro_SQL = "SELECT * FROM member_registration WHERE user_name='$login_user'";
    $fetch_mem_pro = json_decode(ret_json_str($mem_pro_SQL));
    foreach ($fetch_mem_pro as $fetch_mem_pros) {
        $mem_name = $fetch_mem_pros->mem_name;
        $mem_email = $fetch_mem_pros->email;
        $mem_mobile = $fetch_mem_pros->mobile == "0" ? "" : $fetch_mem_pros->mobile;
        $mem_designation = $fetch_mem_pros->designation;
        $mem_department = $fetch_mem_pros->department == "0" ? "" : $fetch_mem_pros->department;
        $mem_location = $fetch_mem_pros->location == "0" ? "" : $fetch_mem_pros->location;
    }

    $rep_off_pro_SQL = "SELECT * FROM mem_reporting_officer WHERE user_name='$login_user'";
    $fetch_rep_off_pro = json_decode(ret_json_str($rep_off_pro_SQL));
    foreach ($fetch_rep_off_pro as $fetch_rep_off_pros) {
        $rep_off1 = $fetch_rep_off_pros->first_officer;
        $rep_off2 = $fetch_rep_off_pros->second_officer;
        $rep_off3 = $fetch_rep_off_pros->third_officer;
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
                            <?php echo $main_page; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="active"><?php echo $main_page; ?></li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <!-- SELECT2 EXAMPLE -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title">Update profile</h3>
                                    </div>
                                    <div class="box-body">
                                        <form class="forms-sample">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="designation">Designation</label> <b class="text-danger"> <span id="mem_designationErr"></span></b>
                                                        <input type="text" class="form-control" id="mem_designation" placeholder="ENTER YOUR DESIGNATION" value="<?php echo $mem_designation; ?>" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email address</label> <b class="text-danger"> *<span id="mem_emailErr"> </span></b>
                                                        <input type="email" class="form-control" id="mem_email" placeholder="ENTER YOUR EMAIL" value="<?php echo $mem_email; ?>" />
                                                        <input type="hidden" class="form-control" id="mem_hemail" placeholder="ENTER YOUR EMAIL" value="<?php echo $mem_email; ?>" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="location">Location</label><b class="text-danger"> <span id="mem_locationErr"></span></b>
                                                        <select class="form-control select2" id="mem_location">
                                                            <option value=""
                                                            <?php
                                                            if ($mem_location == "") {
                                                                echo "selected='selected'";
                                                            }
                                                            ?>>SELECT LOCATION</option>
                                                                    <?php
                                                                    $location_SQL = "SELECT * FROM mas_location WHERE status=1 AND company_id='$login_company_id'";
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
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="department">Department</label> <b class="text-danger"> <span id="mem_departmentErr"></span></b>
                                                        <select class="form-control select2" id="mem_department">
                                                            <option value=""
                                                            <?php
                                                            if ($mem_department == "") {
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
                                                                if ($mem_department == $fetch_departments->id) {
                                                                    echo "selected='selected'";
                                                                }
                                                                ?>>
                                                                            <?php echo $fetch_departments->department; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mobile">Mobile</label> <b class="text-danger"> *<span id="mem_mobileErr"></span></b>
                                                        <input type="number" min="0" class="form-control" id="mem_mobile" placeholder="ENTER YOUR MOBILE NUMBER" value="<?php echo $mem_mobile; ?>" />
                                                        <input type="hidden" class="form-control" id="mem_hmobile" placeholder="ENTER YOUR MOBILE NUMBER" value="<?php echo $mem_mobile; ?>" />
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                        <button type="button" id="save_profile" class="btn btn-primary mr-2">Save</button>
                                        <b id="prof_success_message" class="text-success"></b>
                                        <b id="prof_error_message" class="text-danger"></b>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col-xs-12 -->
                            <div class="col-xs-12">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title">Reporting officers</h3>
                                    </div>
                                    <div class="box-body">
                                        <form class="forms-sample">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="first_officer">First officer</label> <b class="text-danger"> * <span id="officerErr"></span></b>
                                                        <select  class="form-control select2" id="first_officer" tabindex="1">
                                                            <option value="">SELECT FIRST OFFICER</option>
                                                            <?php
                                                            $fo_SQL = "SELECT * FROM member_registration WHERE update_status=1 AND company_id='$login_company_id' AND user_name!='admin' ";
                                                            $fetch_fo = json_decode(ret_json_str($fo_SQL));
                                                            foreach ($fetch_fo as $fetch_fos) {
                                                                ?>
                                                                <option value="<?php echo $fetch_fos->user_name; ?>"
                                                                <?php
                                                                if ($rep_off1 == $fetch_fos->user_name) {
                                                                    echo "selected='selected'";
                                                                }
                                                                ?>>
                                                                            <?php echo $fetch_fos->mem_name; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="third_officer">Third officer</label> 
                                                        <select  class="form-control select2" id="third_officer" tabindex="3">
                                                            <option value="">SELECT THIRD OFFICER</option>
                                                            <?php
                                                            $to_SQL = "SELECT * FROM member_registration WHERE update_status=1 AND company_id='$login_company_id' AND user_name!='admin'";
                                                            $fetch_to = json_decode(ret_json_str($to_SQL));
                                                            foreach ($fetch_to as $fetch_tos) {
                                                                ?>
                                                                <option value="<?php echo $fetch_tos->user_name; ?>"
                                                                <?php
                                                                if ($rep_off3 == $fetch_tos->user_name) {
                                                                    echo "selected='selected'";
                                                                }
                                                                ?>>
                                                                            <?php echo $fetch_tos->mem_name; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="second_officer">Second officer</label> 
                                                        <select  class="form-control select2" id="second_officer" tabindex="2">
                                                            <option value="">SELECT SECOND OFFICER</option>
                                                            <?php
                                                            $so_SQL = "SELECT * FROM member_registration WHERE update_status=1 AND company_id='$login_company_id' AND user_name!='admin'";
                                                            $fetch_so = json_decode(ret_json_str($so_SQL));
                                                            foreach ($fetch_so as $fetch_sos) {
                                                                ?>
                                                                <option value="<?php echo $fetch_sos->user_name; ?>"
                                                                <?php
                                                                if ($rep_off2 == $fetch_sos->user_name) {
                                                                    echo "selected='selected'";
                                                                }
                                                                ?>>
                                                                            <?php echo $fetch_sos->mem_name; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <button type="button" id="save_rep_off" class="btn btn-primary mr-2">Save</button>
                                        <b id="off_success_message" class="text-success"></b>
                                        <b id="off_error_message" class="text-danger"></b>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col-xs-12 -->
                        </div>
                        <!-- /.row-->
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

                    /* *********************************** UPDATE MEMBER PROFILE *********************************** */

                    $('#save_profile').click(function () {
                        //alert("Working");
                        var user_name = "<?php echo $login_user; ?>";
                        var mem_email = $('#mem_email').val().trim();
                        var mem_hemail = $('#mem_hemail').val().trim();
                        var mem_mobile = $('#mem_mobile').val().trim();
                        var mem_hmobile = $('#mem_hmobile').val().trim();
                        var mem_designation = $('#mem_designation').val().trim();
                        var mem_department = $('#mem_department').val().trim();
                        var mem_location = $('#mem_location').val().trim();

                        if ((mem_email === "") || (mem_mobile === "")) {
                            $("#prof_error_message").html("Provide all the fields<br/><br/>");
                            $('#mem_emailErr').text(mem_email === "" ? "Required" : "");
                            $('#mem_mobileErr').text(mem_mobile === "" ? "Required" : "");
                                                  }
                        if (mem_mobile < 0) {
                            $('#mem_mobileErr').text("Cannot enter negetive value");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?edit_member",
                                dataType: "json",
                                data: {
                                    user_name: user_name,
                                    mem_email: mem_email,
                                    mem_hemail: mem_hemail,
                                    mem_mobile: mem_mobile,
                                    mem_hmobile: mem_hmobile,
                                    mem_designation: mem_designation,
                                    mem_department: mem_department,
                                    mem_location: mem_location
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#prof_success_message").text(RetVal.data);
                                        $("#prof_error_message").text("");
                                        $('#mem_emailErr').text("");
                                        $('#mem_mobileErr').text("");
                                        $('#mem_hemail').val(mem_email);
                                        $('#mem_hmobile').val(mem_mobile);

                                    } else {
                                        $("#prof_error_message").text(RetVal.message);
                                        $("#prof_success_message").text("");
                                        var mem_emailErr = JSON.parse(RetVal.data).MemEmailErr;
                                        var mem_mobileErr = JSON.parse(RetVal.data).MemMobileErr;

                                        if (mem_emailErr === null) {
                                            mem_emailErr = "";
                                        }
                                        if (mem_mobileErr === null) {
                                            mem_mobileErr = "";
                                        }
                                        $('#mem_emailErr').text("");
                                        $('#mem_mobileErr').text("");

                                        $('#mem_emailErr').text(mem_emailErr);
                                        $('#mem_mobileErr').text(mem_mobileErr);
                                    }

                                }
                            });
                        }
                    });

                    /* *********************************** UPDATE MEMBER PROFILE *********************************** */

                    /* *********************************** UPDATE REPORTING OFFICER *********************************** */

                    $('#save_rep_off').click(function () {
                        //alert("Working");
                        var user_name = "<?php echo $login_user; ?>";
                        var rep_officer1 = $('#first_officer').val().trim();
                        var rep_officer2 = $('#second_officer').val().trim();
                        var rep_officer3 = $('#third_officer').val().trim();

                        if (rep_officer1 === "") {
                            $("#off_error_message").html("Provide all the fields<br/><br/>");
                            $('#officerErr').text(rep_officer1 === "" ? "Required" : "");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?reporting_officer",
                                dataType: "json",
                                data: {
                                    user_name: user_name,
                                    rep_officer1: rep_officer1,
                                    rep_officer2: rep_officer2,
                                    rep_officer3: rep_officer3,
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#off_success_message").text(RetVal.data);
                                        $("#off_error_message").text("");
                                        $('#officerErr').text("");
                                    } else {
                                        $("#off_error_message").text(RetVal.message);
                                        $("#off_success_message").text("");
                                        var officerErr = JSON.parse(RetVal.data).OffErr;

                                        if (officerErr === null) {
                                            officerErr = "";
                                        }
                                        $('#officerErr').text("");
                                        $('#officerErr').text(officerErr);
                                    }

                                }
                            });
                        }
                    });

                    /* *********************************** UPDATE REPORTING OFFICER *********************************** */
                });
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>