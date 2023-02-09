<?php

    $mystatutory_analysisSQL = "";
                $mystatutory_analysisSQL .= "SELECT COUNT(*) total_cmp, COUNT(CASE WHEN a.status = -1 AND a.update_status = 1 THEN 1 END) AS cancelled_cmp, COUNT(CASE WHEN a.status = 1 AND a.update_status =";
                $mystatutory_analysisSQL .= "1 AND a.renewed_date IS NULL THEN 1 END) AS task_comp_time, COUNT(CASE WHEN a.status = 0 THEN 1 END) AS task_not_comp, COUNT(CASE WHEN ";
                $mystatutory_analysisSQL .= "a.status = 1 AND a.update_status = 1 AND a.renewed_date IS not NULL THEN 1 END) AS task_comp_delay FROM risk_management a ";
                $mystatutory_analysisSQL .= "WHERE a.company_id='$login_company_id' AND a.username='$login_user'";
                $fetch_mystatutory_analysis = json_decode(ret_json_str($mystatutory_analysisSQL));
                foreach ($fetch_mystatutory_analysis as $fetch_mystatutory_analysiss) {
                    $my_statutory_total_comp = $fetch_mystatutory_analysiss->total_cmp;
                    $my_statutory_cmpl_app_time = $fetch_mystatutory_analysiss->task_comp_time;
                    $my_statutory_cmpl_app_delay = $fetch_mystatutory_analysiss->task_comp_delay;
                    $count_my_statutory_cmpl_app = ($my_statutory_cmpl_app_time + $my_statutory_cmpl_app_delay);
                    $count_my_statutory_cancelled = $fetch_mystatutory_analysiss->cancelled_cmp;
                    $count_pending_my_statutory_compliances = $my_statutory_total_comp - ($count_my_statutory_cmpl_app + $count_my_statutory_cancelled);
                }
                
                
                

                $my_nonstatutory_analysisSQL = "";
                $my_nonstatutory_analysisSQL .= "SELECT COUNT(*) total_cmp, COUNT(CASE WHEN a.status = -1 AND a.update_status = 1 THEN 1 END) AS cancelled_cmp, COUNT(CASE WHEN a.status = 1 AND a.update_status =";
                $my_nonstatutory_analysisSQL .= "1 AND a.renewed_date IS NULL THEN 1 END) AS task_comp_time, COUNT(CASE WHEN a.status = 0 THEN 1 END) AS task_not_comp, COUNT(CASE WHEN ";
                $my_nonstatutory_analysisSQL .= "a.status = 1 AND a.update_status = 1 AND a.renewed_date IS not NULL THEN 1 END) AS task_comp_delay FROM mng_cmp a ";
                $my_nonstatutory_analysisSQL .= "WHERE a.company_id='$login_company_id' AND a.username='$login_user'";
                $fetch_mynonstatutory_analysis = json_decode(ret_json_str($my_nonstatutory_analysisSQL));
                foreach ($fetch_mynonstatutory_analysis as $fetch_mynonstatutory_analysiss) {
                    $my_non_statutory_total_comp = $fetch_mynonstatutory_analysiss->total_cmp;
                    $my_non_statutory_cmpl_app_time = $fetch_mynonstatutory_analysiss->task_comp_time;
                    $my_non_statutory_cmpl_app_delay = $fetch_mynonstatutory_analysiss->task_comp_delay;
                    $count_my_non_statutory_cmpl_app = ($my_non_statutory_cmpl_app_time + $my_non_statutory_cmpl_app_delay);
                    $count_my_non_statutory_cancelled = $fetch_mynonstatutory_analysiss->cancelled_cmp;
                    $count_pending_my_non_statutory_compliances = $my_non_statutory_total_comp - ($count_my_non_statutory_cmpl_app + $count_my_non_statutory_cancelled);
                }

