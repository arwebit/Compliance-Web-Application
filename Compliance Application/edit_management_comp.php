<?php
$main_page = "Non-statutory compliance";
$page = "Edit non-statutory compliance";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['mng_id']) {
        $mng_id = $_GET['mng_id'];
        $mng_SQL = "SELECT * FROM mng_cmp WHERE id='$mng_id'";
        $fetch_mng = json_decode(ret_json_str($mng_SQL));
        foreach ($fetch_mng as $fetch_mngs) {
            $mngid = $fetch_mngs->mng_id;
            $department_id = $fetch_mngs->department_id;
            $comp_nature = $fetch_mngs->comp_nature;
            $description = $fetch_mngs->description;
            $due_date = date("Y-m-d", strtotime($fetch_mngs->due_date));
           // $due_date = date("d/m/Y", strtotime(str_replace("-", "/", $fetch_rms->due_date)));
            $budgeted_cost = $fetch_mngs->budgeted_cost;
            $remarks = $fetch_mngs->remarks;
            $reference = $fetch_mngs->reference;
            $policy_document_no = $fetch_mngs->policy_document_no;
            $assign_user = $fetch_mngs->assign_user;
            $location = $fetch_mngs->location_id;
        }
        if ($assign_user == $login_user) {
            $update_status = "2";
        } else {
            $update_status = "3";
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
                                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                                <li><a href="#"><?php echo $main_page; ?></a></li>
                                <li class="active"><?php echo $page; ?></li>
                            </ol>
                        </section>

                        <!-- Main content -->
                        <section class="content">

                            <!-- SELECT2 EXAMPLE -->
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                    <?php echo $page; ?> (NSC ID : <?php echo $mngid; ?>) 
                                    </h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <form class="forms-sample">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="department">Department</label> <b class="text-danger"> * <span  id="departmentErr"></span></b>
                                                    <select class="form-control select2" id="department_id" tabindex="1">
                                                        <option value="">SELECT DEPARTMENT</option>
                                                        <?php
                                                        $department_SQL = "SELECT * FROM mas_department WHERE status=1 AND company_id='$login_company_id' ORDER BY department";
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
                                                </div>
                                                <div class="form-group">
                                                    <label for="cmp_nature">Nature of compliance</label><b class="text-danger"> * <span  id="cmp_natureErr"></span></b>
                                                    <input type="text" class="form-control" tabindex="3" id="cmp_nature" placeholder="ENTER COMPLIANCE NATURE" value="<?php echo $comp_nature; ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="due_date">Due date </label><span class="text-danger"> *</span>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" <?php echo $readonly_field; ?> class="form-control" id="due_date" placeholder="ENTER DUE DATE" value="<?php echo $due_date; ?>">
                                                    </div>
                                                    <b class="text-danger" id="due_dateErr"></b>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label><b class="text-danger"> * <span  id="remarksErr"></span></b>
                                                    <input type="text" class="form-control" tabindex="13" id="remarks" placeholder="ENTER REMARKS" value="<?php echo $remarks; ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="budgeted_cost">Budgeted cost</label><span class="text-danger"> </span>
                                                    <input type="number" step="0.01" tabindex="9" class="form-control" id="budgeted_cost" placeholder="ENTER BUDGETED COST" value="<?php echo $budgeted_cost; ?>" />
                                                    <b class="text-danger" id="budgeted_costErr"></b>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="description">Description</label><b class="text-danger"> * <span  id="descriptionErr"></span></b>
                                                    <input type="text" class="form-control" tabindex="3" id="description" placeholder="ENTER DESCRIPTION" value="<?php echo $description; ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="pol_doc_no">Document Ref</label><b class="text-danger"> * <span  id="pol_doc_noErr"></span></b>
                                                    <input type="text" class="form-control" tabindex="13" id="pol_doc_no" placeholder="ENTER DOCUMENT REF" value="<?php echo $policy_document_no; ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="assign_person">Assignee</label> <b class="text-danger"> * <span id="assign_personErr"></span></b>
                                                    <select class="form-control select2" id="assign_person" tabindex="17">
                                                        <option value="">SELECT ASSIGNEE</option>
                                                        <?php
                                                        $assign_user_SQL = "";
                                                        $assign_user_SQL .= "SELECT * FROM member_registration a INNER JOIN member_login_access b ON a.user_name=b.username ";
                                                        $assign_user_SQL .= "WHERE a.update_status=1 AND a.company_id='$login_company_id' AND a.user_name!='admin' ORDER BY a.user_name";
                                                        $fetch_assign_user = json_decode(ret_json_str($assign_user_SQL));
                                                        foreach ($fetch_assign_user as $fetch_assign_users) {
                                                            ?>
                                                            <option value="<?php echo $fetch_assign_users->user_name; ?>"
                                                            <?php
                                                            if ($assign_user == $fetch_assign_users->user_name) {
                                                                echo "selected='selected'";
                                                            }
                                                            ?>>
                                                                        <?php echo $fetch_assign_users->mem_name; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
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
                                                                $location_SQL = "SELECT * FROM mas_location WHERE status=1 ORDER BY location";
                                                                $fetch_location = json_decode(ret_json_str($location_SQL));
                                                                foreach ($fetch_location as $fetch_locations) {
                                                                    ?>
                                                            <option value="<?php echo $fetch_locations->id; ?>"
                                                            <?php
                                                            if ($location == $fetch_locations->id) {
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
                                                    <label for="reference">Reference</label><b class="text-danger">  <span  id="referenceErr"></span></b>
                                                    <input type="text" class="form-control" tabindex="13" id="reference" placeholder="ENTER REFERENCE" value="<?php echo $reference; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <button type="button" id="save_mng_cm" class="btn btn-primary" tabindex="17">Save</button>
                                    <b id="mng_success_message" class="text-success"></b>
                                    <b id="mng_error_message" class="text-danger"></b>
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

                        /* *********************************** UPDATE MANAGEMENT COMPLIANCE *********************************** */

                        $('#save_mng_cm').click(function () {
                            var mng_id = "<?php echo $mng_id; ?>";
                            var user_name = "<?php echo $login_user; ?>";
                            var assign_person = $('#assign_person').val().trim();
                            var department_id = $('#department_id').val().trim();
                            var description = $('#description').val().trim();
                            var due_date = $('#due_date').val().trim();
                            var budgeted_cost = $('#budgeted_cost').val().trim();
                            var reference = $('#reference').val().trim();
                            var remarks = $('#remarks').val().trim();
                            var pol_doc_no = $('#pol_doc_no').val().trim();
                            var rm_status = "0";
                            var location = $('#location').val().trim();
                            var cmp_nature = $('#cmp_nature').val().trim();
                            var update_status = "<?php echo $update_status; ?>";

                            if ((location === "") || (cmp_nature === "") || (department_id === "") || (assign_person === "") || (description === "") || (remarks === "") || (due_date === "") || (pol_doc_no === "")) {
                                $("#mng_error_message").html("Provide all the fields<br/><br/>");
                                $('#assign_personErr').text(assign_person === "" ? "Required" : "");
                                $('#departmentErr').text(department_id === "" ? "Required" : "");
                                $('#descriptionErr').text(description === "" ? "Required" : "");
                                $('#due_dateErr').text(due_date === "" ? "Required" : "");
                                $('#remarksErr').text(remarks === "" ? "Required" : "");
                                $('#pol_doc_noErr').text(pol_doc_no === "" ? "Required" : "");
                                $('#cmp_natureErr').text(cmp_nature === "" ? "Required" : "");
                                $('#locationErr').text(location === "" ? "Required" : "");
                            } else {
                                if (budgeted_cost === "") {
                                    budgeted_cost = "0";
                                }
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_management_comp",
                                    dataType: "json",
                                    data: {
                                        mng_id: mng_id,
                                        user_name: user_name,
                                        assign_person: assign_person,
                                        department_id: department_id,
                                        description: description,
                                        cmp_nature: cmp_nature,
                                        due_date: due_date,
                                        remarks: remarks,
                                        budgeted_cost: budgeted_cost,
                                        reference: reference,
                                        pol_doc_no: pol_doc_no,
                                        rm_status: rm_status,
                                        location: location,
                                        update_status: update_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            $("#mng_success_message").text(RetVal.data);
                                            $('#assign_personErr').text("");
                                            $('#departmentErr').text("");
                                            $('#descriptionErr').text("");
                                            $('#comp_natureErr').text("");
                                            $('#due_dateErr').text("");
                                            $('#remarksErr').text("");
                                            $('#budgeted_costErr').text("");
                                            $('#pol_doc_noErr').text("");
                                            $('#purpose_valueErr').text("");
                                            $('#locationErr').text("");
                                            $('#renewDateErr').text("");
                                        } else {
                                            $("#mng_error_message").text(RetVal.message);
                                            $("#mng_success_message").text("");

                                            var assign_personErr = JSON.parse(RetVal.data).AssignPersonErr;
                                            var departmentErr = JSON.parse(RetVal.data).DepartmentErr;
                                            var descriptionErr = JSON.parse(RetVal.data).DescriptionErr;
                                            var comp_natureErr = JSON.parse(RetVal.data).Comp_natureErr;
                                            var due_dateErr = JSON.parse(RetVal.data).Due_dateErr;
                                            var remarksErr = JSON.parse(RetVal.data).RemarksErr;
                                            var pol_doc_noErr = JSON.parse(RetVal.data).Pol_doc_noErr;
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
                                            if (comp_natureErr === null) {
                                                comp_natureErr = "";
                                            }
                                            if (due_dateErr === null) {
                                                due_dateErr = "";
                                            }
                                            if (remarksErr === null) {
                                                remarksErr = "";
                                            }
                                            if (locationErr === null) {
                                                locationErr = "";
                                            }

                                            $('#assign_personErr').text("");
                                            $('#departmentErr').text("");
                                            $('#descriptionErr').text("");
                                            $('#comp_natureErr').text("");
                                            $('#due_dateErr').text("");
                                            $('#budgeted_costErr').text("");
                                            $('#remarksErr').text("");
                                            $('#pol_doc_noErr').text("");
                                            $('#locationErr').text("");
                                            $('#assign_personErr').text(assign_personErr);
                                            $('#departmentErr').text(departmentErr);
                                            $('#descriptionErr').text(descriptionErr);
                                            $('#comp_natureErr').text(comp_natureErr);
                                            $('#due_dateErr').text(due_dateErr);
                                            $('#remarksErr').text(remarksErr);
                                            $('#pol_doc_noErr').text(pol_doc_noErr);
                                            $('#locationErr').text(locationErr);
                                        }

                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE MANAGEMENT COMPLIANCE*********************************** */
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