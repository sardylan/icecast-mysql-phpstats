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
require_once($site_root . "/includes/useragent.php");

$sql_limit = "LIMIT 0, 30";

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

    $sql_condition_time = "WHERE stop > FROM_UNIXTIME({$engine_start}) AND start < FROM_UNIXTIME({$engine_stop}) AND duration > 30";
    $sql_blacklist = "AND ip NOT IN (SELECT ip FROM ipblacklist)";

    $sql_query = "SELECT COUNT(id) AS listeners, MAX(duration) AS maxonlinetime, MIN(duration) AS minonlinetime FROM stats {$sql_condition_time} {$sql_blacklist} ORDER BY duration DESC {$sql_limit}";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                $ret["listeners"] = $sql_data["listeners"];
                $ret["maxonlinetime"] = strftime("%H:%M:%S", (int) ($sql_data["maxonlinetime"]));
                $ret["minonlinetime"] = strftime("%H:%M:%S", (int) ($sql_data["minonlinetime"]));
            }

    $sql_query = "SELECT COUNT(id) AS listeners, SUM(duration) AS sum FROM stats {$sql_condition_time} {$sql_blacklist}";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["aveonlinetime"] = strftime("%H:%M:%S", (int) ($sql_data["sum"] / $sql_data["listeners"]));

    header("Content-type: application/json");
    header("Cache-Control: no-cache, must-revalidate");

    echo json_encode($ret);
}

if($action == "mountpoints") {
    $ret = "";

    $sql_condition_time = "WHERE tta.stop > FROM_UNIXTIME({$engine_start}) AND tta.start < FROM_UNIXTIME({$engine_stop}) AND tta.duration > 30";
    $sql_blacklist = "AND tta.ip NOT IN (SELECT ip FROM ipblacklist)";
    $sql_limit = "LIMIT 0, 30";

    $sql_query = "SELECT COUNT(tta.id) AS listeners, ttb.mount AS mount FROM stats tta, mountpoints ttb {$sql_condition_time} {$sql_blacklist} AND tta.mount = ttb.id GROUP BY mount";

    error_log($sql_query);

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            while($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret .= "<li>{$sql_data["mount"]} ({$sql_data["listeners"]})</li>";

    header("Content-type: text/html");
    header("Cache-Control: no-cache, must-revalidate");

    echo $ret;
}

if($action == "table") {
    $ret = "";

    $sql_condition_time = "WHERE tta.stop > FROM_UNIXTIME({$engine_start}) AND tta.start < FROM_UNIXTIME({$engine_stop}) AND tta.duration > 30";
    $sql_blacklist = "AND tta.ip NOT IN (SELECT ip FROM ipblacklist)";
    $sql_limit = "";

    $sql_order = "ORDER BY duration DESC";

    $sql_query = "SELECT tta.ip AS ip, tta.agent AS agent, ttb.mount AS mount, tta.start AS start, tta.stop AS stop, tta.duration AS duration FROM stats tta, mountpoints ttb {$sql_condition_time} {$sql_blacklist} AND tta.mount = ttb.id {$sql_order} {$sql_limit}";

    error_log($sql_query);

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            while($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                $user_agent = cleanUserAgent($sql_data["agent"]);
                $user_agent_parsed = parseUserAgent($user_agent);

                $ret .= "<tr>";
                $ret .= "<td class=\"res_table_ip\"><a href=\"http://www.geoiptool.com/?IP={$sql_data["ip"]}\">{$sql_data["ip"]}</a></td>";
                $ret .= "<td class=\"res_table_mount\">{$sql_data["mount"]}</td>";

//                 if($user_agent_parsed == $user_agent)
//                     $ret .= "<td class=\"res_table_agent\">{$user_agent}</td>";
//                 else
//                     $ret .= "<td class=\"res_table_agent\">{$user_agent_parsed}<br /><span class=\"small_agent\">{$user_agent}</span></td>";

                $ret .= "<td class=\"res_table_agent\">{$user_agent_parsed}</td>";

                $ret .= "<td class=\"res_table_startstop\">{$sql_data["start"]}<br />{$sql_data["stop"]}</td>";
                $ret .= "<td class=\"res_table_duration\">" . strftime("%H:%I:%S", $sql_data["duration"]) . "</td>";
                $ret .= "</tr>";
            }

    header("Content-type: text/html");
    header("Cache-Control: no-cache, must-revalidate");

    echo $ret;
}

?>