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
 * Many of these functions are created by Paolo Cortis (paolo.cortis@gmail.com)
 *
 */


define("OUTPUT_WINDOWS", "<img src=\"img/icons/windows.png\" alt=\"Windows\" title=\"Windows\" />");
define("OUTPUT_MAC", "<img src=\"img/icons/mac.png\" alt=\"Mac\" title=\"Mac\" />");
define("OUTPUT_LINUX", "<img src=\"img/icons/linux.png\" alt=\"Linux\" title=\"Linux\" />");
define("OUTPUT_IOS", "<img src=\"img/icons/ios.png\" alt=\"iOS\" title=\"iOS\" />");
define("OUTPUT_ANDROID", "<img src=\"img/icons/android.png\" alt=\"Android\" title=\"Android\" />");

define("OUTPUT_FIREFOX", "<img src=\"img/icons/firefox.png\" alt=\"Firefox\" title=\"Firefox\" />");
define("OUTPUT_GOOGLECHROME", "<img src=\"img/icons/googlechrome.png\" alt=\"Google Chrome\" title=\"Google Chorme\" />");
define("OUTPUT_SAFARI", "<img src=\"img/icons/safari.png\" alt=\"Safari\" title=\"Safari\" />");
define("OUTPUT_OPERA", "<img src=\"img/icons/opera.png\" alt=\"Opera\" title=\"Opera\" />");
define("OUTPUT_IE6", "<img src=\"img/icons/ie6.png\" alt=\"Internet Explorer 6\" title=\"Internet Explorer 6\" />");
define("OUTPUT_IE7", "<img src=\"img/icons/ie7.png\" alt=\"Internet Explorer 7\" title=\"Internet Explorer 7\" />");
define("OUTPUT_IE8", "<img src=\"img/icons/ie8.png\" alt=\"Internet Explorer 8\" title=\"Internet Explorer 8\" />");
define("OUTPUT_IE9", "<img src=\"img/icons/ie9.png\" alt=\"Internet Explorer 9\" title=\"Internet Explorer 9\" />");
define("OUTPUT_IE10", "<img src=\"img/icons/ie10.png\" alt=\"Internet Explorer 10\" title=\"Internet Explorer 10\" />");

define("OUTPUT_ICECAST", "<img src=\"img/icons/icecast.png\" alt=\"Icecast\" title=\"Icecast\" />");

define("OUTPUT_ITUNES", "<img src=\"img/icons/itunes.png\" alt=\"iTunes\" title=\"iTunes\" />");
define("OUTPUT_WMP", "<img src=\"img/icons/wmp.png\" alt=\"Windows Media Player\" title=\"Windows Media Player\" />");
define("OUTPUT_VLC", "<img src=\"img/icons/vlc.png\" alt=\"VLC\" title=\"VLC\" />");
define("OUTPUT_WINAMP", "<img src=\"img/icons/winamp.png\" alt=\"Winamp\" title=\"Winamp\" />");

define("OUTPUT_SAMSUNGSMARTTV", "<img src=\"img/icons/samsungsmarttv.png\" alt=\"Samsung Smart TV\" title=\"Samsung Smart TV\" />");
define("OUTPUT_TUNEIN", "<img src=\"img/icons/tunein.png\" alt=\"TuneIn\" title=\"TuneIn\" />");

define("OUTPUT_BOT", "<img src=\"img/icons/bot.png\" alt=\"Search Engine Bot\" title=\"Search Engine Bot\" />");

function cleanUserAgent($input = "")
{
    return urldecode(html_entity_decode($input));
}

function parseUserAgent($input = "")
{
    $ret = "";

    if(strlen($input) > 0) {
        $ret = $input;

        if($input == "Lavf52.111.0") {
            $ret = OUTPUT_TUNEIN;
        } else if($input == "RMA/1.0 (compatible; RealMedia)") {
            $ret = "WiFi Radio <strong>(not sure)</strong>";
        } else if(preg_match("/bot/i", $input)) {
            $ret = OUTPUT_BOT;
        } else if(preg_match("/SMART-TV/i", $input)) {
            $ret = OUTPUT_SAMSUNGSMARTTV;
        } else if(preg_match("/^iTunes/i", $input)) {
            $ret = OUTPUT_ITUNES;
            if(preg_match("/Mac/i", $input)) {
                $ret .= OUTPUT_MAC;
            } else if(preg_match("/win/i", $input)) {
                $ret .= OUTPUT_WINDOWS;
            }
        } else if(preg_match("/Gecko\//i", $input)) {
            if(preg_match("/firefox/i", $input)) {
                $ret = OUTPUT_FIREFOX;
                if(preg_match("/win/i", $input)) {
                    $ret .= OUTPUT_WINDOWS;
                } else if(preg_match("/mac/i", $input)) {
                    $ret .= OUTPUT_MAC;
                } else if(preg_match("/linux/i", $input)) {
                    $ret .= OUTPUT_LINUX;
                }
            }
        } else if(preg_match("/Chrome\//i", $input)) {
            $ret = OUTPUT_GOOGLECHROME;
            if(preg_match("/win/i", $input)) {
                $ret .= OUTPUT_WINDOWS;
            } else if(preg_match("/mac/i", $input)) {
                $ret .= OUTPUT_MAC;
            } else if(preg_match("/linux/i", $input)) {
                $ret .= OUTPUT_LINUX;
            }
        } else if(preg_match("/msie/i", $input)) {
            if(preg_match("/msie 6/i", $input)) {
                $ret = OUTPUT_IE6;
            } else if(preg_match("/msie 7/i", $input)) {
                $ret = OUTPUT_IE7;
            } else if(preg_match("/msie 8/i", $input)) {
                $ret = OUTPUT_IE8;
            } else if(preg_match("/msie 9/i", $input)) {
                $ret = OUTPUT_IE9;
            } else if(preg_match("/msie 10/i", $input)) {
                $ret = OUTPUT_IE10;
            }
        } else if(preg_match("/safari/i", $input)) {
            $ret = OUTPUT_SAFARI;
            if(preg_match("/win/i", $input)) {
                $ret .= OUTPUT_WINDOWS;
            } else if(preg_match("/mac/i", $input)) {
                $ret .= OUTPUT_MAC;
            }
        } else if(preg_match("/opera/i", $input)) {
            $ret = OUTPUT_OPERA;
            if(preg_match("/win/i", $input)) {
                $ret .= OUTPUT_WINDOWS;
            } else if(preg_match("/mac/i", $input)) {
                $ret .= OUTPUT_MAC;
            } else if(preg_match("/linux/i", $input)) {
                $ret .= OUTPUT_LINUX;
            }
        } else if(preg_match("/xbmc\//i", $input)) {
            $ret = "XBMC";
            if(preg_match("/win/i", $input)) {
                $ret .= OUTPUT_WINDOWS;
            } else if(preg_match("/mac/i", $input)) {
                $ret .= OUTPUT_MAC;
            } else if(preg_match("/linux/i", $input)) {
                $ret .= OUTPUT_LINUX;
                if(preg_match("/x86/i", $input)) {
                    $ret .= " PC";
                } else if(preg_match("/arm/i", $input)) {
                    $ret .= " ARM";
                }
            }
        } else if(preg_match("/CFNetwork/i", $input)) {
            $ret = OUTPUT_IOS;
        } else if(preg_match("/^icecast/i", $input)) {
            $ret = OUTPUT_ICECAST;
        } else if(preg_match("/wowza/i", $input)) {
            $ret = "Wowza Media Server";
        } else if(preg_match("/android/i", $input)) {
            $ret = OUTPUT_ANDROID;
        } else if(preg_match("/AppleCoreMedia/i", $input)) {
            $ret = "App iPhone";
        } else if(preg_match("/WMFSDK/i", $input) || preg_match("/NSPlayer/i", $input)) {
            $ret = OUTPUT_WMP;
        } else if(preg_match("/MPlayer/i", $input)) {
            $ret = "MPlayer";
        } else if(preg_match("/vlc/i", $input)) {
            $ret = OUTPUT_VLC;
        } else if(preg_match("/Winamp/i", $input)) {
            $ret = OUTPUT_WINAMP;
        } else if(preg_match("/MB Player/i", $input)) {
            $ret = "MB Player";
        } else if(preg_match("/Audacious/i", $input)) {
            $ret = "Audacious";
        } else if(preg_match("/psp/i", $input)) {
            $ret = "PlayStation Portable";
        } else if(preg_match("/BlackBerry/i", $input)) {
            $ret = "BlackBerry";
        } else if(preg_match("/nokia/i", $input)) {
            $ret = "Nokia Phone";
            if(preg_match("/series60/i", $input)) {
                $ret .= " Series 60";
            }
        } else if(preg_match("/bot/i", $input)) {
            $ret = "Bot";
            if(preg_match("/google/i", $input)) {
                $ret .= " Google";
            }
        }
    }

    return $ret;
}

?>