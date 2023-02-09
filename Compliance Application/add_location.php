<?php
$main_page = "Master";
$page = "Add Location";
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
                                    <div class="form-group">
                                        <label for="location">Location</label><span class="text-danger"> *</span>
                                        <input type="text" class="form-control" id="location" placeholder="ENTER LOCATION">
                                        <b class="text-danger" id="locationErr"></b>
                                    </div>
                                    <button type="button" id="save_location" class="btn btn-primary mr-2">Save</button>
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

                    /* *********************************** INSERT LOCATION *********************************** */

                    $('#save_location').click(function () {
                        //alert("Working");
                        var user_name = "<?php echo $login_user; ?>";
                        var location = $('#location').val();
                        var company = "<?php echo $login_company_id; ?>";

                        if ((location === "") || (company === "")) {
                            $("#error_message").html("Provide all the fields<br/><br/>");
                            $('#locationErr').text(location === "" ? "Required" : "");
                            $('#companyErr').text(company === "" ? "Required" : "");

                        } else {
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?add_location",
                                dataType: "json",
                                data: {
                                    user_name: user_name,
                                    location: location,
                                    company: company
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#success_message").text(RetVal.data);
                                        $("#error_message").text("");
                                        $('#locationErr').text("");
                                        $('#location').val("");
                                        $('#companyErr').text("");
                                        $('#company').val("");
                                    } else {
                                        $("#error_message").text(RetVal.message);
                                        $("#success_message").text("");
                                        var locationErr = JSON.parse(RetVal.data).LocationErr;
                                        var companyErr = JSON.parse(RetVal.data).CompanyErr;
                                        if (locationErr === null) {
                                            locationErr = "";
                                        }
                                        if (companyErr === null) {
                                            companyErr = "";
                                        }
                                        $('#locationErr').text("");
                                        $('#locationErr').text(locationErr);
                                        $('#companyErr').text("");
                                        $('#companyErr').text(companyErr);
                                    }
                                }
                            });
                        }
                    });

                    /* *********************************** INSERT LOCATION *********************************** */
                });
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>