<?php

$db_debug = 1; # 0 - None, 1 - Errors, 2 - Add Info

//$db = db_connect($dbname,$hostname,$username,$password);

function db_connect($db_name, $db_host, $db_user, $db_pass)
{
    //$db['name'] = $db_name;
    @$db = @mysqli_connect($db_host, $db_user, $db_pass);
    if (!$db)
        die("Cannot connect to server ($db_user@$db_host): " . mysqli_error($db));
    if (!mysqli_select_db($db, $db_name)) {
        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET = utf8 COLLATE utf8_general_ci";
        if (mysqli_query($db, $sql)) {
            echo "Create Database: '$db_name'\r\n<br/>";
        }
    }
    @mysqli_select_db($db, $db_name) || die("Cannot select database ($db_name): " .
        mysql_error());
    return $db;
}

function db_query($db, $table, $where = "", $order = "")
{
    global $db_debug;

    $sql = "SELECT * FROM $table";
    if ($where)
        $sql .= " WHERE $where";
    if ($order)
        $sql .= " ORDER BY $order";

    if ($db_debug > 1)
        print "query sql = $sql<br>\n";

    //$res = mysqli_db_query($db['name'], $sql, $db);
    $res = mysqli_query($db, $sql);
    if (!$res) {
        if ($db_debug > 0) {
            die("Query Error ($sql) : " . mysqli_error($db));
        } else {
            return 0;
        }
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = $row;
    }
    if (!isset($rows))
        return false;
    return $rows;
}

function db_sql_query($db, $sql, $explode = false)
{
    global $db_debug;

    if ($db_debug > 1)
        print "sql query sql=" . nl2br($sql) . "<br>\n";

    if ($explode) {
        $sqls = explode(";\r\n", $sql);
    } else {
        $sqls[] = $sql;
    }

    $res = 1;

    if (is_array($sqls)) {
        foreach ($sqls as $ssql) {
            if ($ssql) {
                //$res = mysqli_db_query($db['name'], $ssql, $db);
                $res = mysqli_query($db, $ssql);
                if (!$res) {
                    if ($db_debug > 0) {
                        die("SQL Query Error ($ssql) : " . mysqli_error($db));
                    } else {
                        return 0;
                    }
                }
            }
        }
    } else {
        if ($db_debug > 0) {
            die("SQL Query Error ($sql) : Empty SQL");
        } else {
            return 0;
        }
    }

    if ($res !== false) {
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
    }
    if (!isset($rows))
        return false;
    return $rows;
}

function db_count($db, $sql)
{
    global $db_debug;

    if ($db_debug > 1)
        print "count sql=$sql<br>\n";

    if (!eregi("select", $sql)) {
        if ($db_debug > 0)
            die("ERROR: in sql ($sql) not present SELECT tag!\n");
        return - 1;
    }

    //$res = mysqli_db_query($db['name'], $sql, $db);
    $res = mysqli_query($db, $sql);
    if (!$res) {
        if ($db_debug > 0) {
            die("SQL Query Error ($sql) : " . mysqli_error($db));
        } else {
            return - 1;
        }
    }

    return mysqli_num_rows($res);
}


function db_insert($db, $table, $rec)
{
    global $db_debug;

    $names = "";
    $values = '';
    $value = '';
    foreach ($rec as $key => $value) {
        $value = ereg_replace("[\\]", "\\\\", $value);
        $value = ereg_replace("'", "\\'", $value);
        #$value=addslashes($value);
        $names .= ($names ? ", " : "") . "$key";
        if (ereg("^ex:(.*)$", $value, $arr)) {
            $values .= ($values ? ", " : "") . "$arr[1]";
        } else {
            $values .= ($values ? ", " : "") . "'$value'";
        }
    }
    $sql = "INSERT INTO $table ($names) VALUES ($values)";
    if ($db_debug > 1)
        print "insert sql=$sql<br>\n";

    //$res = mysqli_db_query($db['name'], $sql, $db);
    $res = mysqli_query($db, $sql);

    if (!$res) {
        if ($db_debug > 0) {
            die("Insert Error ($sql) : " . mysqli_error($db));
        } else {
            return 0;
        }
    }
    if (mysqli_insert_id() != 0) {
        return mysqli_insert_id();
    } else {
        return $res;
    }
}

function db_update($db, $table, $where, $rec)
{
    global $db_debug;
    $set = '';
    foreach ($rec as $key => $value) {
        if (ereg("^ex:(.*)$", $value, $arr)) {
            $set .= ($set ? ", " : "") . "$key=$arr[1]";
        } else {
            $value = ereg_replace("[\\]", "\\\\", $value);
            $value = ereg_replace("'", "\\'", $value);
            $set .= ($set ? ", " : "") . "$key='$value'";
        }
    }

    $sql = "UPDATE $table SET $set";
    if ($where)
        $sql .= " WHERE $where";

    if ($db_debug > 1)
        print "update sql=$sql<br>\n";
    //$res = mysqli_db_query($db['name'], $sql, $db);
    $res = mysqli_query($db, $sql);
    if (!$res) {
        if ($db_debug > 0) {
            die("Update Error ($sql) : " . mysqli_error($db));
        } else {
            return 0;
        }
    }
    return $res;
}

function db_delete($db, $table, $where = "")
{
    global $db_debug;

    $sql = "DELETE FROM $table";
    if ($where)
        $sql .= " WHERE $where";

    if ($db_debug > 1)
        print "delete sql=$sql<br>\n";

    //$res = mysqli_db_query($db['name'], $sql, $db);
    $res = mysqli_query($db, $sql);
    if (!$res) {
        if ($db_debug > 0) {
            die("Delete Error ($sql) : " . mysqli_error($db));
        } else {
            return 0;
        }
    }
    return $res;
}

function db_close($db)
{
    mysqli_close($db);
}

function db_create($db_name, $db_host, $db_user, $db_pass)
{
    @$db = @mysqli_connect($db_host, $db_user, $db_pass);
    if (!$db)
        die("Cannot connect to server ($db_user@$db_host): " . mysqli_error($db));
    @mysqli_query($db, "CREATE DATABASE $db_name;") || die("Cannot create database ($db_name): " .
        mysqli_error($db));
    ;
    @mysqli_select_db($db, $db_name) || die("Cannot select database ($db_name): " .
        mysqli_error($db));
    return $db;
}

?>
