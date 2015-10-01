  var func_calendar = function(p) {
            var d = document;
            var h = d.getElementsByTagName("head")[0];
            var link = d.createElement("link");
            link.href="style/calwidget.css";
            link.setAttribute("rel","stylesheet");
            link.type="text/css";
            var link2 = d.createElement("link");
            link2.href="style/calstyle.css";
            link2.setAttribute("rel","stylesheet");
            link2.type="text/css";
            h.appendChild(link2);
            h.appendChild(link);
            var url = p.getElementsByTagName("a");
            var url2 = "";
            if(url.length > 1) {
                url2 = url[url.length - 1];
                tmp = "<a class=\"pin\"";
                for(i = 0; i < url2.attributes.length; ++i) {
                    tmp += " " + url2.attributes[i].name + "=\"" + url2.attributes[i].value + "\"";
                }
                tmp += ">" + url2.innerHTML + "</a>";
                url2 = tmp;

                url = url[url.length - 2];
                tmp = "<a";
                for(i = 0; i < url.attributes.length; ++i) {
                    tmp += " " + url.attributes[i].name + "=\"" + url.attributes[i].value + "\"";
                }
                tmp += ">" + url.innerHTML + "</a>";
                url = tmp;
            } else {
                if(url.length == 1) {
                    url = url[url.length - 1];
                    tmp = "<a";
                    for(i = 0; i < url.attributes.length; ++i) {
                        tmp += " " + url.attributes[i].name + "=\"" + url.attributes[i].value + "\"";
                    }
                    tmp += ">" + url.innerHTML + "</a>";
                    url = tmp;
                } else {
                    url = "";
                }
            }
            p.id = "edodatki_widget";
            p.innerHTML = "<div class=\"widget_calendar\"><!--<span>Calendar</span>--><div><div><div id=\"calendar_header\"><a href=\"#\" class=\"prev\" title=\"Prev month\"><img src =\"images/prev-cal.png\" /><!--Prev month--></a><a href=\"#\" class=\"next\" title=\"Mext month\"><img src =\"images/next-cal.png\" /><!--Next month--></a><span></span></div><div id=\"calendar_wraper\"></div><div style=\"text-align: center\">" + url + "</div></div>" + url2 + "</div>";

                var script = d.createElement("script");
                script.src = "js/calendar_widget.js";
                script.type = "text/javascript";
                script.charset = "UTF-8";
                h.appendChild(script);

            };
                var bdps0083huog8scw0cc0osgkgo = document.getElementsByTagName("script");
                bdps0083huog8scw0cc0osgkgo = bdps0083huog8scw0cc0osgkgo[bdps0083huog8scw0cc0osgkgo.length - 1].parentNode;
            var oldonload_bdps0083huog8scw0cc0osgkgo = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = function() {
                    func_calendar(bdps0083huog8scw0cc0osgkgo);
                };
            } else {
                window.onload = function() {
                    if(oldonload_bdps0083huog8scw0cc0osgkgo) {
                        oldonload_bdps0083huog8scw0cc0osgkgo();
                    }
                    func_calendar(bdps0083huog8scw0cc0osgkgo);
                }
            }