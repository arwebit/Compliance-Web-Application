
<?php
error_reporting(1);
include 'dbConfig.php';
?>
<input type="hidden" id="assuser" value="<?php echo $login_userSQL; ?>" />
<?php
/*
 * Load function based on the Ajax request 
 */
if (isset($_POST['func']) && !empty($_POST['func'])) {
    switch ($_POST['func']) {
        case 'getCalender':
            getCalender($_POST['year'], $_POST['month'], $_POST['ass_user']);
            break;
        case 'getPending':
            getPending($_POST['date'], $_POST['ass_user']);
            break;
        default:
            break;
    }
}

/*
 * Generate event calendar in HTML format 
 */

function getCalender($year = '', $month = '', $userSQL = '') {
    $dateYear = ($year != '') ? $year : date("Y");
    $dateMonth = ($month != '') ? $month : date("m");
    $date = $dateYear . '-' . $dateMonth . '-01';
    $currentMonthFirstDay = date("N", strtotime($date));
    $totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN, $dateMonth, $dateYear);
    $totalDaysOfMonthDisplay = ($currentMonthFirstDay == 1) ? ($totalDaysOfMonth) : ($totalDaysOfMonth + ($currentMonthFirstDay - 1));
    $boxDisplay = ($totalDaysOfMonthDisplay <= 35) ? 35 : 42;

    $prevMonth = date("m", strtotime('-1 month', strtotime($date)));
    $prevYear = date("Y", strtotime('-1 month', strtotime($date)));
    $totalDaysOfMonth_Prev = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);
    ?> 

    <main class="calendar-contain"> 
        <section class="title-bar"> 
            <div class="title-bar__month"> 
                <select class="month-dropdown form-control"> 
                    <?php echo getMonthList($dateMonth); ?> 
                </select> 
            </div> 
            <div class="title-bar__year"> 
                <select class="year-dropdown form-control"> 
                    <?php echo getYearList($dateYear); ?> 
                </select> 
            </div> 
        </section> 

        <aside class="calendar__sidebar" id="event_list"> 
            <?php echo getPending('', $userSQL); ?> 
        </aside> 

        <section class="calendar__days"> 
            <section class="calendar__top-bar"> 
                <span class="top-bar__days">Mon</span> 
                <span class="top-bar__days">Tue</span> 
                <span class="top-bar__days">Wed</span> 
                <span class="top-bar__days">Thu</span> 
                <span class="top-bar__days">Fri</span> 
                <span class="top-bar__days">Sat</span> 
                <span class="top-bar__days">Sun</span> 
            </section> 

            <?php
            $dayCount = 1;
            $eventNum = 0;

            echo '<section class="calendar__week">';
            for ($cb = 1; $cb <= $boxDisplay; $cb++) {
                if (($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)) {
                    // Current date 
                    $currentDate = $dateYear . '-' . $dateMonth . '-' . $dayCount;

                    // Get number of events based on the current date 
                    global $db;
                    $eventCountSQL = "";
                    $eventCountSQL .= "SELECT id, rm_id AS code, due_date, 'S' as tab_tag from risk_management WHERE $userSQL due_date = '" . $currentDate . "' AND status NOT IN ('1','-1')";
                    $eventCountSQL .= "UNION SELECT id, mng_id AS code, due_date, 'N' as tab_tag from mng_cmp WHERE $userSQL due_date = '" . $currentDate . "' AND status NOT IN ('1','-1')";
                    $result = $db->query($eventCountSQL);
                    $eventNum = $result->num_rows;

                    // Define date cell color 
                    if (strtotime($currentDate) == strtotime(date("Y-m-d"))) {
                        ?>
                        <div class="calendar__day today" onclick="getPending('<?php echo $currentDate; ?>', document.getElementById('assuser').value);">
                            <span class="calendar__date"><?php echo $dayCount; ?></span> 
                            <span class="calendar__task calendar__task--today">
                                <?php
                                if ($eventNum > 0) {
                                    echo $eventNum . ' due';
                                } else {
                                    echo 'No tasks';
                                }
                                ?></span> 
                        </div>
                        <?php
                    } elseif ($eventNum > 0) {
                        if (strtotime($currentDate) > strtotime(date("Y-m-d"))) {
                            ?>
                            <div class="calendar__day due" onclick="getPending('<?php echo $currentDate; ?>', document.getElementById('assuser').value);">
                                <span class="calendar__date"><?php echo $dayCount; ?></span> 
                                <span class="calendar__task"><?php echo $eventNum; ?> due</span> 
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="calendar__day overdue" onclick="getPending('<?php echo $currentDate; ?>',document.getElementById('assuser').value);">
                                <span class="calendar__date"><?php echo $dayCount; ?></span> 
                                <span class="calendar__task"><?php echo $eventNum; ?> overdue</span> 
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="calendar__day no-event" onclick="getPending('<?php echo $currentDate; ?>', document.getElementById('assuser').value);">
                            <span class="calendar__date"><?php echo $dayCount; ?></span> 
                            <span class="calendar__task">No tasks </span> 
                        </div>
                        <?php
                    }
                    $dayCount++;
                } else {
                    if ($cb < $currentMonthFirstDay) {
                        $inactiveCalendarDay = ((($totalDaysOfMonth_Prev - $currentMonthFirstDay) + 1) + $cb);
                        $inactiveLabel = 'expired';
                    } else {
                        $inactiveCalendarDay = ($cb - $totalDaysOfMonthDisplay);
                        $inactiveLabel = 'upcoming';
                    }
                    echo ' 
                            <div class="calendar__day inactive"> 
                                <span class="calendar__date">' . $inactiveCalendarDay . '</span> 
                                <span class="calendar__task">' . $inactiveLabel . '</span> 
                            </div> 
                        ';
                }
                echo ($cb % 7 == 0 && $cb != $boxDisplay) ? '</section><section class="calendar__week">' : '';
            }
            echo '</section>';
            ?> 
        </section> 
    </main> 

    <script>
        function getCalendar(target_div, year, month, ass_user) {
            $.ajax({
                type: 'POST',
                url: 'calender_functions.php',
                data: 'func=getCalender&year=' + year + '&month=' + month + '&ass_user=' + ass_user,
                success: function (html) {
                    $('#' + target_div).html(html);
                }
            });
        }

        function getPending(date, ass_user) {
            $.ajax({
                type: 'POST',
                url: 'calender_functions.php',
                data: 'func=getPending&date=' + date + '&ass_user=' + ass_user,
                success: function (html) {
                    $('#event_list').html(html);
                }
            });
        }

        $(document).ready(function () {
            $('.month-dropdown').on('change', function () {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), $("#assuser").val());
            });
            $('.year-dropdown').on('change', function () {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), $("#assuser").val());
            });
        });
    </script> 
    <?php
}

/*
 * Generate months options list for select box 
 */

function getMonthList($selected = '') {
    $options = '';
    for ($i = 1; $i <= 12; $i++) {
        $value = ($i < 10) ? '0' . $i : $i;
        $selectedOpt = ($value == $selected) ? 'selected' : '';
        $options .= '<option value="' . $value . '" ' . $selectedOpt . ' >' . date("F", mktime(0, 0, 0, $i + 1, 0, 0)) . '</option>';
    }
    return $options;
}

/*
 * Generate years options list for select box 
 */

function getYearList($selected = '') {
    $yearInit = !empty($selected) ? $selected : date("Y");
    $yearPrev = ($yearInit - 5);
    $yearNext = ($yearInit + 5);
    $options = '';
    for ($i = $yearPrev; $i <= $yearNext; $i++) {
        $selectedOpt = ($i == $selected) ? 'selected' : '';
        $options .= '<option value="' . $i . '" ' . $selectedOpt . ' >' . $i . '</option>';
    }
    return $options;
}

/*
 * Generate events list in HTML format 
 */

function getPending($date = '', $userSQL = '') {
    $date = $date ? $date : date("Y-m-d");

    $eventListHTML = '<h2 class="sidebar__heading">' . date("l", strtotime($date)) . '<br>' . date("F d", strtotime($date)) . '</h2>';

    // Fetch events based on the specific date 
    global $db;
    $eventSQL = "";
    $eventSQL .= "SELECT id, rm_id AS code, due_date, 'S' as tab_tag from risk_management WHERE $userSQL due_date = '" . $date . "' AND status NOT IN ('1','-1')";
    $eventSQL .= "UNION SELECT id, mng_id AS code, due_date, 'N' as tab_tag from mng_cmp WHERE $userSQL due_date = '" . $date . "' AND status NOT IN ('1','-1')";
    $result = $db->query($eventSQL);
    if ($result->num_rows > 0) {
        $eventListHTML .= '<ul class="sidebar__list">';
        $eventListHTML .= '<li class="sidebar__list-item sidebar__list-item--complete" style="color:#000000;">Pending</li>';
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $i++;
            if ($row['tab_tag'] == "S") {
                $link = "risk_management_details.php?link=home.php&&rm_id=" . $row['id'];
            } else {
                $link = "mng_cmp_details.php?link=home.php&&mng_id=" . $row['id'];
            }
            $eventListHTML .= '<li class="sidebar__list-item" style="font-weight:bolder;"><a href=' . $link . ' class="list-item__time">' . $i;
            $eventListHTML .= '. ' . $row['code'] . '</a></li>';
        }
        $eventListHTML .= '</ul>';
    }
    echo $eventListHTML;
}
?>