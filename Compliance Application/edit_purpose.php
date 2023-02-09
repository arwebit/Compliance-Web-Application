<?php
$main_page = "Master";
$page = "Update Purpose";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['purpose_id']) {
        $purpose_id = $_GET['purpose_id'];
        $purpose_SQL = "SELECT * FROM mas_purpose WHERE id='$purpose_id'";
        $fetch_purpose = json_decode(ret_json_str($purpose_SQL));
        foreach ($fetch_purpose as $fetch_purposes) {
            $purpose = $fetch_purposes->purpose;
            $status = $fetch_purposes->status;
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
                                            <label for="purpose">Purpose</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="purpose" placeholder="ENTER PURPOSE" value="<?php echo $purpose; ?>" />
                                            <b class="text-danger" id="purposeErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="purpose">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="purpose_status">
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
                                        <button type="button" id="save_purpose" class="btn btn-primary mr-2">Save</button>
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

                        /* *********************************** UPDATE PURPOSE *********************************** */

                        $('#save_purpose').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var purpose_id = "<?php echo $purpose_id; ?>";
                            var purpose = $('#purpose').val();
                            var hpurpose = "<?php echo $purpose; ?>";
                            var purpose_status = $('#purpose_status').val();
                            var company = "<?php echo $login_company_id; ?>";

                            if (purpose === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#purposeErr').text(purpose === "" ? "Required" : "");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_purpose",
                                    dataType: "json",
                                    data: {
                                        purpose_id: purpose_id,
                                        user_name: user_name,
                                        purpose: purpose,
                                        hpurpose: hpurpose,
                                        purpose_status: purpose_status,
                                        company: company
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /* $("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#purposeErr').text("");
                                             $('#legislationErr').text("");*/
                                            window.location.href = "view_purposes.php";
                                        } else {
                                            $("#error_message").text(RetVal.message);
                                            $("#success_message").text("");
                                            var purposeErr = JSON.parse(RetVal.data).PurposeErr;

                                            if (purposeErr === null) {
                                                purposeErr = "";
                                            }
                                            $('#purposeErr').text("");
                                            $('#legislationErr').text("");
                                            $('#purposeErr').text(purposeErr);
                                        }
                                    }
                                });
                            }
                        });

                        /* *********************************** UPDATE PURPOSE *********************************** */
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