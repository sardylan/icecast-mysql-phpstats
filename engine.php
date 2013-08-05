<?php

/*
 * PHP Site for Icecast MySQL Stats
 * Copyright (C) 2013  Luca Cireddu
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * You can also find an on-line copy of this license at:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Created and mantained by: Luca Cireddu
 *                           sardylan@gmail.com
 *                           http://www.lucacireddu.it
 *
 */


$page_path = "";
$cwd = getcwd();
$site_root = substr(getcwd(), 0, strlen($cwd) - strlen($page_path));

require_once($site_root . "/includes/head.php");

// action=text&start=" + $("#search_start").val() + "&stop=" + $("#search_stop").val() + "&mountpoints=" + $("#mounts_select").val().join(),

$action = my_get("action");

$engine_start = strtotime(my_get("start") . ":00");
$engine_stop = strtotime(my_get("stop") . ":59");

if($action == "text") {
    $ret["start"] = strftime("%Y-%m-%d %H:%I:%S", $engine_start);
    $ret["stop"] = strftime("%Y-%m-%d %H:%I:%S", $engine_stop);
    $ret["days"] = round(($engine_stop - $engine_start) / (60*60*24), 2);
    $ret["listeners"] = "";
    $ret["maxonlinetime"] = "";
    $ret["minonlinetime"] = "";
    $ret["aveonlinetime"] = "";

    $sql_condition_time = "FROM stats WHERE stop > FROM_UNIXTIME({$engine_start}) AND start < FROM_UNIXTIME({$engine_stop}) AND duration > 30";
    $sql_limit = "LIMIT 0, 30";

    $sql_query = "SELECT COUNT(id) AS listeners, MAX(duration) AS maxonlinetime, MIN(duration) AS minonlinetime {$sql_condition_time} ORDER BY duration DESC {$sql_limit}";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                $ret["listeners"] = $sql_data["listeners"];
                $ret["maxonlinetime"] = strftime("%H:%M:%S", (int) ($sql_data["maxonlinetime"]));
                $ret["minonlinetime"] = strftime("%H:%M:%S", (int) ($sql_data["minonlinetime"]));
            }

    $sql_query = "SELECT COUNT(id) AS listeners, SUM(duration) AS sum {$sql_condition_time}";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["aveonlinetime"] = strftime("%H:%M:%S", (int) ($sql_data["sum"] / $sql_data["listeners"]));

    header("Content-type: application/json");
    header("Cache-Control: no-cache, must-revalidate");

    echo json_encode($ret);
}

?>