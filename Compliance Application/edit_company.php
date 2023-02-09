<?php
$main_page = "Master";
$page = "Update company";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['company_id']) {
        $company_id = $_GET['company_id'];
        $company_SQL = "SELECT * FROM mas_company WHERE id='$company_id'";
        $fetch_company = json_decode(ret_json_str($company_SQL));
        foreach ($fetch_company as $fetch_companys) {
            $company = $fetch_companys->company_name;
            $status = $fetch_companys->status;
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
                                <div class="box-body">
                                    <form class="forms-sample">
                                        <div class="form-group">
                                            <label for="company">Company</label><span class="text-danger"> *</span>
                                            <input type="text" class="form-control" id="company" placeholder="ENTER DEPARTMENT" value="<?php echo $company; ?>" />
                                            <b class="text-danger" id="companyErr"></b>
                                        </div>
                                        <div class="form-group">
                                            <label for="company">Status</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" id="company_status">
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

                        /* *********************************** UPDATE COMPANY *********************************** */

                        $('#save_company').click(function () {
                            //alert("Working");
                            var user_name = "<?php echo $login_user; ?>";
                            var company_id = "<?php echo $company_id; ?>";
                            var company = $('#company').val();
                            var company_status = $('#company_status').val();

                            if (company === "") {
                                $("#error_message").html("Provide all the fields<br/><br/>");
                                $('#companyErr').text(company === "" ? "Required" : "");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "getAPI.php?edit_company",
                                    dataType: "json",
                                    data: {
                                        company_id: company_id,
                                        user_name: user_name,
                                        company: company,
                                        company_status: company_status
                                    },
                                    success: function (RetVal) {
                                        if (RetVal.message === "Success") {
                                            /*$("#success_message").text(RetVal.data);
                                             $("#error_message").text("");
                                             $('#companyErr').text("");*/
                                            window.location.href = "view_company.php";
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

                        /* *********************************** UPDATE COMPANY *********************************** */
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