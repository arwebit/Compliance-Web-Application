<?php
$main_page = "Master";
$page = "Add company";
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
                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li><a href="#"><?php echo $main_page; ?></a></li>
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
                                    <div class="form-group">
                                        <label for="company">Company</label><span class="text-danger"> *</span>
                                        <input type="text" class="form-control" id="company" placeholder="ENTER COMPANY">
                                        <b class="text-danger" id="companyErr"></b>
                                    </div>
                                    <button type="button" id="save_company" class="btn btn-primary mr-2">Save</button>
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

                    /* *********************************** INSERT COMPANY *********************************** */

                    $('#save_company').click(function () {
                        //alert("Working");
                        var user_name = "<?php echo $login_user; ?>";
                        var company = $('#company').val();

                        if (company === "") {
                            $("#error_message").html("Provide all the fields<br/><br/>");
                            $('#companyErr').text(company === "" ? "Required" : "");

                        } else {
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?add_company",
                                dataType: "json",
                                data: {
                                    user_name: user_name,
                                    company: company
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#success_message").text(RetVal.data);
                                        $("#error_message").text("");
                                        $('#companyErr').text("");
                                        $('#company').val("");
                                    } else {
                                        $("#error_message").text(RetVal.message);
                                        $("#success_message").text("");
                                        var companyErr = JSON.parse(RetVal.data).CompanyErr;

                                        if (companyErr === null) {
                                            companyErr = "";
                                        }
                                        $('#companyErr').text("");
                                        $('#companyErr').text(companyErr);
                                    }
                                }
                            });
                        }
                    });

                    /* *********************************** INSERT COMPANY *********************************** */
                });
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>