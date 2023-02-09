<?php
$main_page = "Statutory Compliance";
$page = "Mark Complete Statutory Compliance";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['rm_id']) {
        $rm_id = $_GET['rm_id'];
        $rm_SQL = "SELECT * FROM risk_management WHERE id='$rm_id'";
        $fetch_rm = json_decode(ret_json_str($rm_SQL));
        foreach ($fetch_rm as $fetch_rms) {
            $rmid = $fetch_rms->rm_id;
            $renewed_date = $renewed_date==""?"":date("Y-m-d", strtotime($fetch_rms->renewed_date));
           // $renewed_date = date("d/m/Y", strtotime(str_replace("-", "/", $fetch_rms->renewed_date)));
            $actual_cost = $fetch_rms->actual_cost;
            $purpose_value = $fetch_rms->purpose_value;
            $actual_value_covered = $fetch_rms->actual_value_covered;
        }
        $update_status = "1";
        $rm_status = "1";
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
                                <div class="box-header">
                                    <?php echo $page; ?> for SC ID : <?php echo $rmid; ?>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <form class="forms-sample">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="renew_date">Task completion date</label><b class="text-danger"> *<span id="renewDateErr"></span></b>
                                               <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                   <input type="date" class="form-control" id="renew_date" placeholder="ENTER TASK COMPLETE DATE" value="<?php echo $renewed_date;?>">
                                                </div>
                                                </div>
                                                <!--<div class="form-group">
                                                    <label for="purpose_value">Purpose value</label><span class="text-danger"> </span>
                                                    <input type="text" class="form-control" tabindex="10" id="purpose_value" placeholder="ENTER PURPOSE VALUE" value="0" />
                                                    <b class="text-danger" id="purpose_valueErr"></b>
                                                </div>-->
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label><b class="text-danger"> *<span  id="remarksErr"></span></b>
                                                    <input <?php echo $readonly_field; ?> type="text" class="form-control" id="remarks" tabindex="16" placeholder="ENTER REMARKS" value="<?php echo $remarks; ?>"/>
                                                    <b class="text-danger" id="remarksErr"></b>
                                                </div>
                                                <button type="button" id="mark_cmplt_rm" class="btn btn-success" tabindex="2">
                                                Mark complete</button>
                                            <b id="rm_success_message" class="text-success"></b>
                                            <b id="rm_error_message" class="text-danger"></b>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="actual_cost">Actual cost</label><span class="text-danger"> </span>
                                                    <input type="number" step="0.01" tabindex="11" class="form-control" id="actual_cost" placeholder="ENTER ACTUAL COST" value="<?php echo $actual_cost; ?>" />
                                                    <b class="text-danger" id="actual_costErr"></b>
                                                </div>
                                                <div class="form-group">
                                                    <label for="actual_value_covered">Actual value covered</label><span class="text-danger"> </span>
                                                    <input type="number" step="0.01" class="form-control" tabindex="12" id="actual_value_covered" placeholder="ENTER ACTUAL VALUE COVERED" value="<?php echo $actual_value_covered; ?>"/>
                                                    <b class="text-danger" id="actual_value_coveredErr"></b>
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

                    $(document).ready(function () {
                        /* *********************************** MARK AS COMPLETE RISK MANAGEMENT *********************************** */

                        $('#mark_cmplt_rm').click(function () {
                            //alert("Working");
                            var rm_id = "<?php echo $rm_id; ?>";
                            var renew_date = $('#renew_date').val().trim();
                            var rm_status = "<?php echo $rm_status; ?>";
                            var update_status = "<?php echo $update_status; ?>";
                            var actual_cost = $('#actual_cost').val().trim();
                            var actual_value_covered = $('#actual_value_covered').val().trim();
                            var remarks = $('#remarks').val().trim();
                            if ((renew_date === "")||(remarks==="")) {
                                $("#rm_error_message").html("Provide all the fields<br/><br/>");
                                $('#renewDateErr').text(renew_date === "" ? "Required" : "");
                                $('#remarksErr').text(remarks === "" ? "Required" : "");
                            } else {
                                if (actual_cost === "") {
                                    actual_cost = 0;
                                }
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?mark_complete_rm",
                                    dataType: "json",
                                    data: {
                                        rm_id: rm_id,
                                        renew_date: renew_date,
                                        rm_status: rm_status,
                                        actual_cost:actual_cost,
                                        actual_value_covered:actual_value_covered,
                                        remarks:remarks,
                                        update_status: update_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            $("#rm_success_message").text(RetVal.data);
                                            $("#rm_error_message").text("");
                                            $('#renewDateErr').text("");
                                            $('#remarksErr').text("");

                                        } else {
                                            $("#rm_error_message").text(RetVal.message);
                                            $("#rm_success_message").text("");

                                            var renew_dateErr = JSON.parse(RetVal.data).RenewDateErr;
                                            var remarksErr = JSON.parse(RetVal.data).RemarksErr;

                                            if (renew_dateErr === null) {
                                                renew_dateErr = "";
                                            }
                                            if (remarksErr === null) {
                                                remarksErr = "";
                                            }
                                            $('#renewDateErr').text("");
                                            $('#remarksErr').text("");
                                            $('#remarksErr').text(remarksErr);
                                            $('#renewDateErr').text(renew_dateErr);
                                        }

                                    }
                                });
                            }
                        });

                        /* *********************************** MARK AS COMPLETE MANAGEMENT *********************************** */
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