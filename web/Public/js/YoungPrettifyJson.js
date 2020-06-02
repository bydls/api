/**
 * 美化JSON
 * Author: 高蒙 <936594075@qq.com>
 * Date: 2019/10/16 09:11
 * Copyright (c) 2019 http://www.shuchengxian.com All rights reserved.
 */
;$(function ($, window, document, undefined) {

    YoungPrettifyJson = function (container, jsonbody) {

        "use strict";
        /*stirct mode not support by IE9-*/

        if (!container) return;
        if (!jsonbody) return;

        var options = options || {},
            Html,
            eachHtml;

        function init() {
            /*处理json格式*/
            document.getElementById(container).innerHTML = renderHtml(JSON.parse(jsonbody), 1);
            rendCss();
        }

        /*输出最终的HTML*/
        function renderHtml(json, level) {
            options.eachHtml = '';
            funEachJson(json, level);
            options.Html = '<div class="y-prettiy-json"><div>{</div>' + options.eachHtml + '<div>}</div>';
            return options.Html;
        }

        /*处理json数据*/
        function funEachJson(json, level) {
            for (let i in json) {
                if (json[i] != undefined && i != undefined) {
                    if (typeof json[i] == 'string') {
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">"' + i + '":<span class="y-prettiy-string y-prettiy-value">"' + json[i] + '"</span>  <label class="y-prettiy-label">&lt;string&gt;</label> </div>';
                    } else if (typeof json[i] == 'number') {
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">"' + i + '":<span class="y-prettiy-number y-prettiy-value">' + json[i] + '</span>  <label class="y-prettiy-label">&lt;' + ((json[i] % 1 != 0) ? 'float' : 'int') + '&gt;</label> </div>';
                    } else if (typeof json[i] == 'boolean') {
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">"' + i + '":<span class="y-prettiy-boolean y-prettiy-value">' + json[i] + '</span>  <label class="y-prettiy-label">&lt;boolean&gt;</label> </div>';
                    } else if (Array.isArray(json[i])) {
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">"' + i + '":[</div>';
                        funEachJson(json[i], level + 1);
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">],</div>';
                    } else {
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">' + (isNaN(Number(i)) ? '"' + i + '":' : '') + '{</div>';
                        funEachJson(json[i], level + 1);
                        options.eachHtml += '<div class="y-prettiy-margin-l" data-level="' + level + '">},</div>';
                    }
                }
            }
        }

        /*更改样式*/
        function rendCss() {
            document.getElementsByClassName('y-prettiy-json')[0].style.fontFamily = "Menlo, Monaco, Consolas, monospace";
            document.getElementsByClassName('y-prettiy-json')[0].style.fontSize = "12px";
            document.getElementsByClassName('y-prettiy-json')[0].style.lineHeight = "18px";
            let y_doc = document.getElementsByClassName('y-prettiy-value');
            for (let i = 0; i < y_doc.length; i++) {
                y_doc[i].style.color = "#428bca";
            }
            y_doc = document.getElementsByClassName('y-prettiy-number');
            for (let i = 0; i < y_doc.length; i++) {
                y_doc[i].style.color = "#5cb85c";
            }
            y_doc = document.getElementsByClassName('y-prettiy-label');
            for (let i = 0; i < y_doc.length; i++) {
                y_doc[i].style.color = "#8b95a5";
                y_doc[i].style.backgroundColor = "#f5f6f7";
                y_doc[i].style.marginLeft = "5px";
            }
            y_doc = document.getElementsByClassName('y-prettiy-margin-l');
            for (let i = 0; i < y_doc.length; i++) {
                let level = y_doc[i].getAttribute('data-level');
                y_doc[i].style.marginLeft = (level == 1 ? level * 12 : level * 24) + 'px';
            }
        }

        init();
    };
}(jQuery, window, document));
