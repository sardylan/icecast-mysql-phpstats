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

?>
<?php

$page_path = "";
$cwd = getcwd();
$site_root = substr(getcwd(), 0, strlen($cwd) - strlen($page_path));

require_once($site_root . "/includes/head.php");

?>
<html>

    <head>
        <title>Icecast Stats</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3/jquery.ui.slider.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-timepicker-addon-1.3.1.js"></script>
        <script type="text/javascript" src="js/clock.js"></script>
        <script type="text/javascript" src="js/form.php"></script>
        <script type="text/javascript" src="js/footer.js"></script>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3/jquery-ui.min.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-timepicker.css" />
        <link rel="stylesheet" type="text/css" href="css/page.css" />
        <link rel="stylesheet" type="text/css" href="css/various.css" />
        <link rel="stylesheet" type="text/css" href="css/content.css" />
    </head>

    <body>
        <div id="page">
            <div class="spacer"></div>
            <div id="header">
                <div id="logo"><img src="img/logo.png" alt="Logo" title="Logo" /></div>
                <div id="header-spacer"></div>
                <div id="title">Icecast Stats</div>
            </div>
            <div class="spacer"></div>
            <form>
                <div id="querychooser">
                    <div id="chooser-mounts">
                        <select id="mounts_select" name="mounts_select" multiple="multiple" size="5">
                        </select>
                        <input type="button" id="mounts_all_select" value="Select all" />
                        <input type="button" id="mounts_all_unselect" value="Unselect all" />
                    </div>
                    <div id="chooser-buttons">
                        Rapid search:<br />
                        <input class="rapid" type="button" id="search_24h" value="24 hours" />
                        <input class="rapid" type="button" id="search_2d" value="2 days" />
                        <input class="rapid" type="button" id="search_7d" value="7 days" /><br />
                        <input class="rapid" type="button" id="search_1m" value="1 month" />
                        <input class="rapid" type="button" id="search_2m" value="2 months" />
                        <input class="rapid" type="button" id="search_1y" value="1 year" />
                    </div>
                    <div id="chooser-interval">
                        From: <input type="input" class="interval" id="search_start" name="search_start" /><br />
                        To: <input type="input" class="interval" id="search_stop" name="search_stop" /><br />
                        <input type="button" id="search_manual" value="Search listeners" />
                    </div>
                </div>
            </form>
            <div class="spacer"></div>
            <div id="content">
                <div id="summary">
                    <div id="summary-text"><p>Activites from <span id="res_start"></span> to <span id="res_stop"></span></p>
                        <p>Total days: <span id="res_days"></span><br />
                            Total listeners: <span id="res_listeners"></span><br />
                            Max on-line time: <span id="res_maxonlinetime"></span><br />
                            Min on-line time: <span id="res_minonlinetime"></span><br />
                            Average on-line time: <span id="res_aveonlinetime"></span></p>
                        <p>Mount point activity</p>
                        <ul id="resp_mountpoints_list"></ul></div>
                    <div id="summary-image"><img id="img_listeners" src="img/loading.png" alt="Listeners" title="Listeners" /></div>
                </div>
                <div class="spacer"></div>
                <div id="table">
                    <table>
                        <thead class="res_table">
                            <tr>
                                <th class="res_table_ip">IP</th>
                                <th class="res_table_mount">MOUNT</th>
                                <th class="res_table_agent">AGENT</th>
                                <th class="res_table_startstop">START<br />
                                    STOP</th>
                                <th class="res_table_duration">DURATION</th>
                            </tr>
                        </thead>
                        <tbody id="res_table" class="res_table"></tbody>
                    </table>
                </div>
            </div>
            <div id="bigspacer"></div>
        </div>
        <div id="footer">
            <div class="footer-element-left" id="footer_info"><span id="value_info"></span></div>
            <div class="footer-element-right" id="footer_clock"><span id="value_clock">Loading clock...</span></div>
            <div class="footer-element-right" id="footer_online">Online: <span id="value_online"></span></div>
            <div class="footer-element-right" id="footer_mounts">Available mountpoints on DB: <span id="value_mounts"></span></div>
            <div class="footer-element-right" id="footer_records">Total listeners in DB: <span id="value_records"></span></div>
        </div>
    </body>

</html>