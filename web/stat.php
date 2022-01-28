<?php

///////////////////////////////////////////////////////////////////////
session_start();

///////////////////////////////////////////////////////////////////////


header("Cache-Control: no-cache");
//header("Content-Type: text/html; charset=utf-8");

ini_set('log_errors', 'on');
ini_set('error_log', 'error_log.txt');

error_reporting(1);

$parser_id = 'trademarkia.com';

require_once ("config.inc.php");

// ------------------------------------------------------------------------
$this_user_name = $_SESSION[user_name];
$this_user_user_pass = $_SESSION[user_pass];
$this_user = _user_info($this_user_name, $this_user_user_pass);

$this_user_id = $this_user[id];
$this_user_access_col_read = explode(',', $this_user[user_access_col_read]);
$this_user_access_col_write = explode(',', $this_user[user_access_col_write]);

$index_tpl = 'tpl/stat_admin.html';

// ------------------------------------------------------------------------

$result[reset_filtr_display] = 'none';
$result[find_txt] = '';

// -------------------------------------------------------------------
if (isset($_GET[page_str])) {
    //('page_str');
    $_SESSION[page_str] = (int)$_GET[page_str];
}

/////////////////////////////////////////////////////////////////////////////////////////////
// очистка базы данных
if (isset($_GET[db_clear])) {
    $delete_count_days = intval($_GET['delete_count_days']);

    if ($delete_count_days) {
        $row1 = db_sql_query($db, "SELECT * FROM `$db_table_stat` ORDER BY `date_add` LIMIT 1");

        if (!empty($row1)) {
            $dt1 = date_create($row1[0]['date_add']);

            if ($dt1) {
                $date_limit = date('Y-m-d', ($dt1->getTimestamp()-86400) + ($delete_count_days * 86400)) .
                    ' 23:59:59';

                db_sql_query($db, "DELETE FROM `$db_table_stat` WHERE `date_add` <= '$date_limit'");
            }
        }

        $result[error] = 'База статистики очищена за ' . $_GET['delete_count_days'] .
            ' дней!';
    } else {
        $sql = "TRUNCATE $db_table_stat";
        db_sql_query($db, $sql);

        $result[error] = 'База статистики очищена!';
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////
// инициализация страницы

// всего
$sql = "SELECT COUNT(*) as count FROM $db_table";
$rows = db_sql_query($db, $sql);
$result['resume.total'] = (int)$rows[0][count];

$day = date("N");
$this_monday = date("Y-m-d", time() - (($day - 1) * 60 * 60 * 24));
$last_monday = date("Y-m-d", time() - ((($day - 1) * 60 * 60 * 24)) - (7 * 60 *
    60 * 24));

$this_date = date("Y-m-d");

// за текущую неделю
$sql = "SELECT COUNT(*) as count FROM $db_table WHERE `date_add` >= '$this_monday 00:00' and `date_add` <= '$this_date 23:59:59'";
$rows = db_sql_query($db, $sql);
$result['resume.this_week'] = (int)$rows[0][count];
// за прошлую неделю
$sql = "SELECT COUNT(*) as count FROM $db_table WHERE `date_add` >= '$last_monday 00:00' and `date_add` <= '$this_monday 00:00'";
$rows = db_sql_query($db, $sql);
$result['resume.last_week'] = (int)$rows[0][count];

// за день
$sql = "SELECT COUNT(*) as count FROM $db_table WHERE `date_add` >= '$this_date 00:00' and `date_add` <= '$this_date 23:59:59'";
$rows = db_sql_query($db, $sql);
$result['resume.daily'] = (int)$rows[0][count];

$result['indeed.com.daily'] = get_daily_parse('', $this_date);

$_GET[page_str] = 50;
if (isset($_SESSION[page_str]))
    $_GET[page_str] = $_SESSION[page_str];
$page_str = $_GET[page_str];

for ($i = 0; $i <= 5; $i++) {
    $val = ($i * 2) * 50;
    if ($val == 0)
        $val = 50;
    if ($_GET[page_str] == $val)
        $val_sel = 'selected';
    else
        $val_sel = '';
    $result[page_str_list] .= '<option value="' . $val . '" ' . $val_sel . '>' . $val .
        '</option>';
}

$page = (int)$_GET[page];
if ($page == 0) {
    if (isset($_GET[direction]) && $_GET[direction] == 'ASC') {
        $direction = 'DESC';
    } else {
        $direction = 'ASC';
    }
} else {
    $direction = $_GET[direction];
}

if (empty($direction))
    $direction = 'ASC';

$argv = $_POST + $_GET;
if (isset($argv[find]) && $argv[find_txt] != '') {
    $result[find_txt] = $argv[find_txt];
    $search = $argv[find_txt];

    $sql = "SELECT * FROM `$db_table_stat` WHERE title LIKE '%$search%' order by `id` desc";
    $rows = db_sql_query($db, $sql);

    $result[reset_filtr_display] = 'display';
} else {
    $pager = pager($db, $db_table_stat, $page, $page_str);

    $sql = "SELECT * FROM `$db_table_stat`";
    if (isset($_GET[order_by]) && $_GET[order_by] != '') {
        $order_by = strip_tags($_GET[order_by]);
    } else {
        $order_by = 'id';
        $direction = 'desc';
    }
    $sql .= " ORDER BY $order_by $direction";

    $sql .= " LIMIT " . $pager[start] . ", " . $pager[end];

    $rows = db_sql_query($db, $sql);
    //printr($rows);
}

$data = '';
foreach ($rows as $key => $val) {
    $date_add = $val[date_add];
    $date_update = $val[date_update];
    
    $dt1 = date_create($date_add);
    $dt2 = date_create($date_update);
    
    $date_1 = $date_add;
    $date_2 = '';
    
    if ($dt1) {
        $date_add = $dt1->format('d.m.Y');
        $date_1 = $dt1->format('d.m.Y');
        $date_2 = $dt1->format('H:i');
    }
    if ($dt2) {
        $date_update = $dt2->format('d.m.Y');
    }
    
    $i++;
    if ($i % 2 == 0) {
        $class = 'class="c1"';
    } else {
        $class = '';
    } // четное/нет

    if ($val[img] != '')
        $val[img] = '<a href="contents/' . $val[img] . '" target="_blank">[Фото]</a>';

    $data .= '<tr ' . $class . '>
                    <td style="text-align: center;">' . $date_1 . '<br/>' . $date_2 .
        '</td>
                    <td style="white-space: nowrap;">' . ($val[profile] ? $val[profile] : $val[parser_id]) .
        '</td>
                    <td>' . $val[title] . '</td>
                    <td>' . $val[browser] . '</td>
                </tr>';
}
$data .= '</table>';

$result[db_data] = $data;
$result[direction] = $direction;

//////////////////////////////////////////////////////////////////////////////////////////////////////
// формирование навигации по страницам

// первая стр
if ($pager[page] > $pager_count + 1) {
    $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
        array(
        'page_num' => '1',
        'order_by' => $order_by,
        'direction' => $direction)) . $pager_divider;
    if ($pager[page] > ($pager_count + 2))
        $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
            array(
            'page_num' => '...',
            'order_by' => $order_by,
            'direction' => $direction)) . $pager_divider;
}
// страницы до и после
for ($i = ($pager[page] - $pager_count); $i <= ($pager[page] + $pager_count); $i++) {
    if ($i > 0 && $i <= $pager[page_count]) {
        if ($i != $pager[page]) {
            $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
                array(
                'page_num' => $i,
                'order_by' => $order_by,
                'direction' => $direction)) . $pager_divider;
        } else {
            $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_active.tpl',
                array(
                'page_num' => $i,
                'order_by' => $order_by,
                'direction' => $direction)) . $pager_divider;
        }
    }
}
// последняя стр
if ($pager[page] < ($pager[page_count] - $pager_count)) {
    if ($pager[page] < ($pager[page_count] - ($pager_count + 1)))
        $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
            array(
            'page_num' => '...',
            'order_by' => $order_by,
            'direction' => $direction)) . $pager_divider;
    $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
        array(
        'page_num' => $pager[page_count],
        'order_by' => $order_by,
        'direction' => $direction)) . $pager_divider;
}

$result[pager] = load_template(dirname(__file__) . '/tpl/pager.tpl', $res);
//////////////////////////////////////////////////////////////////////////////////////////////////////

$row1 = db_sql_query($db, "SELECT * FROM `$db_table_stat` ORDER BY `date_add` LIMIT 1");
$row2 = db_sql_query($db, "SELECT * FROM `$db_table_stat` ORDER BY `date_add` DESC LIMIT 1");

$result['count_days'] = 0;

if (!empty($row1) && !empty($row2)) {
    if ($row1[0]['date_add'] == $row2[0]['date_add']) {
        $result['count_days'] = 1;
    } else {
        $dt1 = date_create(preg_replace("# .*$#", ' ', $row1[0]['date_add']) .
            '00:00:00');
        $dt2 = date_create(preg_replace("# .*$#", ' ', $row2[0]['date_add']) .
            '23:59:59');

        if ($dt1 && $dt2) {
            $sec = $dt2->getTimestamp() - $dt1->getTimestamp();

            if ($sec > 0) {
                $days = $sec / 86400;

                if ($days > 1) {
                    $result['count_days'] = ceil($days);
                } else {
                    $result['count_days'] = 1;
                }
            } else {
                $result['count_days'] = 1;
            }
        }
    }
}

db_close($db);

echo load_template($index_tpl, $result);

?>
