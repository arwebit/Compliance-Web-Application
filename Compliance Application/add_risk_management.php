<?php
$main_page = "Statutory Compliance";
$page = "Add Statutory Compliance";
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
                                                <label for="department">Department</label> <b class="text-danger"> * <span  id="departmentErr"></span></b>
                                                <select class="form-control select2" id="department_id" tabindex="1" onchange="get_legislation(this.value);">
                                                    <option value="">SELECT DEPARTMENT</option>
                                                    <?php
                                                    $department_SQL = "SELECT * FROM mas_department WHERE status=1 AND company_id='$login_company_id' ORDER BY department";
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
                                                <label for="mode">Mode</label><b class="text-danger"> * <span  id="modeErr"></span></b>
                                                <select class="form-control select2" tabindex="5" id="mode_id">
                                                    <option value="">SELECT MODE</option>
                                                    <?php
                                                    $mode_SQL = "SELECT * FROM mas_mode WHERE status=1 AND company_id='$login_company_id' ORDER BY mode";
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
                                            <div class="form-group">
                                                <label for="due_date">Due date </label><b class="text-danger"> * <span  id="due_dateErr"></span></b>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="date" class="form-control" id="due_date" placeholder="ENTER DUE DATE">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="purpose">Purpose</label><b class="text-danger"> * <span  id="purposeErr"></span></b>
                                                <select class="form-control select2" id="purpose_id" tabindex="6" onchange="togg_purpose(this.value);">
                                                    <option value="">SELECT PURPOSE</option>
                                                    <?php
                                                    $purpose_SQL = "SELECT * FROM mas_purpose WHERE status=1 AND company_id='$login_company_id' ORDER BY purpose";
                                                    $fetch_purpose = json_decode(ret_json_str($purpose_SQL));
                                                    foreach ($fetch_purpose as $fetch_purposes) {
                                                        ?>
                                                        <option value="<?php echo $fetch_purposes->id; ?>">
                                                            <?php echo $fetch_purposes->purpose; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                    <option value="-1">Others</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="location">Location</label><b class="text-danger"> *<span id="locationErr"></span></b>
                                                <select class="form-control select2" id="location">
                                                    <option value=""
                                                    <?php
                                                    if ($mem_location == "") {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>SELECT LOCATION</option>
                                                            <?php
                                                            $location_SQL = "SELECT * FROM mas_location WHERE status=1 AND company_id='$login_company_id' ORDER BY location";
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
                                            <div class="form-group">
                                                <label for="description">Description</label><b class="text-danger">  <span  id="descriptionErr"></span></b>
                                                <input type="text" class="form-control" tabindex="3" id="description" placeholder="ENTER DESCRIPTION" />
                                            </div>
                                            <div class="form-group">
                                                <label for="budgeted_cost">Budgeted cost</label><b class="text-danger">  <span  id="budgeted_costErr"></span></b>
                                                <input type="number" step="0.01" tabindex="9" class="form-control" id="budgeted_cost" placeholder="ENTER BUDGETED COST" />
                                            </div>

                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="legislation">Legislation</label><b class="text-danger"> * <span  id="legislationErr"></span></b>
                                                <select class="form-control select2" id="legislation_id" tabindex="2">
                                                    <option value="">SELECT LEGISLATION</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="activity">Activity</label><b class="text-danger"> * <span  id="activityErr"></span></b>
                                                <select class="form-control select2" id="activity_id" tabindex="4">
                                                    <option value="">SELECT ACTIVITY</option>
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
                                            <div class="form-group">
                                                <label for="assign_person">Assignee</label> <b class="text-danger"> * <span id="assign_personErr"></span></b>
                                                <select class="form-control select2" id="assign_person" tabindex="17">
                                                    <option value="">SELECT ASSIGNEE</option>
                                                    <?php
                                                    $assign_user_SQL = "";
                                                    $assign_user_SQL .= "SELECT * FROM member_registration a INNER JOIN member_login_access b ON a.user_name=b.username ";
                                                    $assign_user_SQL .= "WHERE a.update_status=1 AND company_id='$login_company_id' AND a.user_name!='admin' ORDER BY a.user_name";
                                                    $fetch_assign_user = json_decode(ret_json_str($assign_user_SQL));
                                                    foreach ($fetch_assign_user as $fetch_assign_users) {
                                                        ?>
                                                        <option value="<?php echo $fetch_assign_users->user_name; ?>">
                                                            <?php echo $fetch_assign_users->mem_name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="purpose_others">Purpose (Others)</label><b class="text-danger">  <span  id="purpose_othersErr"></span></b>
                                                <input type="text" class="form-control" tabindex="3" id="purpose_others" placeholder="ENTER PURPOSE" disabled="disabled"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="reference">Reference</label><b class="text-danger"> <span  id="referenceErr"></span></b>
                                                <input type="text" class="form-control" id="reference" tabindex="14" placeholder="ENTER REFERENCE" />
                                            </div>
                                            <div class="form-group">
                                                <label for="pol_doc_no">Document Ref No</label><b class="text-danger"> <span  id="pol_doc_noErr"></span></b>
                                                <input type="text" class="form-control" tabindex="13" id="pol_doc_no" placeholder="ENTER DOCUMENT REF NO" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <button type="button" id="save_rm" class="btn btn-primary mr-2" tabindex="17">Save</button>
                                <b id="rm_success_message" class="text-success"></b>
                                <b id="rm_error_message" class="text-danger"></b>
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
                        $('#legislation_id').html("<option value=''>SELECT LEGISLATION</option>");
                        $('#purpose_id').html("");
                        $('#purpose_id').html("<option value=''>SELECT PURPOSE</option>");
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
                                    $('#legislation_id').html(legislationData);
                                } else {
                                    $('#legislation_id').html("");
                                    $('#legislation_id').html("<option value=''>SELECT LEGISLATION</option>");
                                }
                            }
                        });
                    }
                }
                function togg_purpose(purpose_val) {
                    if (purpose_val === "") {
                        $('#purpose_others').val("");
                        document.getElementById("purpose_others").disabled = true;
                    } else {
                        if (purpose_val === "-1") {
                            document.getElementById("purpose_others").disabled = false;
                        } else {
                            $('#purpose_others').val("");
                            document.getElementById("purpose_others").disabled = true;
                        }
                    }
                }
                $(document).ready(function () {

                    /* *********************************** INSERT RISK MANAGEMENT *********************************** */

                    $('#save_rm').click(function () {
                        //alert("Working");
                        var user_name = "<?php echo $login_user; ?>";
                        var assign_person = $('#assign_person').val().trim();
                        var department_id = $('#department_id').val().trim();
                        var description = $('#description').val().trim();
                        var mode_id = $('#mode_id').val().trim();
                        var due_date = $('#due_date').val().trim();
                        var budgeted_cost = $('#budgeted_cost').val().trim();
                        var reference = $('#reference').val().trim();
                        var pol_doc_no = $('#pol_doc_no').val().trim();
                        var rm_status = "0";
                        var legislation_id = $('#legislation_id').val().trim();
                        var activity_id = $('#activity_id').val().trim();
                        var purpose_id = $('#purpose_id').val().trim();
                        var purpose_others = $('#purpose_others').val().trim();
                        var location = $('#location').val().trim();
                        var company = "<?php echo $login_company_id; ?>";
                        if ((location === "") || (department_id === "") || (assign_person === "") || (mode_id === "") || (due_date === "") || (legislation_id === "") || (activity_id === "") || (purpose_id === "")) {
                            $("#rm_error_message").html("Provide all the fields<br/><br/>");
                            $('#assign_personErr').text(assign_person === "" ? "Required" : "");
                            $('#departmentErr').text(department_id === "" ? "Required" : "");
                            $('#modeErr').text(mode_id === "" ? "Required" : "");
                            $('#due_dateErr').text(due_date === "" ? "Required" : "");
                            $('#legislationErr').text(legislation_id === "" ? "Required" : "");
                            $('#activityErr').text(activity_id === "" ? "Required" : "");
                            $('#purposeErr').text(purpose_id === "" ? "Required" : "");
                            $('#locationErr').text(location === "" ? "Required" : "");
                        } else {
                            if (pol_doc_no === "") {
                                pol_doc_no = "---";
                            }
                            if (reference === "") {
                                reference = "---";
                            }
                            if (description === "") {
                                description = "---";
                            }
                            if (budgeted_cost === "") {
                                budgeted_cost = "0";
                            }
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?add_risk_management",
                                dataType: "json",
                                data: {
                                    user_name: user_name,
                                    assign_person: assign_person,
                                    department_id: department_id,
                                    description: description,
                                    mode_id: mode_id,
                                    due_date: due_date,
                                    budgeted_cost: budgeted_cost,
                                    reference: reference,
                                    pol_doc_no: pol_doc_no,
                                    rm_status: rm_status,
                                    legislation_id: legislation_id,
                                    activity_id: activity_id,
                                    purpose_id: purpose_id,
                                    location: location,
                                    purpose_others: purpose_others,
                                    company: company
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#rm_success_message").text(RetVal.data);
                                        $("#rm_error_message").text("");
                                        $('#assign_personErr').text("");
                                        $('#departmentErr').text("");
                                        $('#descriptionErr').text("");
                                        $('#modeErr').text("");
                                        $('#due_dateErr').text("");
                                        $('#budgeted_costErr').text("");
                                        $('#referenceErr').text("");
                                        $('#pol_doc_noErr').text("");
                                        $('#legislationErr').text("");
                                        $('#activityErr').text("");
                                        $('#purposeErr').text("");
                                        $('#locationErr').text("");
                                        $('#remarksErr').text("");
                                        $('#purpose_valueErr').text("");
                                        $('#renewDateErr').text("");
                                        $('#department_id').val("");
                                        $('#description').val("");
                                        $('#mode_id').val("");
                                        $('#renew_date').val("");
                                        $('#due_date').val("");
                                        $('#budgeted_cost').val("");
                                        $('#reference').val("");
                                        $('#pol_doc_no').val("");
                                        $('#legislation_id').val("");
                                        $('#activity_id').val("");
                                        $('#purpose_others').val("");
                                        $('#purpose_id').val("");
                                        $('#location').val("");

                                    } else {
                                        $("#rm_error_message").text(RetVal.message);
                                        $("#rm_success_message").text("");

                                        var assign_personErr = JSON.parse(RetVal.data).AssignPersonErr;
                                        var departmentErr = JSON.parse(RetVal.data).DepartmentErr;
                                        var descriptionErr = JSON.parse(RetVal.data).DescriptionErr;
                                        var modeErr = JSON.parse(RetVal.data).ModeErr;
                                        var due_dateErr = JSON.parse(RetVal.data).Due_dateErr;
                                        var budgeted_costErr = JSON.parse(RetVal.data).Budgeted_costErr;
                                        var referenceErr = JSON.parse(RetVal.data).ReferenceErr;
                                        var pol_doc_noErr = JSON.parse(RetVal.data).Pol_doc_noErr;
                                        var legislationErr = JSON.parse(RetVal.data).LegislationErr;
                                        var activityErr = JSON.parse(RetVal.data).ActivityErr;
                                        var purposeErr = JSON.parse(RetVal.data).PurposeErr;
                                        var locationErr = JSON.parse(RetVal.data).LocationErr;
                                        if (assign_personErr === null) {
                                            assign_personErr = "";
                                        }
                                        if (departmentErr === null) {
                                            departmentErr = "";
                                        }
                                        if (descriptionErr === null) {
                                            descriptionErr = "";
                                        }
                                        if (modeErr === null) {
                                            modeErr = "";
                                        }
                                        if (due_dateErr === null) {
                                            due_dateErr = "";
                                        }
                                        if (budgeted_costErr === null) {
                                            budgeted_costErr = "";
                                        }
                                        if (referenceErr === null) {
                                            referenceErr = "";
                                        }
                                        if (pol_doc_noErr === null) {
                                            pol_doc_noErr = "";
                                        }
                                        if (legislationErr === null) {
                                            legislationErr = "";
                                        }
                                        if (activityErr === null) {
                                            activityErr = "";
                                        }
                                        if (purposeErr === null) {
                                            purposeErr = "";
                                        }
                                        if (locationErr === null) {
                                            locationErr = "";
                                        }
                                        $('#assign_personErr').text("");
                                        $('#departmentErr').text("");
                                        $('#descriptionErr').text("");
                                        $('#modeErr').text("");
                                        $('#due_dateErr').text("");
                                        $('#budgeted_costErr').text("");
                                        $('#referenceErr').text("");
                                        $('#pol_doc_noErr').text("");
                                        $('#legislationErr').text("");
                                        $('#activityErr').text("");
                                        $('#purposeErr').text("");
                                        $('#locationErr').text("");
                                        $('#assign_personErr').text(assign_personErr);
                                        $('#departmentErr').text(departmentErr);
                                        $('#descriptionErr').text(descriptionErr);
                                        $('#modeErr').text(modeErr);
                                        $('#due_dateErr').text(due_dateErr);
                                        $('#budgeted_costErr').text(budgeted_costErr);
                                        $('#referenceErr').text(referenceErr);
                                        $('#pol_doc_noErr').text(pol_doc_noErr);
                                        $('#legislationErr').text(legislationErr);
                                        $('#activityErr').text(activityErr);
                                        $('#purposeErr').text(purposeErr);
                                        $('#locationErr').text(locationErr);
                                    }

                                }
                            });
                        }
                    });

                    /* *********************************** INSERT RISK MANAGEMENT *********************************** */
                });
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>