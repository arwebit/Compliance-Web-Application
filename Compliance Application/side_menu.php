<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header" style="color: #e7e7e7;">MAIN NAVIGATION</li>
            <li>
                <a href="home.php">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php
            if ($login_role_id == -1) {
                if(file_exists("upload_files/recoitcc_crm_backup.php")){
                     $bckup_link="upload_files/recoitcc_crm_backup.php";
                }else{
                    $bckup_link="#";
                }
                ?>
            <li> <a href="<?php echo $bckup_link; ?>"><i class="fa fa-hdd-o"></i>Backup</a></li>
            <?php
            }
            if ($login_role_id < 3) {
                ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span>Masters</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                        if ($login_role_id == -1) {
                            ?>
                        
                            <li> <a href="view_company.php"><i class="fa fa-circle-o"></i>Company</a></li>
                            <li> <a href="add_employee.php"><i class="fa fa-circle-o"></i></i>Employee</a></li>

                            <?php
                        }
                        if (($login_role_id > 0) && ($login_role_id < 3)) {
                            if ($login_role_id == 1) {
                                ?>

                                <!--<li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-tags"></i>
                                        <span>Export</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="export_all_data.php?export_data=Statutory&&company_id=<?php /* echo $login_company_id; ?>"><i class="fa fa-circle-o"></i> Statutory compliance</a></li>
                      <li><a href="export_all_data.php?export_data=Non_statutory&&company_id=<?php echo $login_company_id; ?>"><i class="fa fa-circle-o"></i> Non-statutory compliance</a></li>
                      <li><a href="export_all_data.php?export_master=Employee&&company_id=<?php echo $login_company_id; ?>"><i class="fa fa-circle-o"></i> Employee</a></li>
                      <li><a href="export_all_data.php?export_master=Location&&company_id=<?php echo $login_company_id; ?>"><i class="fa fa-circle-o"></i> Location</a></li>
                      <li><a href="export_all_data.php?export_master=Department&&company_id=<?php echo $login_company_id; */ ?>"><i class="fa fa-circle-o"></i> Department</a></li>
                                    </ul>
                                </li>-->
                                <?php
                            }
                            ?>

                            <li> <a href="view_employees.php"><i class="fa fa-circle-o"></i>Employee</a></li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i></i> Location
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="view_locations.php"><i class="fa fa-arrow-right"></i> View</a></li>
                                    <li> <a href="upload_master_data.php?table=mas_location"><i class="fa fa-arrow-right"></i> Upload</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i></i> Departments
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="view_departments.php"><i class="fa fa-arrow-right"></i> View</a></li>
                                    <li> <a href="upload_master_data.php?table=mas_department"><i class="fa fa-arrow-right"></i> Upload</a></li>
                                </ul>
                            </li>
                            <li> <a href="view_legislations.php"><i class="fa fa-circle-o"></i>Legislations</a></li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i></i> Purposes
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="view_purposes.php"><i class="fa fa-arrow-right"></i> View</a></li>
                                    <li> <a href="upload_master_data.php?table=mas_purpose"><i class="fa fa-arrow-right"></i> Upload</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i></i> Activities
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="view_activities.php"><i class="fa fa-arrow-right"></i> View</a></li>
                                    <li> <a href="upload_master_data.php?table=mas_activity"><i class="fa fa-arrow-right"></i> Upload</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i></i> Modes
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="view_modes.php"><i class="fa fa-arrow-right"></i> View</a></li>
                                    <li> <a href="upload_master_data.php?table=mas_mode"><i class="fa fa-arrow-right"></i> Upload</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>

                <?php
            }
            if ($login_role_id != -1) {
                ?> 

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file-archive-o"></i> <span>Compliance</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i></i> Statutory
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="add_risk_management.php"><i class="fa fa-arrow-right"></i> Add</a></li>
                                <li> <a href="upload_statutory_data.php"><i class="fa fa-arrow-right"></i> Import data</a></li>
                                <li> <a href="view_risk_management.php"><i class="fa fa-arrow-right"></i> Action</a></li>
                                <li> <a href="upload_statutory_file.php"><i class="fa fa-arrow-right"></i> Upload files</a></li>
                                <!--<li> <a id="btnExportSD" href="javascript::void(0)">Export all</a></li>
                                --></ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i> Non-statutory
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li> <a href="add_management_comp.php"><i class="fa fa-arrow-right"></i> Add</a></li>
                                <li> <a href="upload_non_statutory_data.php"><i class="fa fa-arrow-right"></i> Import data</a></li>
                                <li> <a href="view_management_comp.php"><i class="fa fa-arrow-right"></i> Action</a></li>
                                <li> <a href="upload_non_statutory_file.php"><i class="fa fa-arrow-right"></i> Upload files</a></li>
                                <!-- <li> <a id="btnExportNSD" href="javascript::void(0)">Export all</a></li>
                                --> </ul>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-tags"></i>
                        <span>My tasks</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li> <a href="assigner_tasks.php"><i class="fa fa-circle-o"></i> Statutory compliance</a></li>
                        <li> <a href="mng_assigner_tasks.php"><i class="fa fa-circle-o"></i> Non-statutory compliance</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-sticky-note-o"></i> <span>Compliance reports</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i> Statutory
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li> <a  href="report_mis.php"><i class="fa fa-arrow-right"></i> MIS</a></li>
                                <li> <a  href="report_annual_plan.php"><i class="fa fa-arrow-right"></i> Annual plan</a></li>
                                <li> <a  href="report_status.php"><i class="fa fa-arrow-right"></i> Status</a></li>
                                <li> <a  href="report_approved.php"><i class="fa fa-arrow-right"></i> Approved report</a></li>
                                <li> <a  href="report_canceled.php"><i class="fa fa-arrow-right"></i> Cancelled report</a></li>
                                <li> <a  href="report_user_statutory.php"><i class="fa fa-arrow-right"></i> User report</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i> Non-statutory
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li> <a href="report_management_mis.php"><i class="fa fa-arrow-right"></i> MIS</a></li>
                                <li> <a href="report_management_annual_plan.php"><i class="fa fa-arrow-right"></i> Annual plan</a></li>
                                <li> <a href="report_management_status.php"><i class="fa fa-arrow-right"></i> Status</a></li>
                                <li> <a href="report_management_approved.php"><i class="fa fa-arrow-right"></i> Approved report</a></li>
                                <li> <a  href="report_management_canceled.php"><i class="fa fa-arrow-right"></i> Cancelled report</a></li>
                            <li> <a  href="report_user_non_statutory.php"><i class="fa fa-arrow-right"></i> User report</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="header" style="color: #e7e7e7;">DOWNLOAD TEMPLATES</li>
                <li><a href="download/Non_statutory_format.csv"><i class="fa fa-circle-o text-red"></i> <span>Non Statutory Format</span></a></li>
                <li><a href="download/Statutory_format.csv"><i class="fa fa-circle-o text-yellow"></i> <span>Statutory Format</span></a></li>
                <li><a href="download/Master_format.csv"><i class="fa fa-circle-o text-purple"></i> <span>Master Table Format</span></a></li>
                    <?php
                }
                ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>