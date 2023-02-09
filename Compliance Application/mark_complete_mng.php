<?php
$main_page = "Non-Statutory Compliance";
$page = "Mark Complete Non-Statutory Compliance";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['mng_id']) {
        $mng_id = $_GET['mng_id'];
        $mng_SQL = "SELECT * FROM mng_cmp WHERE id='$mng_id'";
        $fetch_mng = json_decode(ret_json_str($mng_SQL));
        foreach ($fetch_mng as $fetch_mngs) {
            $mngid = $fetch_mngs->mng_id;
            $renewed_date = $renewed_date==""?"":date("Y-m-d", strtotime($fetch_mngs->renewed_date));
           // $renewed_date = date("d/m/Y", strtotime(str_replace("-", "/", $fetch_rms->renewed_date)));
            $actual_transaction_value = $fetch_mngs->actual_transaction_value;
            $reference = $fetch_mngs->reference;
            $remarks = $fetch_mngs->remarks;
        }
        $update_status = "1";
        $mng_status = "1";
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
                                    <?php echo $page; ?> for NSC ID : <?php echo $mngid; ?>
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
                                                    <input type="text" class="form-control" tabindex="10" id="purpose_value" placeholder="ENTER PURPOSE VALUE" value="<?php //echo $purpose_value;   ?>" />
                                                    <b class="text-danger" id="purpose_valueErr"></b>
                                                </div>-->
                                                <div class="form-group">
                                                    <label for="actual_cost">Actual transaction value</label><span class="text-danger"> </span>
                                                    <input type="number" step="0.01" tabindex="11" class="form-control" id="actual_cost" placeholder="ENTER ACTUAL TRANSACTION value" value="<?php echo $actual_transaction_value; ?>" />
                                                    <b class="text-danger" id="actual_costErr"></b>
                                                </div>
                                                <button type="button" id="mark_cmplt_rm" class="btn btn-primary mr-2" tabindex="2">
                                                Mark complete</button>
                                            <b id="mng_success_message" class="text-success"></b>
                                            <b id="mng_error_message" class="text-danger"></b>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="reference">Reference</label><b class="text-danger"> <span  id="referenceErr"></span></b>
                                                    <input <?php echo $readonly_field; ?> type="text" class="form-control" id="reference" tabindex="16" placeholder="ENTER REFERENCE" value="<?php echo $reference; ?>"/>
                                                    <b class="text-danger" id="referenceErr"></b>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label><b class="text-danger"> *<span  id="remarksErr"></span></b>
                                                    <input <?php echo $readonly_field; ?> type="text" class="form-control" id="remarks" tabindex="16" placeholder="ENTER REMARKS" value="<?php echo $remarks; ?>"/>
                                                    <b class="text-danger" id="remarksErr"></b>
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
                        /* *********************************** MARK AS COMPLETE MANAGEMENT COMPLIANCE *********************************** */

                        $('#mark_cmplt_rm').click(function () {
                            //alert("Working");
                            var mng_id = "<?php echo $mng_id; ?>";
                            var renew_date = $('#renew_date').val().trim();
                            var mng_status = "<?php echo $mng_status; ?>";
                            var update_status = "<?php echo $update_status; ?>";
                            var actual_cost = $('#actual_cost').val().trim();
                            var remarks = $('#remarks').val().trim();
                            var reference = $('#reference').val().trim();

                            if ((renew_date === "") || (remarks === "")) {
                                $("#mng_error_message").html("Provide all the fields<br/><br/>");
                                $('#renewDateErr').text(renew_date === "" ? "Required" : "");
                                $('#remarksErr').text(remarks === "" ? "Required" : "");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?mark_complete_mng",
                                    dataType: "json",
                                    data: {
                                        mng_id: mng_id,
                                        renew_date: renew_date,
                                        mng_status: mng_status,
                                        reference: reference,
                                        actual_cost: actual_cost,
                                        remarks: remarks,
                                        update_status: update_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            $("#mng_success_message").text(RetVal.data);
                                            $("#mng_error_message").text("");
                                            $('#renewDateErr').text("");
                                            $('#remarksErr').text("");

                                        } else {
                                            $("#mng_error_message").text(RetVal.message);
                                            $("#mng_success_message").text("");

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

                        /* *********************************** MARK AS COMPLETE MANAGEMENT COMPLIANCE*********************************** */
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