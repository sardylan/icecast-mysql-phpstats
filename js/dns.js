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


function updateHostnameSpan(text, obj)
{
    if(text.length > 0)
        obj.html(text);
}

function obtainHostNames() {
    $("#res_table .res_table_ip > a").each(function(){

        obj = $(this).parent().children("span.small_agent");
        ip = this.innerText;

        $.ajax({
            context: obj,
            type: "GET",
            url: "dns.php",
            data: "ip=" + ip,
            success: function(response) {
                updateHostnameSpan(response, this);
            }
        });


    });
}
