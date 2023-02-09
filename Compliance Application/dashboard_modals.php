<!-- /************************************* STATUTORY COMPLIANCES ***************************************** /-->
<!-- modal -->  
<div class="modal modal-default fade" id="statutory_comp_due" >
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Statutory Completed (On or before due date)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Completed date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th colspan="2"style="font-weight: bold; font-size: 14px;">Actual </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                            <th style="font-weight: bold; font-size: 14px;">Cost</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stat_cmp_due_where_clause = "a.status = 1 AND a.update_status =1 AND a.renewed_date<=a.due_date";
                        $link1 = "risk_management_details.php?link=home.php&&rm_id=";
                        $slno1 = 0;
                        $approved_reportSQL = "";
                        $approved_reportSQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.legislation, f.department, g.location, ";
                        $approved_reportSQL .= "a.due_date, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost ";
                        $approved_reportSQL .= "FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN mas_purpose c ON ";
                        $approved_reportSQL .= "a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON a.legislation_id=e.id ";
                        $approved_reportSQL .= "INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                        $approved_reportSQL .= "WHERE a.company_id='$login_company_id' $user_clause AND " . $stat_cmp_due_where_clause;
                        $fetch_approved_report = json_decode(ret_json_str($approved_reportSQL));
                        foreach ($fetch_approved_report as $fetch_approved_reports) {
                            $slno1++;
                            $id = $fetch_approved_reports->id;
                            $scid = $fetch_approved_reports->rm_id;
                            $department = $fetch_approved_reports->department;
                            $legislation = $fetch_approved_reports->legislation;
                            $location = $fetch_approved_reports->location;
                            $activity = $fetch_approved_reports->activity;
                            $purpose = $fetch_approved_reports->purpose;
                            $mode = $fetch_approved_reports->mode;
                            $reference = $fetch_approved_reports->reference;
                            $due_date = $fetch_approved_reports->due_date;
                            $budgeted_cost = $fetch_approved_reports->budgeted_cost;
                            $renewed_date = $fetch_approved_reports->renewed_date;
                            $actual_cost = $fetch_approved_reports->actual_cost;
                            $remarks = $fetch_approved_reports->remarks;
                            $actual_value_covered = $fetch_approved_reports->actual_value_covered;
                            $str_due_date = strtotime($due_date);
                            $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                            $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                            if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                $str_renew_date = strtotime($renewed_date);
                                $delay_diff = $str_renew_date - $str_due_date;
                                $task_delayed = round($delay_diff / (60 * 60 * 24));
                                if ($task_delayed <= 0) {
                                    $task_delayed = "---";
                                }
                            } else {
                                $task_delayed = "---";
                            }
                            ?>
                            <tr>
                                <td><?php echo $slno1; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link1 . $id; ?>">
                                        <?php echo $scid; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $legislation; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $activity; ?></td>
                                <td><?php echo $purpose; ?></td>
                                <td><?php echo $mode; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td><?php echo $renewed_date == "" ? "---" : date("d-m-Y", strtotime($renewed_date)); ?></td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_value_covered; ?></td>
                                <td><?php echo $actual_cost; ?></td>
                                <td><?php echo $remarks; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 

<!-- modal -->  
<div class="modal modal-default fade" id="statutory_comp_delay">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Statutory Completed (Delayed)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th colspan="2"style="font-weight: bold; font-size: 14px;">Actual </th>
                            <th colspan="2" style="font-weight: bold; font-size: 14px;">Compliances Status</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                            <th style="font-weight: bold; font-size: 14px;">Cost</th>
                            <th style="font-weight: bold; font-size: 14px;">Completed date</th>
                            <th style="font-weight: bold; font-size: 14px;">Delayed (in days)</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stat_cmp_due_delay_where_clause = "a.status = 1 AND a.update_status =1 AND a.renewed_date>a.due_date";
                        $link2 = "risk_management_details.php?link=home.php&&rm_id=";
                        $slno2 = 0;
                        $approved_report1SQL = "";
                        $approved_report1SQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.legislation, f.department, g.location, ";
                        $approved_report1SQL .= "a.due_date, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost ";
                        $approved_report1SQL .= "FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN mas_purpose c ON ";
                        $approved_report1SQL .= "a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON a.legislation_id=e.id ";
                        $approved_report1SQL .= "INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                        $approved_report1SQL .= "WHERE a.company_id='$login_company_id' $user_clause AND " . $stat_cmp_due_delay_where_clause;
                        $fetch_approved_report1 = json_decode(ret_json_str($approved_report1SQL));
                        foreach ($fetch_approved_report1 as $fetch_approved_reports1) {
                            $slno2++;
                            $id = $fetch_approved_reports1->id;
                            $scid = $fetch_approved_reports1->rm_id;
                            $department = $fetch_approved_reports1->department;
                            $legislation = $fetch_approved_reports1->legislation;
                            $location = $fetch_approved_reports1->location;
                            $activity = $fetch_approved_reports1->activity;
                            $purpose = $fetch_approved_reports1->purpose;
                            $mode = $fetch_approved_reports1->mode;
                            $reference = $fetch_approved_reports1->reference;
                            $due_date = $fetch_approved_reports1->due_date;
                            $budgeted_cost = $fetch_approved_reports1->budgeted_cost;
                            $renewed_date = $fetch_approved_reports1->renewed_date;
                            $actual_cost = $fetch_approved_reports1->actual_cost;
                            $remarks = $fetch_approved_reports1->remarks;
                            $actual_value_covered = $fetch_approved_reports1->actual_value_covered;
                            $str_due_date = strtotime($due_date);
                            $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                            $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                            if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                $str_renew_date = strtotime($renewed_date);
                                $delay_diff = $str_renew_date - $str_due_date;
                                $task_delayed = round($delay_diff / (60 * 60 * 24));
                                if ($task_delayed <= 0) {
                                    $task_delayed = "---";
                                }
                            } else {
                                $task_delayed = "---";
                            }
                            ?>
                            <tr>
                                <td><?php echo $slno; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link2 . $id; ?>">
                                        <?php echo $scid; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $legislation; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $activity; ?></td>
                                <td><?php echo $purpose; ?></td>
                                <td><?php echo $mode; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_value_covered; ?></td>
                                <td><?php echo $actual_cost; ?></td>
                                <td><?php echo $renewed_date == "" ? "---" : date("d-m-Y", strtotime($renewed_date)); ?></td>
                                <td><?php echo $task_delayed; ?> </td>
                                <td><?php echo $remarks; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->  
<div class="modal modal-default fade" id="statutory_pending_due">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Statutory Pending (Due)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
               <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">No. of days pending</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th colspan="2" style="font-weight: bold; font-size: 14px;">Actual</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th> 
                        </tr>
                        <tr>
                            <th style="font-weight: bold; font-size: 14px;">Cost</th>
                            <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stat_pending_due_where_clause = "a.status = 0 AND a.due_date>=NOW()";
                        $link3 = "risk_management_details.php?link=home.php&&rm_id=";
                        $slno3 = 0;
                        $status_reportSQL = "";
                        $status_reportSQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.legislation, f.department, g.location, ";
                        $status_reportSQL .= "a.due_date, a.assign_user, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost,a.status, a.update_status, ";
                        $status_reportSQL .= "a.create_date, a.modify_date FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN ";
                        $status_reportSQL .= "mas_purpose c ON a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON ";
                        $status_reportSQL .= "a.legislation_id=e.id INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                        $status_reportSQL .= "WHERE a.company_id='$login_company_id' $user_clause AND " . $stat_pending_due_where_clause;
                        $fetch_status_report = json_decode(ret_json_str($status_reportSQL));
                        foreach ($fetch_status_report as $fetch_status_reports) {
                            $slno3++;
                            $id = $fetch_status_reports->id;
                            $scid = $fetch_status_reports->rm_id;
                            $department = $fetch_status_reports->department;
                            $legislation = $fetch_status_reports->legislation;
                            $location = $fetch_status_reports->location;
                            $assign_user = $fetch_status_reports->assign_user;
                            $activity = $fetch_status_reports->activity;
                            $purpose = $fetch_status_reports->purpose;
                            $mode = $fetch_status_reports->mode;
                            $reference = $fetch_status_reports->reference;
                            $due_date = $fetch_status_reports->due_date;
                            $budgeted_cost = $fetch_status_reports->budgeted_cost;
                            $renewed_date = $fetch_status_reports->renewed_date;
                            $actual_cost = $fetch_status_reports->actual_cost;
                            $remarks = $fetch_status_reports->remarks;
                            $actual_value_covered = $fetch_status_reports->actual_value_covered;

                            if ($case_status == -1) {
                                $canceled_date = $fetch_status_reports->modify_date;
                                $task_delayed = "---";
                                $task_still_not_completed = "---";
                            } else {
                                $canceled_date = "---";
                                $str_due_date = strtotime($due_date);
                                $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                                $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                                if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                    $str_renew_date = strtotime($renewed_date);
                                    $delay_diff = $str_renew_date - $str_due_date;
                                    $task_delayed = round($delay_diff / (60 * 60 * 24));
                                    if ($task_delayed <= 0) {
                                        $task_delayed = "---";
                                    }
                                } else {
                                    $task_delayed = "---";
                                }
                            }
                            ?>
                            <tr>
                                <td><?php echo $slno3; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link3 . $id; ?>">
                                        <?php echo $scid; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $legislation; ?></td>
                                <td><?php echo $activity; ?></td>
                                <td><?php echo $purpose; ?></td>
                                <td><?php echo $mode; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo $assign_user; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td>
                                    <?php
                                    if ($case_status == -1) {
                                        echo "---";
                                    } else {
                                        if (empty($renewed_date)) {
                                            if ($task_still_not_completed >= 0) {
                                                echo "Due : ";
                                            } else {
                                                echo "Overdue :  ";
                                            }
                                            echo abs($task_still_not_completed);
                                        } else {
                                            echo "---";
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_cost; ?></td>
                                <td><?php echo $actual_value_covered; ?></td>
                                <td><?php echo $remarks; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
               </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
               <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-outline">Save changes</button>
             </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->  
<div class="modal modal-default fade" id="statutory_pending_overdue">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Statutory Pending (Overdue)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class="modal-body">
                   <div class=" table-responsive">
                    <table id="tblData" class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">No. of days pending</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                <th colspan="2" style="font-weight: bold; font-size: 14px;">Actual</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th> 
                            </tr>
                            <tr>
                                <th style="font-weight: bold; font-size: 14px;">Cost</th>
                                <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stat_pending_overdue_where_clause = "a.status = 0 AND a.due_date <NOW()";
                            $link4 = "risk_management_details.php?link=home.php&&rm_id=";
                            $slno4 = 0;
                            $status_report1SQL = "";
                            $status_report1SQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.legislation, f.department, g.location, ";
                            $status_report1SQL .= "a.due_date, a.assign_user, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost,a.status, a.update_status, ";
                            $status_report1SQL .= "a.create_date, a.modify_date FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN ";
                            $status_report1SQL .= "mas_purpose c ON a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON ";
                            $status_report1SQL .= "a.legislation_id=e.id INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                            $status_report1SQL .= "WHERE a.company_id='$login_company_id' $user_clause AND " . $stat_pending_overdue_where_clause;
                            $fetch_status_report1 = json_decode(ret_json_str($status_report1SQL));
                            foreach ($fetch_status_report1 as $fetch_status_reports1) {
                                $slno4++;
                                $id = $fetch_status_reports1->id;
                                $scid = $fetch_status_reports1->rm_id;
                                $department = $fetch_status_reports1->department;
                                $legislation = $fetch_status_reports1->legislation;
                                $location = $fetch_status_reports1->location;
                                $assign_user = $fetch_status_reports1->assign_user;
                                $activity = $fetch_status_reports1->activity;
                                $purpose = $fetch_status_reports1->purpose;
                                $mode = $fetch_status_reports1->mode;
                                $reference = $fetch_status_reports1->reference;
                                $due_date = $fetch_status_reports1->due_date;
                                $budgeted_cost = $fetch_status_reports1->budgeted_cost;
                                $renewed_date = $fetch_status_reports1->renewed_date;
                                $actual_cost = $fetch_status_reports1->actual_cost;
                                $remarks = $fetch_status_reports1->remarks;
                                $actual_value_covered = $fetch_status_reports1->actual_value_covered;

                                if ($case_status == -1) {
                                    $canceled_date = $fetch_status_reports1->modify_date;
                                    $task_delayed = "---";
                                    $task_still_not_completed = "---";
                                } else {
                                    $canceled_date = "---";
                                    $str_due_date = strtotime($due_date);
                                    $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                                    $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                                    if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                        $str_renew_date = strtotime($renewed_date);
                                        $delay_diff = $str_renew_date - $str_due_date;
                                        $task_delayed = round($delay_diff / (60 * 60 * 24));
                                        if ($task_delayed <= 0) {
                                            $task_delayed = "---";
                                        }
                                    } else {
                                        $task_delayed = "---";
                                    }
                                }
                                ?>
                                <tr>
                                    <td><?php echo $slno4; ?></td>
                                    <td style="font-weight: bolder;"><a href="<?php echo $link4 . $id; ?>">
                                            <?php echo $scid; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $department; ?></td>
                                    <td><?php echo $location; ?></td>
                                    <td><?php echo $legislation; ?></td>
                                    <td><?php echo $activity; ?></td>
                                    <td><?php echo $purpose; ?></td>
                                    <td><?php echo $mode; ?></td>
                                    <td><?php echo $reference; ?></td>
                                    <td><?php echo $assign_user; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                    <td>
                                        <?php
                                        if ($case_status == -1) {
                                            echo "---";
                                        } else {
                                            if (empty($renewed_date)) {
                                                if ($task_still_not_completed >= 0) {
                                                    echo "Due : ";
                                                } else {
                                                    echo "Overdue :  ";
                                                }
                                                echo abs($task_still_not_completed);
                                            } else {
                                                echo "---";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $budgeted_cost; ?></td>
                                    <td><?php echo $actual_cost; ?></td>
                                    <td><?php echo $actual_value_covered; ?></td>
                                    <td><?php echo $remarks; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                   </div>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->  
<div class="modal modal-default fade" id="statutory_canceled">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Statutory Cancelled</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>

                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Cancelled date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th colspan="2"style="font-weight: bold; font-size: 14px;">Actual </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                            <th style="font-weight: bold; font-size: 14px;">Cost</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stat_canceled_where_clause = "a.status = -1 AND a.update_status = 1";
                        $link5 = "risk_management_details.php?link=home.php&&rm_id=";
                        $slno5 = 0;
                        $canceled_reportSQL = "";
                        $canceled_reportSQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.legislation, f.department, g.location, ";
                        $canceled_reportSQL .= "a.due_date, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost, a.modify_date ";
                        $canceled_reportSQL .= "FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN mas_purpose c ON ";
                        $canceled_reportSQL .= "a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON a.legislation_id=e.id ";
                        $canceled_reportSQL .= "INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                        $canceled_reportSQL .= "WHERE a.company_id='$login_company_id' $user_clause AND " . $stat_canceled_where_clause;
                        $fetch_canceled_report = json_decode(ret_json_str($canceled_reportSQL));
                        foreach ($fetch_canceled_report as $fetch_canceled_reports) {
                            $slno5++;
                            $id = $fetch_canceled_reports->id;
                            $scid = $fetch_canceled_reports->rm_id;
                            $department = $fetch_canceled_reports->department;
                            $legislation = $fetch_canceled_reports->legislation;
                            $location = $fetch_canceled_reports->location;
                            $activity = $fetch_canceled_reports->activity;
                            $purpose = $fetch_canceled_reports->purpose;
                            $mode = $fetch_canceled_reports->mode;
                            $reference = $fetch_canceled_reports->reference;
                            $due_date = $fetch_canceled_reports->due_date;
                            $budgeted_cost = $fetch_canceled_reports->budgeted_cost;
                            $renewed_date = $fetch_canceled_reports->renewed_date;
                            $actual_cost = $fetch_canceled_reports->actual_cost;
                            $remarks = $fetch_canceled_reports->remarks;
                            $actual_value_covered = $fetch_canceled_reports->actual_value_covered;
                            $canceled_date = $fetch_canceled_reports->modify_date;
                            ?>
                            <tr>
                                <td><?php echo $slno5; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link5 . $id; ?>">
                                        <?php echo $scid; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $legislation; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $activity; ?></td>
                                <td><?php echo $purpose; ?></td>
                                <td><?php echo $mode; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td><?php echo date("d-m-Y", strtotime($canceled_date)); ?></td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_value_covered; ?></td>
                                <td><?php echo $actual_cost; ?></td>
                                <td><?php echo $remarks; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /************************************* STATUTORY COMPLIANCES ***************************************** /-->



<!-- /************************************* NON-STATUTORY COMPLIANCES ***************************************** /-->
<!-- modal -->  
<div class="modal modal-default fade" id="non_statutory_comp_due" >
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Non-Statutory Completed (On or before due date)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
               <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">NSC ID</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Description</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Completed date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Transaction Value</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $non_stat_cmp_due_where_clause = "a.status = 1 AND a.update_status =1 AND a.renewed_date<=a.due_date";
                        $link6 = "mng_cmp_details.php?link=home.php&&mng_id=";
                        $slno6 = 0;
                        $non_stat_status_reportSQL = "";
                        $non_stat_status_reportSQL .= "SELECT a.id, a.mng_id, a.reference, b.location, c.department, a.description, a.due_date, a.renewed_date, ";
                        $non_stat_status_reportSQL .= "a.assign_user, a.budgeted_cost, a.actual_transaction_value, a.comp_nature, a.create_date, a.modify_date, ";
                        $non_stat_status_reportSQL .= "a.remarks FROM mng_cmp a INNER JOIN mas_location b ON a.location_id=b.id INNER JOIN mas_department c ON ";
                        $non_stat_status_reportSQL .= "a.department_id=c.id WHERE a.company_id='$login_company_id' $user_clause AND " . $non_stat_cmp_due_where_clause;

                        $fetch_non_statstatus_report = json_decode(ret_json_str($non_stat_status_reportSQL));
                        foreach ($fetch_non_statstatus_report as $fetch_non_statstatus_reports) {
                            $slno6++;
                            $id = $fetch_non_statstatus_reports->id;
                            $mng_id = $fetch_non_statstatus_reports->mng_id;
                            $department = $fetch_non_statstatus_reports->department;
                            $location = $fetch_non_statstatus_reports->location;
                            $description = $fetch_non_statstatus_reports->description;
                            $comp_nature = $fetch_non_statstatus_reports->comp_nature;
                            $reference = $fetch_non_statstatus_reports->reference;
                            $due_date = $fetch_non_statstatus_reports->due_date;
                            $renewed_date = $fetch_non_statstatus_reports->renewed_date;
                            $assign_user = $fetch_non_statstatus_reports->assign_user;
                            $remarks = $fetch_non_statstatus_reports->remarks;
                            $budgeted_cost = $fetch_non_statstatus_reports->budgeted_cost;
                            $actual_transaction_value = $fetch_non_statstatus_reports->actual_transaction_value;
                            $case_status = $fetch_non_statstatus_reports->status;
                            ?>
                            <tr>
                                <td><?php echo $slno6; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link6 . $id; ?>">
                                        <?php echo $mng_id; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $comp_nature; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo $assign_user; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td><?php echo date("d-m-Y", strtotime($renewed_date)); ?></td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_transaction_value; ?></td>

                                <td><?php echo $remarks; ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
               </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 
<!-- modal -->  
<div class="modal modal-default fade" id="non_statutory_comp_delay">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Non-Statutory Completed (Delayed)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
               <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>

                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">NSC ID</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Description</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Transaction Value</th>
                            <th colspan="2" style="font-weight: bold; font-size: 14px;">Compliances Status</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>   
                        </tr>
                        <tr>
                            <th style="font-weight: bold; font-size: 14px;">Completed date</th>
                            <th style="font-weight: bold; font-size: 14px;">Delayed (in days)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $non_stat_cmp_delay_where_clause = "a.status = 1 AND a.update_status = 1 AND a.renewed_date>a.due_date";
                        $link7 = "mng_cmp_details.php?link=home.php&&mng_id=";
                        $slno7 = 0;
                        $non_status_report1SQL = "";
                        $non_status_report1SQL .= "SELECT a.id, a.mng_id, a.reference, b.location, c.department, a.description, a.due_date, a.renewed_date, ";
                        $non_status_report1SQL .= "a.assign_user, a.budgeted_cost, a.actual_transaction_value, a.comp_nature, a.create_date, a.modify_date, ";
                        $non_status_report1SQL .= "a.remarks FROM mng_cmp a INNER JOIN mas_location b ON a.location_id=b.id INNER JOIN mas_department c ON ";
                        $non_status_report1SQL .= "a.department_id=c.id WHERE a.company_id='$login_company_id' $user_clause AND " . $non_stat_cmp_delay_where_clause;

                        $fetch_non_statstatus_report1 = json_decode(ret_json_str($non_status_report1SQL));
                        foreach ($fetch_non_statstatus_report1 as $fetch_non_statstatus_reports1) {
                            $slno7++;
                            $id = $fetch_non_statstatus_reports1->id;
                            $mng_id = $fetch_non_statstatus_reports1->mng_id;
                            $department = $fetch_non_statstatus_reports1->department;
                            $location = $fetch_non_statstatus_reports1->location;
                            $description = $fetch_non_statstatus_reports1->description;
                            $comp_nature = $fetch_non_statstatus_reports1->comp_nature;
                            $reference = $fetch_non_statstatus_reports1->reference;
                            $due_date = $fetch_non_statstatus_reports1->due_date;
                            $renewed_date = $fetch_non_statstatus_reports1->renewed_date;
                            $assign_user = $fetch_non_statstatus_reports1->assign_user;
                            $remarks = $fetch_non_statstatus_reports1->remarks;
                            $budgeted_cost = $fetch_non_statstatus_reports1->budgeted_cost;
                            $actual_transaction_value = $fetch_non_statstatus_reports1->actual_transaction_value;
                            $case_status = $fetch_non_statstatus_reports1->status;

                            if ($case_status == -1) {
                                $canceled_date = $fetch_non_statstatus_reports1->modify_date;
                                $task_delayed = "---";
                                $task_still_not_completed = "---";
                            } else {
                                $canceled_date = "---";
                                $str_due_date = strtotime($due_date);
                                $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                                $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                                if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                    $str_renew_date = strtotime($renewed_date);
                                    $delay_diff = $str_renew_date - $str_due_date;
                                    $task_delayed = round($delay_diff / (60 * 60 * 24));
                                    if ($task_delayed <= 0) {
                                        $task_delayed = "---";
                                    }
                                } else {
                                    $task_delayed = "---";
                                }
                            }
                            ?>
                            <tr>
                                <td><?php echo $slno7; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link7 . $id; ?>">
                                        <?php echo $mng_id; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $comp_nature; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo $assign_user; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_transaction_value; ?></td>
                                <td><?php echo $renewed_date == "" ? "---" : date("d-m-Y", strtotime($renewed_date)); ?></td>
                                <td><?php echo $task_delayed; ?> </td>
                                <td><?php echo $remarks; ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
               </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->  
<div class="modal modal-default fade" id="non_statutory_pending_due">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Non-Statutory Pending (Due)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">NSC ID</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Description</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">No. of days pending</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Transaction Value</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>     
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $non_stat_pend_due_whereclause = "a.status = 0 AND a.due_date>=NOW()";
                        $link8 = "mng_cmp_details.php?link=report_management_status.php&&mng_id=";
                        $slno8 = 0;
                        $non_statpend_due_reportSQL = "";
                        $non_statpend_due_reportSQL .= "SELECT a.id, a.mng_id, a.reference, b.location, c.department, a.description, a.due_date, a.renewed_date, ";
                        $non_statpend_due_reportSQL .= "a.assign_user, a.budgeted_cost, a.actual_transaction_value, a.comp_nature,a.status, a.update_status, ";
                        $non_statpend_due_reportSQL .= "a.create_date, a.modify_date, a.remarks FROM mng_cmp a INNER JOIN mas_location b ON a.location_id=b.id ";
                        $non_statpend_due_reportSQL .= "INNER JOIN mas_department c ON a.department_id=c.id WHERE a.company_id='$login_company_id' $user_clause AND " . $non_stat_pend_due_whereclause;


                        $fetch_non_stat_pending_report = json_decode(ret_json_str($non_statpend_due_reportSQL));
                        foreach ($fetch_non_stat_pending_report as $fetch_non_stat_pending_reports) {
                            $slno8++;
                            $id = $fetch_non_stat_pending_reports->id;
                            $mng_id = $fetch_non_stat_pending_reports->mng_id;
                            $department = $fetch_non_stat_pending_reports->department;
                            $location = $fetch_non_stat_pending_reports->location;
                            $description = $fetch_non_stat_pending_reports->description;
                            $comp_nature = $fetch_non_stat_pending_reports->comp_nature;
                            $reference = $fetch_non_stat_pending_reports->reference;
                            $due_date = $fetch_non_stat_pending_reports->due_date;
                            $renewed_date = $fetch_non_stat_pending_reports->renewed_date;
                            $assign_user = $fetch_non_stat_pending_reports->assign_user;
                            $remarks = $fetch_non_stat_pending_reports->remarks;
                            $budgeted_cost = $fetch_non_stat_pending_reports->budgeted_cost;
                            $actual_transaction_value = $fetch_non_stat_pending_reports->actual_transaction_value;

                            if ($case_status == -1) {
                                $canceled_date = $fetch_non_stat_pending_reports->modify_date;
                                $task_delayed = "---";
                                $task_still_not_completed = "---";
                            } else {
                                $canceled_date = "---";
                                $str_due_date = strtotime($due_date);
                                $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                                $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                                if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                    $str_renew_date = strtotime($renewed_date);
                                    $delay_diff = $str_renew_date - $str_due_date;
                                    $task_delayed = round($delay_diff / (60 * 60 * 24));
                                    if ($task_delayed <= 0) {
                                        $task_delayed = "---";
                                    }
                                } else {
                                    $task_delayed = "---";
                                }
                            }
                            ?>
                            <tr>
                                <td><?php echo $slno8; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link8 . $id; ?>">
                                        <?php echo $mng_id; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $comp_nature; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo $assign_user; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td>
                                    <?php
                                    if ($case_status == -1) {
                                        echo "---";
                                    } else {
                                        if (empty($renewed_date)) {
                                            if ($task_still_not_completed >= 0) {
                                                echo "Due : ";
                                            } else {
                                                echo "Overdue :  ";
                                            }
                                            echo abs($task_still_not_completed);
                                        } else {
                                            echo "---";
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_transaction_value; ?></td>
                                <td><?php echo $remarks; ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->  
<div class="modal modal-default fade" id="non_statutory_pending_overdue">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Non-Statutory Pending (Overdue)</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class="modal-body">
                  <div class=" table-responsive">
                    <table id="tblData" class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">NSC ID</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Description</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">No. of days pending</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Transaction Value</th>
                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>     
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $non_stat_pend_overdue_whereclause = "a.status = 0 AND a.due_date<NOW()";
                            $link9 = "mng_cmp_details.php?link=home.php&&mng_id=";
                            $slno9 = 0;
                            $non_statpend_overdue_reportSQL = "";
                            $non_statpend_overdue_reportSQL .= "SELECT a.id, a.mng_id, a.reference, b.location, c.department, a.description, a.due_date, a.renewed_date, ";
                            $non_statpend_overdue_reportSQL .= "a.assign_user, a.budgeted_cost, a.actual_transaction_value, a.comp_nature,a.status, a.update_status, ";
                            $non_statpend_overdue_reportSQL .= "a.create_date, a.modify_date, a.remarks FROM mng_cmp a INNER JOIN mas_location b ON a.location_id=b.id ";
                            $non_statpend_overdue_reportSQL .= "INNER JOIN mas_department c ON a.department_id=c.id WHERE a.company_id='$login_company_id' $user_clause AND " . $non_stat_pend_overdue_whereclause;


                            $fetch_non_stat_pending_over_report = json_decode(ret_json_str($non_statpend_overdue_reportSQL));
                            foreach ($fetch_non_stat_pending_over_report as $fetch_non_stat_pending_over_reports) {
                                $slno9++;
                                $id = $fetch_non_stat_pending_over_reports->id;
                                $mng_id = $fetch_non_stat_pending_over_reports->mng_id;
                                $department = $fetch_non_stat_pending_over_reports->department;
                                $location = $fetch_non_stat_pending_over_reports->location;
                                $description = $fetch_non_stat_pending_over_reports->description;
                                $comp_nature = $fetch_non_stat_pending_over_reports->comp_nature;
                                $reference = $fetch_non_stat_pending_over_reports->reference;
                                $due_date = $fetch_non_stat_pending_over_reports->due_date;
                                $renewed_date = $fetch_non_stat_pending_over_reports->renewed_date;
                                $assign_user = $fetch_non_stat_pending_over_reports->assign_user;
                                $remarks = $fetch_non_stat_pending_over_reports->remarks;
                                $budgeted_cost = $fetch_non_stat_pending_over_reports->budgeted_cost;
                                $actual_transaction_value = $fetch_non_stat_pending_over_reports->actual_transaction_value;

                                if ($case_status == -1) {
                                    $canceled_date = $fetch_non_stat_pending_reports->modify_date;
                                    $task_delayed = "---";
                                    $task_still_not_completed = "---";
                                } else {
                                    $canceled_date = "---";
                                    $str_due_date = strtotime($due_date);
                                    $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                                    $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                                    if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                        $str_renew_date = strtotime($renewed_date);
                                        $delay_diff = $str_renew_date - $str_due_date;
                                        $task_delayed = round($delay_diff / (60 * 60 * 24));
                                        if ($task_delayed <= 0) {
                                            $task_delayed = "---";
                                        }
                                    } else {
                                        $task_delayed = "---";
                                    }
                                }
                                ?>
                                <tr>
                                    <td><?php echo $slno9; ?></td>
                                    <td style="font-weight: bolder;"><a href="<?php echo $link9 . $id; ?>">
                                            <?php echo $mng_id; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $department; ?></td>
                                    <td><?php echo $location; ?></td>
                                    <td><?php echo $comp_nature; ?></td>
                                    <td><?php echo $description; ?></td>
                                    <td><?php echo $reference; ?></td>
                                    <td><?php echo $assign_user; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                    <td>
                                        <?php
                                        if ($case_status == -1) {
                                            echo "---";
                                        } else {
                                            if (empty($renewed_date)) {
                                                if ($task_still_not_completed >= 0) {
                                                    echo "Due : ";
                                                } else {
                                                    echo "Overdue :  ";
                                                }
                                                echo abs($task_still_not_completed);
                                            } else {
                                                echo "---";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $budgeted_cost; ?></td>
                                    <td><?php echo $actual_transaction_value; ?></td>
                                    <td><?php echo $remarks; ?></td>

                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->  
<div class="modal modal-default fade" id="non_statutory_canceled">
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Non-Statutory Cancelled</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class=" table-responsive">
                <table id="tblData" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">NSC ID</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Description</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Cancelled date</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Transaction Value</th>
                            <th colspan="2" style="font-weight: bold; font-size: 14px;">Compliances Status (Tasks)</th>
                            <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $non_stat_canceled_where_clause = "a.status = -1 AND a.update_status = 1";
                        $link10 = "mng_cmp_details.php?link=home.php&&mng_id=";
                        $slno10 = 0;
                        $nscanceled_reportSQL = "";
                        $nscanceled_reportSQL .= "SELECT a.id, a.mng_id, a.reference, b.location, c.department, a.description, a.due_date, a.renewed_date, ";
                        $nscanceled_reportSQL .= "a.assign_user, a.budgeted_cost, a.actual_transaction_value, a.comp_nature, a.create_date, a.modify_date, ";
                        $nscanceled_reportSQL .= "a.remarks FROM mng_cmp a INNER JOIN mas_location b ON a.location_id=b.id INNER JOIN mas_department c ON ";
                        $nscanceled_reportSQL .= "a.department_id=c.id WHERE a.company_id='$login_company_id' $user_clause AND " . $non_stat_canceled_where_clause;

                        $fetch_nscanceled_report = json_decode(ret_json_str($nscanceled_reportSQL));
                        foreach ($fetch_nscanceled_report as $fetch_nscanceled_reports) {
                            $slno10++;
                            $id = $fetch_nscanceled_reports->id;
                            $mng_id = $fetch_nscanceled_reports->mng_id;
                            $department = $fetch_nscanceled_reports->department;
                            $location = $fetch_nscanceled_reports->location;
                            $description = $fetch_nscanceled_reports->description;
                            $comp_nature = $fetch_nscanceled_reports->comp_nature;
                            $reference = $fetch_nscanceled_reports->reference;
                            $due_date = $fetch_nscanceled_reports->due_date;
                            $canceled_date = $fetch_nscanceled_reports->modify_date;
                            $renewed_date = $fetch_nscanceled_reports->renewed_date;
                            $assign_user = $fetch_nscanceled_reports->assign_user;
                            $remarks = $fetch_nscanceled_reports->remarks;
                            $budgeted_cost = $fetch_nscanceled_reports->budgeted_cost;
                            $actual_transaction_value = $fetch_nscanceled_reports->actual_transaction_value;
                            $case_status = $fetch_nscanceled_reports->status;
                            ?>
                            <tr>
                                <td><?php echo $slno10; ?></td>
                                <td style="font-weight: bolder;"><a href="<?php echo $link10 . $id; ?>">
                                        <?php echo $mng_id; ?>
                                    </a>
                                </td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $comp_nature; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $reference; ?></td>
                                <td><?php echo $assign_user; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                <td><?php echo date("d-m-Y", strtotime($canceled_date)); ?></td>
                                <td><?php echo $budgeted_cost; ?></td>
                                <td><?php echo $actual_transaction_value; ?></td>
                                <td><?php echo $remarks; ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /************************************* NON-STATUTORY COMPLIANCES ***************************************** /-->


<!-- /************************************* TICKETS COMPLIANCES ***************************************** /-->
<!-- modal -->  
<div class="modal modal-default fade" id="ticket" >
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <!-- modal-content -->
        <div class="modal-content" style="width:1200px; margin-left: -300px;">
            <!-- modal-header -->
            <div class="modal-header">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                <h4 class="modal-title">Tickets</h4>
            </div>
            <!-- /.modal-header -->
            <!-- modal-body -->
            <div class="modal-body" style="height: 400px; overflow-y: scroll; overflow-x: scroll;">
                <div class=" table-responsive">
                <table id="example1" class="table table-bordered">
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
                            <th  style="font-weight: bold; font-size: 14px;">Ticket Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $t_slno = 0;
                        $ticketSQL = "";
                        $ticketSQL .= "SELECT a.ticket_id, a.ticket_subject, a.ticket_type, a.ticket_description, a.ticket_user, ";
                        $ticketSQL .= "a.ticket_date, a.ticket_closed_date, a.status FROM ticket a INNER JOIN member_login_access b ON ";
                        $ticketSQL .= "a.ticket_user=b.username WHERE a.ticket_user_company='$login_company_id' AND b.role>='$login_role_id'";
                        $fetch_ticket = json_decode(ret_json_str($ticketSQL));
                        foreach ($fetch_ticket as $fetch_tickets) {
                            $t_slno++;
                            $ticket_id = $fetch_tickets->ticket_id;
                            $ticket_subject = $fetch_tickets->ticket_subject;
                            $ticket_type = $fetch_tickets->ticket_type;
                            $ticket_description = $fetch_tickets->ticket_description;
                            $ticket_user = $fetch_tickets->ticket_user;
                            $ticket_date = $fetch_tickets->ticket_date;
                            $ticket_closed_date = $fetch_tickets->ticket_closed_date;
                            $status = $fetch_tickets->status;
                            ?>
                            <tr>
                                <td><?php echo $t_slno; ?></td>
                                <td><?php echo $ticket_id; ?></td>
                                <td><?php echo $ticket_subject; ?></td>
                                <td><?php echo $ticket_type; ?></td>
                                <td><?php echo nl2br($ticket_description); ?></td>
                                <td><?php echo $ticket_user; ?></td>
                                <td><?php echo date("d-m-Y H:i:s", strtotime($ticket_date)); ?></td>
                                <td><?php echo $ticket_closed_date == "" ? "" : date("d-m-Y H:i:s", strtotime($ticket_closed_date)); ?></td>
                                <td>
                                    <?php
                                    if ($status == 1) {
                                        echo "Resolved";
                                    } elseif ($status == -1) {
                                        echo "Not resolved";
                                    } else {
                                        echo "Pending";
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
            <!-- /.modal-body -->
            <!--<div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline">Save changes</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 

<!-- /************************************* TICKETS COMPLIANCES ***************************************** /-->



<!-- /************************************* CALENDER ***************************************** /-->
<!-- modal -->  
<div class="modal modal-default fade" id="calendar" >
    <!-- modal-dialog -->
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
         <div id="calendar_div">
                    <?php 
                    echo getCalender('', '', $login_userSQL); 
                    ?>
                </div></div>
    </div>
    
</div>
<!-- /************************************* CALENDER ***************************************** /-->