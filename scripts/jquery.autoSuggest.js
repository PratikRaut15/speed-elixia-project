/*
 * PHP Ajax AutoSuggest Jquery Plugin
 * http://www.amitpatil.me/
 *
 * @version
 * 1.0 (Dec 20 2010)
 * 
 * @copyright
 * Copyright (C) 2010-2011 
 *
 * @Auther
 * Amit Patil (amitpatil321@gmail.com)
 * Maharashtra (India) m
 *
 * @license
 * This file is part of PHP Ajax AutoSuggest Jquery Plugin.
 * 
 * PHP Ajax AutoSuggest Jquery Plugin is freeware script. you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * PHP Ajax AutoSuggest Jquery Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this script.  If not, see <http://www.gnu.org/copyleft/lesser.html>.
 */
(function ($) {
    $.fn.extend({
        autoSuggest: function (options) {
            var defaults = {
                ajaxFilePath: "",
                ajaxParams: "",
                autoFill: false,
                iwidth: "auto",
                opacity: "0.9",
                ilimit: "10",
                idHolder: "",
                match: "starts"
            };
            options = $.extend(defaults, options);
            var ajaxFilePath = options.ajaxFilePath;
            var ajaxParams = options.ajaxParams;
            var autoFill = options.autoFill;
            var width = options.iwidth;
            var opacity = options.opacity;
            var limit = options.ilimit;
            var idHolder = options.idHolder;
            var match = options.match;
            var pop_param = ajaxParams.substr(ajaxParams.indexOf("=") + 1);
            var customClassName = "ajax_response";
            if (pop_param == 'alertPop') {
                customClassName = 'ajax_response_2';
            }
            if (pop_param == 'zonelist') {
                customClassName = 'ajax_response_3';
            }
            if (pop_param == 'locationlist') {
                customClassName = 'ajax_response_4';
            }
            if (pop_param == 'vehicletypelist') {
                customClassName = 'ajax_response_3';
            }
            if (pop_param == 'transporterlist') {
                customClassName = 'ajax_response_4';
            }
            if (pop_param == 'factorylist') {
                customClassName = 'ajax_response_5';
            }
            if (pop_param == 'depotlist') {
                customClassName = 'ajax_response_6';
            }
            if (pop_param == 'routelist') {
                customClassName = 'ajax_response_7';
            }
            if (pop_param == 'typelist') {
                customClassName = 'ajax_response_8';
            }
            if (pop_param == 'skulist') {
                customClassName = 'ajax_response_9';
            }

            return this.each(function () {
                var obj = $(this);
                obj.keyup(function (event) {
                    var p = obj;
                    var offset = p.offset();
                    var keyword = obj.val();
                    if (keyword.length)
                    {
                        if (event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
                        {
                            if (ajaxFilePath != "")
                            {
                                //alert("data="+keyword+"&limit="+limit+"&match="+match+"&"+ajaxParams+"&getId=1");
                                $.ajax({
                                    type: "POST",
                                    url: ajaxFilePath,
                                    data: "data=" + keyword + "&limit=" + limit + "&match=" + match + "&" + ajaxParams + "&getId=1",
                                    success: function (responce) {

                                        if (responce != 0)
                                        {
                                            var cdata1 = jQuery.parseJSON(responce);
                                            var results = cdata1;
                                            optionList = "<ul class=\"listshow\">";
                                            jQuery.each(results, function (i, device) {
                                                myId = device.checkpointid;
                                                if (pop_param == 'zonelist') {
                                                    optionList += "<li value='" + device.zoneid + "'><a href=\"javascript:void(0);\" onclick=\"fill('" + device.zoneid + "','" + device.zonename + "');\">" + device.zonename + "</a></li>";
                                                }
                                                else if (pop_param == 'locationlist') {
                                                    optionList += "<li value='" + device.locationid + "'><a href=\"javascript:void(0);\" onclick=\"fill_location('" + device.locationid + "','" + device.locationname + "');\">" + device.locationname + "</a></li>";
                                                }
                                                else if (pop_param == 'depotlist') {
                                                    optionList += "<li value='" + device.depotid + "'><a href=\"javascript:void(0);\" onclick=\"fill_depot('" + device.depotid + "','" + device.depotname + "');\">" + device.depotname + "</a></li>";
                                                }
                                                else if (pop_param == 'vehicletypelist') {
                                                    optionList += "<li value='" + device.vehicletypeid + "'><a href=\"javascript:void(0);\" onclick=\"fill_vehicletype('" + device.vehicletypeid + "','" + device.vehiclecode + "');\">" + device.vehiclecode + "</a></li>";
                                                }
                                                else if (pop_param == 'transporterlist') {
                                                    optionList += "<li value='" + device.transporterid + "'><a href=\"javascript:void(0);\" onclick=\"fill_transporter('" + device.transporterid + "','" + device.transportername + "');\">" + device.transportername + "</a></li>";
                                                }
                                                else if (pop_param == 'factorylist') {
                                                    optionList += "<li value='" + device.factoryid + "'><a href=\"javascript:void(0);\" onclick=\"fill_factory('" + device.factoryid + "','" + device.factoryname + "');\">" + device.factoryname + "</a></li>";
                                                }
                                                else if (pop_param == 'routelist') {
                                                    optionList += "<li value='" + device.routemasterid + "'><a href=\"javascript:void(0);\" onclick=\"fill_route('" + device.routemasterid + "','" + device.routename + "');\">" + device.routename + "</a></li>";
                                                }
                                                else if (pop_param == 'typelist') {
                                                    optionList += "<li value='" + device.typeid + "'><a href=\"javascript:void(0);\" onclick=\"fill_type('" + device.typeid + "','" + device.type + "');\">" + device.type + "</a></li>";
                                                }
                                                else if (pop_param == 'skulist') {
                                                    optionList += "<li value='" + device.skuid + "'><a href=\"javascript:void(0);\" onclick=\"fill_sku('" + device.skuid + "','" + device.skucode + "','" + device.sku_description + "');\">" + device.skucode + "</a></li>";
                                                }
                                                else if (pop_param == 'alertPop') {
                                                    optionList += "<li value='" + device.vehicleid + "'><a href=\"javascript:void(0);\" onclick=\"fill_pop('" + device.vehicleid + "','" + device.vehicleno + "');\">" + device.vehicleno + "</a></li>";
                                                }
                                                else if (pop_param == 'groupList') {
                                                    optionList += "<li value='" + device.groupid + "'><a href=\"javascript:void(0);\" onclick=\"fill_group('" + device.groupid + "','" + device.value + "');\">" + device.value + "</a></li>";
                                                }
                                                else if (idHolder != "" && idHolder != null)
                                                    optionList += "<li value='" + device.vehicleid + "'><a href=\"javascript:void(0);\" onclick=\"fill('" + device.vehicleid + "','" + device.vehicleno + "');\">" + device.vehicleno + "</a></li>";
                                                else
                                                    optionList += "<li value='" + device.vehicleid + "'><a href=\"javascript:void(0);\" onclick=\"fill('" + device.vehicleid + "','" + device.vehicleno + "');\">" + device.vehicleno + "</a></li>";
                                            });
                                            optionList += "</ul>";
                                            if ($("." + customClassName).html() == null)
                                            {
                                                var id = obj.attr("id");
                                                // initialization
                                                $("<div class='" + customClassName + "'></div>").insertAfter(obj)
                                                        //.css("left",parseInt($("#"+id).offset().left))
                                                        //.css("top",parseInt(offset.top + obj.height() + 3))
                                                        //.css("opacity",opacity)
                                                        .html(optionList).css("display", "block");
                                                // set responce div width
                                                if (width == "auto")
                                                    $("." + customClassName).css("width", parseInt(obj.width()) + 2);
                                                else
                                                    $("." + customClassName).css("width", parseInt(width + 2));
                                            }
                                            else
                                                $("." + customClassName).html(optionList).css("display", "block");
                                        }
                                        else
                                            $(".listshow").css("display", "none");
                                    }
                                });
                            }
                            else
                                alert("Ajax file path not provided");
                        }
                        else
                        {
                            $(".listshow li .selected").removeClass("selected");
                            switch (event.keyCode)
                            {
                                case 40:
                                    {
                                        found = 0;
                                        $(".listshow li").each(function () {
                                            if ($(this).attr("class") == "selected")
                                                found = 1;
                                        });
                                        if (found == 1)
                                        {
                                            var sel = $(".listshow li[class='selected']");
                                            // check if his is a last element in the list
                                            // if so then add selected class to the first element in the list
                                            if (sel.next().text() == "")
                                                $(".listshow li:first").addClass("selected");
                                            else
                                                sel.next().addClass("selected");
                                            // remove class selected from previous item
                                            sel.removeClass("selected");
                                        }
                                        else
                                            $(".listshow li:first").addClass("selected");
                                    }
                                    break;
                                case 38:
                                    {
                                        found = 0;
                                        $(".listshow li").each(function () {
                                            if ($(this).attr("class") == "selected")
                                                found = 1;
                                        });
                                        if (found == 1)
                                        {
                                            var sel = $(".listshow li[class='selected']");
                                            // check if his is a last element in the list
                                            // if so then add selected class to the first element in the list
                                            if (sel.prev().text() == "")
                                                $(".list li:last").addClass("selected");
                                            else
                                                sel.prev().addClass("selected");
                                            // remove class selected from previous item
                                            sel.removeClass("selected");
                                        }
                                        else
                                            $(".listshow li:last").addClass("selected");
                                    }
                                    break;
                                case 13:
                                    str = $(".listshow li[class='selected']").text();
                                    obj.val(str);
                                    vid = $(".listshow li[class='selected']").val();
                                    // store id of the selected option
                                    if (pop_param == 'zonelist') {
                                        fill(vid, str);
                                    } else if (pop_param == 'locationlist') {
                                        fill_location(vid, str);
                                    }
                                     else if (pop_param == 'depotlist') {
                                        fill_depot(vid, str);
                                    }
                                    else if (pop_param == 'alertPop') {
                                        fill_pop(vid, str);
                                    }
                                    else if (pop_param == 'vehicletypelist') {
                                        fill_vehicletype(vid, str);
                                    }
                                    else if (pop_param == 'transporterlist') {
                                        fill_transporter(vid, str);
                                    }else if (pop_param == 'factorylist') {
                                        fill_factory(vid, str);
                                    }
                                    else if (pop_param == 'routelist') {
                                        fill_route(vid, str);
                                    }
                                    else if (pop_param == 'typelist') {
                                        fill_type(vid, str);
                                    }
                                    else if (pop_param == 'skulist') {
                                        fill_sku(vid, str);
                                    }
                                    else {
                                        fill(vid, str);
                                    }

                                    $(".listshow").fadeOut("fast");
                                    //fillchk(str,vid);
                                    break;
                            }
                            // if autoFill option is true then fill selected value in textbox
                            if (autoFill)
                            {
                                str = $(".listshow li[class='selected']").text();
                                obj.val(str);
                            }
                        }
                    }
                    else
                        // if there is no character in the text field then remove the suggestion box 
                        $(".listshow").fadeOut("fast");
                });
                // prevent form submission on enter key press
                obj.keypress(function (event) {
                    if (event.keyCode == "13")
                        return false;
                });
                $(".list li").live("mouseover", function () {
                    $(".listshow li[class='selected']").removeClass("selected");
                    $(this).addClass("selected");
                    // if autoFill option is true then fill selected value in textbox
                    if (autoFill)
                    {
                        str = $(".listshow li[class='selected']").text();
                        obj.val(str);
                    }
                });
                $(".list li").live("click", function () {
                    str = $(".listshow li[class='selected']").text();
                    obj.val(str);
                    // store id of the selected option
                    if (idHolder != "" && idHolder != null)
                        $("#" + idHolder).val($("li[class='selected'] a").attr("id"));
                    $(".listshow").fadeOut("fast");
                });
                $(document).click(function () {
                    if ($(".listshow").css("display") == "block")
                        $(".listshow").fadeOut("fast");
                });
                $(document).keyup(function (event) {
                    if (event.keyCode == 9)
                    {
                        //str = $(".listshow li[class='selected']").text();
                        //obj.val(str);
                        // store id of the selected option
                        if (idHolder != "" && idHolder != null)
                            $("#" + idHolder).val($(".list li[class='selected'] a").attr("id"));
                        $(".listshow").fadeOut("fast");
                    }
                });
            });
        }
    });
})(jQuery);

