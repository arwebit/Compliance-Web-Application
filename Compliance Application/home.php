<?php
$main_page = "Home";
$page = "Dashboard";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php
            include './header_links.php';
            ?>
        </head>
        <body class="hold-transition skin-blue sidebar-mini">
            <div class="wrapper">
                <?php
                include './top_menu.php';
                include './side_menu.php';
                include './bulk_sms.php';
                include './raise_ticket.php';

                if ($login_role_id == 1) {
                    $login_userSQL = "company_id='$login_company_id' AND ";
                    $user_clause = "";
                } else {
                    $login_userSQL = "assign_user='$login_user' AND ";
                    $user_clause = "AND a.assign_user='$login_user'";
                }
                include './calender_functions.php';
                $my_statSQL = "";
                $my_statSQL .= "SELECT COUNT(*) total_cmp, COUNT(CASE WHEN a.status = -1 AND a.update_status = 1 THEN 1 END) AS canceled_cmp,";
                $my_statSQL .= "COUNT(CASE WHEN a.status = 1 AND a.update_status =1 AND a.renewed_date<=a.due_date THEN 1 END) AS task_comp_time, COUNT(CASE ";
                $my_statSQL .= "WHEN a.status = 1 AND a.update_status = 1 AND a.renewed_date>a.due_date THEN 1 END) AS task_comp_delay, COUNT(CASE WHEN ";
                $my_statSQL .= "a.status = 0 AND a.due_date <NOW() THEN 1 END) AS task_not_comp_overdue, COUNT(CASE WHEN a.status = 0 AND a.due_date ";
                $my_statSQL .= ">=NOW() THEN 1 END) AS task_not_comp_due FROM risk_management a WHERE a.company_id='$login_company_id' $user_clause";
                $fetch_my_stat = json_decode(ret_json_str($my_statSQL));
                foreach ($fetch_my_stat as $fetch_my_stats) {
                    $my_stat_total = $fetch_my_stats->total_cmp;
                    $my_stat_canceled = $fetch_my_stats->canceled_cmp;
                    $my_stat_comp_due = $fetch_my_stats->task_comp_time;
                    $my_stat_comp_delay = $fetch_my_stats->task_comp_delay;
                    $my_stat_not_comp_overdue = $fetch_my_stats->task_not_comp_overdue;
                    $my_stat_not_comp_due = $fetch_my_stats->task_not_comp_due;
                }

                $my_non_statSQL = "";
                $my_non_statSQL .= "SELECT COUNT(*) total_cmp, COUNT(CASE WHEN a.status = -1 AND a.update_status = 1 THEN 1 END) AS canceled_cmp,";
                $my_non_statSQL .= "COUNT(CASE WHEN a.status = 1 AND a.update_status =1 AND a.renewed_date<=a.due_date THEN 1 END) AS task_comp_time, COUNT(CASE ";
                $my_non_statSQL .= "WHEN a.status = 1 AND a.update_status = 1 AND a.renewed_date>a.due_date THEN 1 END) AS task_comp_delay, COUNT(CASE WHEN ";
                $my_non_statSQL .= "a.status = 0 AND a.due_date <NOW() THEN 1 END) AS task_not_comp_overdue, COUNT(CASE WHEN a.status = 0 AND a.due_date ";
                $my_non_statSQL .= ">=NOW() THEN 1 END) AS task_not_comp_due FROM mng_cmp a WHERE a.company_id='$login_company_id' $user_clause";
                $fetch_mynon_stat = json_decode(ret_json_str($my_non_statSQL));
                foreach ($fetch_mynon_stat as $fetch_mynon_stats) {
                    $my_non_stat_total = $fetch_mynon_stats->total_cmp;
                    $my_non_stat_canceled = $fetch_mynon_stats->canceled_cmp;
                    $my_non_stat_comp_due = $fetch_mynon_stats->task_comp_time;
                    $my_non_stat_comp_delay = $fetch_mynon_stats->task_comp_delay;
                    $my_non_stat_not_comp_overdue = $fetch_mynon_stats->task_not_comp_overdue;
                    $my_non_stat_not_comp_due = $fetch_mynon_stats->task_not_comp_due;
                }
                ?>
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1><?php echo $page; ?> 
                        </h1>

                        <ol class="breadcrumb">
                            <span class="fa fa-calendar text-maroon text-bold" data-toggle="modal" data-target="#calendar"></span> &nbsp;&nbsp;&nbsp;&nbsp;
                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="active"><?php echo $page; ?></li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <?php
                        if ($login_role_id > -1) {
                            ?>
                            <!-- Main row -->
                            <div class="row">

                                <!-- /.left-col -->
                                <section class="col-lg-12 col-md-12 col-xs-12 col-sm-12 connectedSortable">
                                    <div class="box box-info">
                                        <div class="box-header">
                                            <center>
                                                <b style="font-size: 17px;"> <?php echo $login_role_id == 1 ? "All user" : "My" ?> Tasks</b> 
                                            </center>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-xs-12 col-sm-12">
                                                    <?php
                                                    if ($my_stat_total == 0) {
                                                        ?>
                                                        <center><b class="alert alert-danger">No records found for Statutory Compliances</b></center>
                                                        <br/> <br/> <br/> <br/><?php
                                                    } else {
                                                        ?>
                                                        <div id="statutory" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                                        <?php
                                                    }
                                                    ?>

                                                    <script type="text/javascript">
                                                        var stat_comp_dd =<?php echo round(($my_stat_comp_due / $my_stat_total) * 100, 1); ?>;
                                                        var stat_comp_dl =<?php echo round(($my_stat_comp_delay / $my_stat_total) * 100, 1); ?>;
                                                        var stat_pend_due =<?php echo round(($my_stat_not_comp_due / $my_stat_total) * 100, 1); ?>;
                                                        var stat_pend_odue =<?php echo round(($my_stat_not_comp_overdue / $my_stat_total) * 100, 1); ?>;
                                                        var stat_cancelled =<?php echo round(($my_stat_canceled / $my_stat_total) * 100, 1); ?>;
                                                        if (isNaN(stat_comp_dd)) {
                                                            stat_comp_dd = 0;
                                                        }
                                                        if (isNaN(stat_comp_dl)) {
                                                            stat_comp_dl = 0;
                                                        }
                                                        if (isNaN(stat_pend_due)) {
                                                            stat_pend_due = 0;
                                                        }
                                                        if (isNaN(stat_pend_odue)) {
                                                            stat_pend_odue = 0;
                                                        }
                                                        if (isNaN(stat_cancelled)) {
                                                            stat_cancelled = 0;
                                                        }
                                                        var stat_val = [stat_comp_dd, stat_comp_dl, stat_pend_due, stat_pend_odue, stat_cancelled];
                                                        Highcharts.chart('statutory', {
                                                            chart: {
                                                                type: 'pie',
                                                                options3d: {
                                                                    enabled: true,
                                                                    alpha: 45,
                                                                    beta: 0
                                                                }
                                                            },
                                                            title: {
                                                                text: 'Statutory'
                                                            },
                                                            tooltip: {
                                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                                            },
                                                            plotOptions: {
                                                                pie: {
                                                                    colors: ['#32CD32', '#FFD700', '#FF8C00', '#FF0000', '#C0C0C0'],
                                                                    allowPointSelect: true,
                                                                    cursor: 'pointer',
                                                                    depth: 35,
                                                                    dataLabels: {
                                                                        enabled: true,
                                                                        format: '{point.name}'
                                                                    }
                                                                }
                                                            },
                                                            series: [{
                                                                    type: 'pie',
                                                                    name: 'Compliances share',
                                                                    data: [
                                                                        ['Completed on due date', stat_val[0]],
                                                                        ['Completed but delayed', stat_val[1]],
                                                                        ['Pending (Due)', stat_val[2]],
                                                                        ['Pending (Overdue)', stat_val[3]],
                                                                        ['Cancelled', stat_val[4]]
                                                                    ]
                                                                }]
                                                        });
                                                    </script>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-xs-12 col-sm-12">
                                                    <?php
                                                    if ($my_non_stat_total == 0) {
                                                        ?>
                                                        <center><b class="alert alert-danger">No records found for Non-Statutory Compliances</b></center>
                                                        <br/> <br/> <br/> <br/>  <?php
                                                    } else {
                                                        ?>
                                                        <div id="non_statutory" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <script type="text/javascript">
                                                        var non_stat_comp_dd =<?php echo round(($my_non_stat_comp_due / $my_non_stat_total) * 100, 1); ?>;
                                                        var non_stat_comp_dl =<?php echo round(($my_non_stat_comp_delay / $my_non_stat_total) * 100, 1); ?>;
                                                        var non_stat_pend_due =<?php echo round(($my_non_stat_not_comp_due / $my_non_stat_total) * 100, 1); ?>;
                                                        var non_stat_pend_odue =<?php echo round(($my_non_stat_not_comp_overdue / $my_non_stat_total) * 100, 1); ?>;
                                                        var non_stat_cancelled =<?php echo round(($my_non_stat_canceled / $my_non_stat_total) * 100, 1); ?>;
                                                        if (isNaN(non_stat_comp_dd)) {
                                                            non_stat_comp_dd = 0;
                                                        }
                                                        if (isNaN(non_stat_comp_dl)) {
                                                            non_stat_comp_dl = 0;
                                                        }
                                                        if (isNaN(non_stat_pend_due)) {
                                                            non_stat_pend_due = 0;
                                                        }
                                                        if (isNaN(non_stat_pend_odue)) {
                                                            non_stat_pend_odue = 0;
                                                        }
                                                        if (isNaN(non_stat_cancelled)) {
                                                            non_stat_cancelled = 0;
                                                        }
                                                        var non_stat_val = [non_stat_comp_dd, non_stat_comp_dl, non_stat_pend_due, non_stat_pend_odue, non_stat_cancelled];

                                                        Highcharts.chart('non_statutory', {
                                                            chart: {
                                                                type: 'pie',
                                                                options3d: {
                                                                    enabled: true,
                                                                    alpha: 45,
                                                                    beta: 0
                                                                }
                                                            },
                                                            title: {
                                                                text: 'Non-Statutory'
                                                            },
                                                            tooltip: {
                                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                                            },
                                                            plotOptions: {
                                                                pie: {
                                                                    colors: ['#32CD32', '#FFD700', '#FF8C00', '#FF0000', '#C0C0C0'],
                                                                    allowPointSelect: true,
                                                                    cursor: 'pointer',
                                                                    depth: 35,
                                                                    dataLabels: {
                                                                        enabled: true,
                                                                        format: '{point.name}'
                                                                    }
                                                                }
                                                            },
                                                            series: [{
                                                                    type: 'pie',
                                                                    name: 'Compliances share',
                                                                    data: [
                                                                        ['Completed on due date', non_stat_val[0]],
                                                                        ['Completed but delayed', non_stat_val[1]],
                                                                        ['Pending (Due)', non_stat_val[2]],
                                                                        ['Pending (Overdue)', non_stat_val[3]],
                                                                        ['Cancelled', non_stat_val[4]]
                                                                    ]
                                                                }]
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                            <div class=" table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Particulars</th>
                                                            <th colspan="2">Completed tasks</th>
                                                            <th colspan="2">Pending tasks</th>
                                                            <th rowspan="2">Cancelled tasks</th>
                                                            <th rowspan="2">Total tasks</th>
                                                        </tr>
                                                        <tr>
                                                            <th>On or before due date</th>  
                                                            <th>Delayed</th> 
                                                            <th>Due</th> 
                                                            <th>Overdue</th>
                                                        </tr>
                                                    </thead>  
                                                    <tbody>
                                                        <tr>
                                                            <th>Statutory</th>
                                                            <td style=" background-color: #32CD32;" data-toggle="modal" data-target="#statutory_comp_due"><?php echo $my_stat_comp_due; ?></td>
                                                            <td style=" background-color: #FFD700;" data-toggle="modal" data-target="#statutory_comp_delay"><?php echo $my_stat_comp_delay; ?></td>
                                                            <td style=" background-color: #FF8C00;" data-toggle="modal" data-target="#statutory_pending_due"><?php echo $my_stat_not_comp_due; ?></td>
                                                            <td style=" background-color: #FF0000; color: #FFFFFF;" data-toggle="modal" data-target="#statutory_pending_overdue"><?php echo $my_stat_not_comp_overdue; ?></td>
                                                            <td style=" background-color: #C0C0C0;" data-toggle="modal" data-target="#statutory_canceled"><?php echo $my_stat_canceled; ?></td>
                                                            <th><?php echo $my_stat_total; ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Non-statutory</th>
                                                            <td style=" background-color: #32CD32;" data-toggle="modal" data-target="#non_statutory_comp_due"><?php echo $my_non_stat_comp_due; ?></td>
                                                            <td style=" background-color: #FFD700;" data-toggle="modal" data-target="#non_statutory_comp_delay"><?php echo $my_non_stat_comp_delay; ?></td>
                                                            <td style=" background-color: #FF8C00;" data-toggle="modal" data-target="#non_statutory_pending_due"><?php echo $my_non_stat_not_comp_due; ?></td>
                                                            <td style=" background-color: #FF0000;color: #FFFFFF;" data-toggle="modal" data-target="#non_statutory_pending_overdue"><?php echo $my_non_stat_not_comp_overdue; ?></td>
                                                            <td style=" background-color: #C0C0C0;" data-toggle="modal" data-target="#non_statutory_canceled"><?php echo $my_non_stat_canceled; ?></td>
                                                            <th style=" "><?php echo $my_non_stat_total; ?></th>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Total</th>
                                                            <th><?php echo $my_stat_comp_due + $my_non_stat_comp_due; ?></th>
                                                            <th><?php echo $my_stat_comp_delay + $my_non_stat_comp_delay; ?></th>
                                                            <th><?php echo $my_stat_not_comp_due + $my_non_stat_not_comp_due; ?></th>
                                                            <th><?php echo $my_stat_not_comp_overdue + $my_non_stat_not_comp_overdue; ?></th>
                                                            <th><?php echo $my_stat_canceled + $my_non_stat_canceled; ?></th>
                                                            <th><?php echo $my_stat_total + $my_non_stat_total; ?></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- /.right-col -->
                            </div>
                            <!-- /.row (main row) -->

                            <section class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 connectedSortable">
                                    <!-- quick email widget -->
                                    <div class="box box-primary">
                                        <div class="box-header">
                                            <i class="fa fa-envelope"></i>
                                            <h3 class="box-title">Circular Email</h3>
                                            <h5>
                                                <b class="text-success" id="success_message"><?php echo $success_msg; ?></b>
                                                <b class="text-danger" id="error_message"><?php echo $error_msg; ?></b>     
                                            </h5> 
                                        </div>
                                        <form action="" method="post">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="department">Department</label>
                                                    <select class="form-control select2" multiple="multiple" data-placeholder="Select departments" name="department[]" id="department" tabindex="1" >
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
                                                    <label for="location">Location</label>
                                                    <select class="form-control select2" multiple="multiple" data-placeholder="Select locations" name="location[]" id="location" tabindex="1" >
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
                                                    <label for="subject">Subject</label><span class="text-danger"> *</span>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="ENTER SUBJECT">
                                                    <b class="text-danger" id="subErr"><?php echo $subErr; ?></b>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message">Message</label><span class="text-danger"> *</span>
                                                    <textarea class="textarea" name="message" placeholder="Message"
                                                              style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                    <b class="text-danger" id="msgErr"><?php echo $msgErr; ?></b>
                                                </div>
                                                <div class="box-footer clearfix">
                                                    <button type="submit" class="pull-right btn btn-primary" name="send_circular" id="send_circular">Send
                                                        <i class="fa fa-arrow-circle-right"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 connectedSortable">
                                    <!-- quick email widget -->
                                    <div class="box box-warning">
                                        <div class="box-header">
                                            <i class="fa fa-ticket"></i>
                                            <h3 class="box-title">Raise ticket</h3>
                                            <h5>
                                                <b class="text-success" id="success_message"><?php echo $tsuccess_msg; ?></b>
                                                <b class="text-danger" id="error_message"><?php echo $terror_msg; ?></b>     
                                            </h5> 
                                        </div>
                                        <form action="" method="post">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="ticket_subject">Subject</label><span class="text-danger"> *</span>
                                                    <select class="form-control select2" name="ticket_subject">
                                                        <option value="">SELECT SUBJECT</option>
                                                        <option value="Closure">Closure  </option>
                                                        <option value="Dashboard">Dashboard</option>
                                                        <option value="Masters">Masters</option>
                                                        <option value="New Development">New Development</option>
                                                        <option value="New Entry">New Entry</option>
                                                        <option value="Report">Report</option>
                                                        <option value="Upload">Upload </option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                    <b class="text-danger" id="ticket_subErr"><?php echo $ticket_subErr; ?></b>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ticket_type">Type</label><span class="text-danger"> *</span>
                                                    <input type="text" class="form-control" name="ticket_type" placeholder="ENTER TYPE" max="255">
                                                    <b class="text-danger" id="ticket_typeErr"><?php echo $ticket_typeErr; ?></b>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ticket_description">Description</label><span class="text-danger"> *</span>
                                                    <textarea class="form-control" name="ticket_description" placeholder="Description"
                                                              style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                    <b class="text-danger" id="ticket_descriptionErr"><?php echo $ticket_descriptionErr; ?></b>
                                                </div>
                                                <div class="box-footer clearfix">
                                                    <button type="button" class="pull-left btn btn-info" id="view_ticket" data-toggle="modal" data-target="#ticket">View tickets
                                                        <i class="fa fa-arrow-circle-right"></i></button>
                                                    <button type="submit" class="pull-right btn btn-warning" name="raise_ticket" id="raise_ticket">Raise ticket
                                                        <i class="fa fa-arrow-circle-right"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                            </section>
                            <?php
                        } else {
                            ?>
                            <div class="box box-primary">
                                <div class="box-header">
                                    <center>
                                        <b style="font-size: 17px;"> Raised tickets</b> 
                                    </center>
                                </div>
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th style="font-weight: bold; font-size: 14px;">Sl </th>
                                                <th style="font-weight: bold; font-size: 14px;">Ticket ID </th>
                                                <th style="font-weight: bold; font-size: 14px;">Ticket Subject</th>
                                                <th  style="font-weight: bold; font-size: 14px;">Ticket Type</th>
                                                <th  style="font-weight: bold; font-size: 14px;">Ticket Description</th>
                                                <th  style="font-weight: bold; font-size: 14px;">Ticket Raised By</th>
                                                <th style="font-weight: bold; font-size: 14px;">Ticket Raised On</th>
                                                <th style="font-weight: bold; font-size: 14px;">Ticket Closed On</th>
                                                <th style="font-weight: bold; font-size: 14px;">Company</th>
                                                <th  style="font-weight: bold; font-size: 14px;">Ticket Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $at_slno = 0;
                                            $aticketSQL = "";
                                            $aticketSQL .= "SELECT a.id, a.ticket_id, a.ticket_subject, a.ticket_type, a.ticket_description, a.ticket_user, c.company_name, ";
                                            $aticketSQL .= "a.ticket_date, a.ticket_closed_date, a.status FROM ticket a INNER JOIN member_login_access b ON a.ticket_user=b.username ";
                                            $aticketSQL .= "INNER JOIN mas_company c ON a.ticket_user_company=c.id";
                                            $fetch_aticket = json_decode(ret_json_str($aticketSQL));
                                            foreach ($fetch_aticket as $fetch_atickets) {
                                                $at_slno++;
                                                $tickid = $fetch_atickets->id;
                                                $ticket_id = $fetch_atickets->ticket_id;
                                                $ticket_subject = $fetch_atickets->ticket_subject;
                                                $ticket_type = $fetch_atickets->ticket_type;
                                                $ticket_description = $fetch_atickets->ticket_description;
                                                $ticket_user = $fetch_atickets->ticket_user;
                                                $ticket_date = $fetch_atickets->ticket_date;
                                                $ticket_closed_date = $fetch_atickets->ticket_closed_date;
                                                $company_name = $fetch_atickets->company_name;
                                                $status = $fetch_atickets->status;
                                                ?>
                                                <tr>
                                                    <td><?php echo $at_slno; ?></td>
                                                    <td><?php echo $ticket_id; ?></td>
                                                    <td><?php echo $ticket_subject; ?></td>
                                                    <td><?php echo $ticket_type; ?></td>
                                                    <td><?php echo nl2br($ticket_description); ?></td>
                                                    <td><?php echo $ticket_user; ?></td>
                                                    <td><?php echo date("d-m-Y H:i:s", strtotime($ticket_date)); ?></td>
                                                    <td><?php echo $ticket_closed_date == "" ? "" : date("d-m-Y H:i:s", strtotime($ticket_closed_date)); ?></td>
                                                    <td><?php echo $company_name; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($status == 1) {
                                                            echo "Resolved";
                                                        } elseif ($status == -1) {
                                                            echo "Not resolved";
                                                        } else {
                                                            ?>
                                                            Pending <br /><br />
                                                            <form action="" method="post" id="ticket_action">
                                                                <input type="hidden" name="ticket_op_id" value="<?php echo $tickid; ?>" />
                                                                <button type="submit" name="ticket_option" class="btn btn-sm btn-danger" value="close">
                                                                    <span class="fa fa-close"></span>
                                                                </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <button type="submit" name="ticket_option" class="btn btn-sm btn-success" value="check">
                                                                    <span class="fa fa-check"></span>
                                                                </button>
                                                            </form>


                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <?php
                        }
                        ?>
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->

                <?php
                include './footer.php';
                ?>
            </div>
            <!-- ./wrapper -->
            <?php
            include './footer_links.php';
            include './dashboard_modals.php';
            ?>


        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>