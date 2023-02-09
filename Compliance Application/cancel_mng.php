<?php
$main_page = "Non-statutory compliance";
$page = "Cancel non-statutory compliance";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['mng_id']) {
        $mng_id = $_GET['mng_id'];
        $mng_SQL = "SELECT * FROM mng_cmp WHERE id='$mng_id'";
        $fetch_mng = json_decode(ret_json_str($mng_SQL));
        foreach ($fetch_mng as $fetch_mngs) {
            $mngid = $fetch_mngs->mng_id;
        }
        $update_status = "1";
        $rm_status = "-1";
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
                                <div class="box-header">
                                    <?php echo $page; ?> for NSC ID : <?php echo $mngid; ?>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <form class="forms-sample">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">

                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label><b class="text-danger"> *<span  id="remarksErr"></span></b>
                                                    <input <?php echo $readonly_field; ?> type="text" class="form-control" id="remarks" tabindex="16" placeholder="ENTER REMARKS" value="<?php echo $remarks; ?>"/>
                                                    <b class="text-danger" id="remarksErr"></b>
                                                </div>
                                                <button type="button" id="mark_cancel_mng" class="btn btn-danger" tabindex="2">
                                                    Cancel</button>
                                                <b id="mng_success_message" class="text-success"></b>
                                                <b id="mng_error_message" class="text-danger"></b>
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
                        /* *********************************** MARK AS CANCEL NON STATUTORY *********************************** */

                        $('#mark_cancel_mng').click(function () {
                            //alert("Working");
                            var mng_id = "<?php echo $mng_id; ?>";
                            var rm_status = "<?php echo $rm_status; ?>";
                            var update_status = "<?php echo $update_status; ?>";
                            var remarks = $('#remarks').val().trim();
                            if (remarks === "") {
                                $("#mng_error_message").html("Provide all the fields<br/><br/>");
                                $('#remarksErr').text(remarks === "" ? "Required" : "");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?mark_cancel_mng",
                                    dataType: "json",
                                    data: {
                                        mng_id: mng_id,
                                        rm_status: rm_status,
                                        remarks: remarks,
                                        update_status: update_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            $("#mng_success_message").text(RetVal.data);
                                            $("#mng_error_message").text("");
                                            $('#remarksErr').text("");

                                        } else {
                                            $("#mng_error_message").text(RetVal.message);
                                            $("#mng_success_message").text("");

                                            var remarksErr = JSON.parse(RetVal.data).RemarksErr;

                                            if (remarksErr === null) {
                                                remarksErr = "";
                                            }
                                            $('#remarksErr').text("");
                                            $('#remarksErr').text(remarksErr);
                                        }

                                    }
                                });
                            }
                        });

                        /* *********************************** MARK AS CANCEL NON STATUTORY *********************************** */
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