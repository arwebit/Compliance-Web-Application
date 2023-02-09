<?php
$main_page = "Upload File";
$page = "Upload Statutory Files";
include './getAPI.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    $roSQL = "";
    $roSQL .= "SELECT * FROM mem_reporting_officer WHERE first_officer='$login_user' OR ";
    $roSQL .= "second_officer='$login_user' OR third_officer='$login_user'";
    $fetch_ro = json_decode(ret_json_str($roSQL));
    foreach ($fetch_ro as $fetch_ros) {
        $user[] = $fetch_ros->user_name;
    }
    $users = "'" . implode("', '", $user) . "'";
    $logClause = "($users,'$login_user')";
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
                                <form action="" class="forms-sample" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="uploaded_by" id="uploaded_by" value="<?php echo $login_user; ?>" />
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="scid">Statutory compliance id</label><span class="text-danger"> *</span>
                                                <input type="text" class="form-control" required="required" name="sc_id" id="sc_id" placeholder="ENTER STATUTORY COMPLIANCE ID">
                                                <b class="text-danger"><?php echo $scidErr; ?></b>
                                            </div>
                                            <div class="form-group">
                                                <label for="document_no">Document no.</label><span class="text-danger"> </span>
                                                <input type="text" class="form-control" name="document_no" id="document_no" placeholder="ENTER DOCUMENT NO">
                                                <b class="text-danger"></b>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="file_xl">Choose file</label><span class="text-danger"> *</span>
                                                <input type="file" id="data_upload" required="required" name="statutory_data_upload[]" class="form-control" />
                                                <b class="text-danger"><?php echo $stat_file_dataErr; ?></b>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label><span class="text-danger"> </span>
                                                <textarea id="description" name="description" class="form-control"></textarea>
                                                <b class="text-danger"></b>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" id="upload_statutory_files" name="upload_statutory_files" class="btn btn-primary mr-2">Upload</button>
                                    <b class="<?php if ($stat_up_err == true) { ?> text-danger <?php } else { ?> text-success <?php } ?>">
                                        <?php
                                        if ($stat_up_err == true) {
                                            echo $stat_up_message;
                                        } else {
                                            echo $stat_up_data;
                                        }
                                        ?>
                                    </b>
                                </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                        <div class="box box-default">
                            <div class="box-header">
                                <h3 class="box-title">Uploaded Files</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style='font-weight:bold;'>SC ID</th>
                                            <th style='font-weight:bold;'>Description</th>
                                            <th style='font-weight:bold;'>Document no</th>
                                            <th style='font-weight:bold;'>Uploaded By</th>
                                            <th style='font-weight:bold;'>Uploaded Date</th>
                                            <th style='font-weight:bold;'>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $link = "risk_management_details.php?link=upload_statutory_file.php&&rm_id=";
                                        $upload_SQL = "";
                                        $upload_SQL .= "SELECT a.id as file_id, a.description, b.id as scid, a.document_no, b.rm_id, a.compliance_type, a.file_data, a.file_data_extension, ";
                                        $upload_SQL .= "a.upload_by, a.upload_date FROM upload_document_files a INNER JOIN risk_management b ON a.non_stat_stat_id=b.id ";
                                        $upload_SQL .= "WHERE a.compliance_type='SC' AND a.upload_by IN $logClause AND a.status=1 ORDER BY a.upload_date DESC";
                                        $fetch_upload = json_decode(ret_json_str($upload_SQL));
                                        foreach ($fetch_upload as $fetch_uploads) {
                                            $scid = $fetch_uploads->scid;
                                            $file_id = $fetch_uploads->file_id;
                                            $statutory_id = $fetch_uploads->rm_id;
                                            $description = $fetch_uploads->description;
                                            $uploaded_by = $fetch_uploads->upload_by;
                                            $uploaded_date = $fetch_uploads->upload_date;
                                            $document_no = $fetch_uploads->document_no;
                                            $stat_file_data = $fetch_uploads->file_data;
                                            $stat_file_data_extension = $fetch_uploads->file_data_extension;
                                            $stat_file_data_loc = $statutory_file_dir . str_replace(" ", "_", $file_id) . "." . $stat_file_data_extension;
                                            $stat_files = fopen($stat_file_data_loc, "w+");
                                            fwrite($stat_files, base64_decode($stat_file_data));
                                            fclose($stat_files);
                                            ?>
                                            <tr>
                                                <td style="font-weight: bolder;"><a href="<?php echo $link . $scid; ?>">
                                                        <?php echo $statutory_id; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo nl2br($description); ?></td>
                                                <td><?php echo $document_no; ?></td>
                                                <td><?php echo $uploaded_by; ?></td>
                                                <td><?php echo $uploaded_date; ?></td>
                                                <td>
                                                    <a href="<?php echo $stat_file_data_loc; ?>" target="_blank">
                                                        <button class="btn btn-info">
                                                            <span class="fa fa-file"></span>
                                                        </button>
                                                    </a>
                                                    <a href="getAPI.php?delete_files=del&&link=upload_statutory_file&&upload_id=<?php echo $file_id; ?>" class="btn btn-danger">
                                                        <span class="fa fa-close"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>