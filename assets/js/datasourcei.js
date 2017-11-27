mpxd.constructors.page_info_system = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.system_design_1 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.system_design_2 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.system_design_3 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}
mpxd.constructors.testing_commisioning_1 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.testing_commisioning_2 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.testing_commisioning_3 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.testing_commisioning_4 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.procurement_manufacturing_1 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.procurement_manufacturing_2 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.procurement_manufacturing_3 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.procurement_manufacturing_4 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.procurement_manufacturing_5 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.installation_1 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.installation_2 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.installation_3 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.constructors.delivery_1 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}
mpxd.constructors.delivery_2 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}
mpxd.constructors.delivery_3 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}
mpxd.constructors.delivery_4 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}
mpxd.constructors.delivery_5 = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}
mpxd.constructors.le = function (data) {
    mpxd.modules.general.GenerateGeneralview(data);
}

mpxd.modules.double_pier_view = {};
mpxd.modules.double_pier_view.View = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    },
    render: function () {
        var that = this;
        var html = mpxd.getTemplate("double_pier_view");

        template = _.template(html, {data: that.data});
        that.$el.html(template);
    }
});

mpxd.constructors.double_pier_view = function (items) {
    var el = "#portlet_" + items.id
    return new mpxd.modules.double_pier_view.View({data: items, el: el});
}


mpxd.modules.single_pier_view = {};
mpxd.modules.single_pier_view.View = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    },
    render: function () {
        var that = this;
        var html = mpxd.getTemplate("single_pier_view");

        template = _.template(html, {data: that.data});
        that.$el.html(template);
    }
});

mpxd.constructors.single_pier_view = function (items) {
    var el = "#portlet_" + items.id
    return new mpxd.modules.single_pier_view.View({data: items, el: el});
}


mpxd.modules.viaduct_pier_view = {};
mpxd.modules.scurve = {};
mpxd.modules.scurve.ScurveView1 = Backbone.View.extend({
    initialize: function (options) {
        //console.log(options);
        this.data = options.data;
        this.render();

    },
    render: function () {
        var that = this;
        var html = mpxd.getTemplate("scurve-1");

        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.psd_data_type2').css({"height":(that.$el.find('.portlet_content').height())-154});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});

        //that.$el.find('#chart_'+that.data.id).highcharts({
        var chart = new Highcharts.Chart({
            title: {
                text: '',
                x: -20 //center
            },
            xAxis: {
                categories: that.data.categories,
                tickInterval: 3,
                labels: {
                    rotation: 270,
                    //step: 3,
                    style: {
                        color: '#ffd461',
                        font: '11px Trebuchet MS, Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                max: 100,
                tickInterval: 10,
                labels: {
                    style: {
                        color: '#ffd461',
                        font: '11px Trebuchet MS, Verdana, sans-serif'
                    }
                },
                title: {
                    text: '%',
                    style: {
                        color: '#ffd461',
                        font: '11px Trebuchet MS, Verdana, sans-serif'
                    },
                    margin: 0
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#333'
                }],
                gridLineColor: '#333'
            },
            tooltip: {
                enabled: true,
                //formatter: function() { return this.series.name; },
                //valueSuffix: '%'
                formatter: function (evt) {
                    var current = this.series.data;
                    //console.log(current[current.length - 1].category);
                    var tooltip;
                    if (current[current.length - 1].series.name === 'Actual' && current[current.length - 1].y === this.y) {
                        tooltip = '<span style="color:#EBFF00">Current ' + this.series.name + ' (' + current[current.length - 1].category + ')</span>: <b>' + current[current.length - 1].y + '%</b><br/>';
                        return tooltip;
                    }
                    else {
                        return false
                    }
                }
            },
            legend: {
                enabled: false,
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Early',
                data: that.data.earlyData,
                color: '#04B152',
                enableMouseTracking: false
            }, {
                name: 'Late',
                data: that.data.delayedData,
                color: '#FF0000',
                enableMouseTracking: false
            }, {
                name: 'Actual',
                data: that.data.actualData,
                color: '#0070C0'
                //enableMouseTracking: false,
                /*events : {
                 mouseOver: function() {
                 console.log(this.yData[this.yData.length - 1]);
                 }
                 },*/
            }],
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            },
            credits: {
                enabled: false
            },
            chart: {
                type: 'spline',
                backgroundColor: '#222',
                renderTo: 'chart_' + that.data.id
            }


        });

        /*chart.tooltip.refresh(chart.series[2].points[that.data.actualData.length - 1]); // onload render tooltip
         (function(chart) {
         chart.wrap(chart.Tooltip.prototype, 'hide', function(defaultCallback) {
         });
         }(Highcharts));*/
    }
});

mpxd.modules.scurve.ScurveView2 = Backbone.View.extend({
    initialize: function (options) {
        //console.log(options);
        this.data = options.data;
        if (typeof options.componentSelector != 'undefined') {
            this.render(options.componentSelector);
        } else {
            this.render();
        }
    },
    render: function (componentSelector) {
        // ComponentSelector is css selector for embedding S-Curve as a component, rather than a portlet
        var that = this;
        var html = mpxd.getTemplate("scurve-2");

        template = _.template(html, {data: that.data});
       /* var padu_scurve_time = ['','Week 39','Week 40','Week 41','Week 42','Week 43','Week 44','Week 45','Week 46','Week 47','Week 48','Week 49','Week 50','Week 51','Week 52'];
        var padu_scurve_early = [0,13.69,34.08,42.71,54.17,57.89,61.83,70.76,80.13,83.85,88.76,92.63,96.43,99.26,100.00];
        var padu_scurve_late = [0,11.69,32.08,40.71,52.17,55.89,59.83,68.76,78.13,81.85,86.76,90.63,94.43,97.26,98.00];
        var padu_scurve_actual = [0,13.69,34.08,40.48,50.74,54.17,56.90,57.64];
*/

        if (typeof componentSelector != 'undefined') {
            var contents = $(template).find('.container');

            that.$el.find(componentSelector).html(contents);
        } else {
            that.$el.html(template);
            //that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-50});
            that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
        }


        Highcharts.chart('portlet_padu_scurve', {
            title: {
                text: ''
            },

            subtitle: {
                text: ''
            },
            tooltip: {
                backgroundColor: '#445',
                shared: true,
                crosshairs: true
            },
           /* xAxis: {
                categories: padu_scurve_time
            },*/
            xAxis: {
                categories: that.data.interval
            },
            yAxis: {
                min: 0,
                max:100,
                title: {
                    text: 'Progress (%)'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            series: [{
                name: 'Planned',
                color:'#0f0',
                data: that.data.earlyData
            }, {
                name: 'Late',
                color:'#f00',
                data: that.data.delayedData
            }, {
                name: 'Actual',
                color:'#0cf',
                data: that.data.actualData
            }],
          /*  series: [{
                name: 'Early',
                color:'#0f0',
                data: padu_scurve_early
            }, {
                name: 'Late',
                color:'#f00',
                data: padu_scurve_late
            }, {
                name: 'Actual',
                color:'#0cf',
                data: padu_scurve_actual
            }],*/

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false
            }
        });
    }
});


mpxd.modules.scurve.initializeScurve = function (callback) {
    /* Initialize template */

    if (typeof mpxd.modules.scurve.initializedFlag == "undefined") {
        mpxd.loadTemplateAsync(["scurve-1", "scurve-2"], callback);
        mpxd.modules.scurve.initializedFlag = true;
    } else {
        if (typeof callback == "function")
            callback();
    }
}


mpxd.constructors.scurve = function (data) {
    if (typeof data.data.id == "undefined")
        data.data.id = data.id;
    if (typeof data.data.title == "undefined")
        data.data.title = data.title;
    var s = mpxd.modules.scurve;
    s.initializeScurve(function () {
       /* console.log(data);*/
        s.GenerateScurve(data.data);
    });

}


mpxd.modules.scurve.GenerateScurve = function (items, componentSelector) {
    //mpxd.modules.scurve.initializeScurve();
    try{
        var data = items;
        var el = "#portlet_" + data.id;
        var type = data.chartType;
        var view = data.viewType;
        //var trend = data.trend.toLowerCase();

        /*data.categories = [];*/
        data.currentEarly = data.currentEarly.split('%')[0];
        data.currentActual = data.currentActual.split('%')[0];
        data.currentLate = data.currentLate.split('%')[0];
        //console.log(data);

        if (type == "long") {
            data.categories = ["Jan/16", "Feb/16", "Mar/16", "Apr/16", "May/16", "Jun/16", "Jul/16", "Aug/16", "Sep/16", "Oct/16", "Nov/16", "Dec/16", "Jan/17", "Feb/17", "Mar/17", "Apr/17", "May/17", "Jun/17", "Jul/17",
                "Aug/17", "Sep/17", "Oct/17", "Nov/17", "Dec/17","Jan/18", "Feb/18", "Mar/18", "Apr/18", "May/18", "Jun/18", "Jul/18", "Aug/18", "Sep/18", "Oct/18", "Nov/18", "Dec/18", "Jan/19", "Feb/19", "Mar/19", "Apr/19", "May/19", "Jun/19", "Jul/19", "Aug/19", "Sep/19", "Oct/19", "Nov/19", "Dec/19", "Jan/20", "Feb/20", "Mar/20", "Apr/20", "May/20", "Jun/20", "Jul/20", "Aug/20", "Sep/20", "Oct/20", "Nov/20", "Dec/20", "Jan/21", "Feb/21", "Mar/21", "Apr/21", "May/21", "Jun/21", "Jul/21", "Aug/21", "Sep/21", "Oct/21", "Nov/21", "Dec/21",
                "Jan/22", "Feb/22", "Mar/22", "Apr/22", "May/22", "Jun/22", "Jul/22", "Aug/22", "Sep/22", "Oct/22", "Nov/22", "Dec/22"];
        } else if (type == "short") {
            data.categories = ["Jan/16", "Apr/16", "Jul/16", "Oct/16", "Jan/17", "Apr/17", "Jul/17", "Oct/17", "Jan/18", "Apr/18", "Jul/18", "Oct/18", "Jan/19", "Apr/19", "Jul/19", "Oct/19", "Jan/20", "Apr/20", "Jul/20", "Oct/20", "Jan/21", "Apr/21", "Jul/21", "Oct/21", "Jan/22", "Apr/22", "Jul/22", "Oct/22"];
            //data.categories = ["Jan-12", "Apr-12", "Jul-12", "Oct-12", "Jan-13", "Apr-13", "Jul-13", "Oct-13", "Jan-14", "Apr-14", "Jul-14", "Oct-14", "Jan-15", "Apr-15", "Jul-15", "Oct-15", "Jan-16", "Apr-16", "Jul-16", "Oct-16", "Jan-17", "Apr-17", "Jul-17"];
        }

        if (typeof data.startAt != "undefined") {
            var dayms = 86400000;
            var beginningD = new Date("1/" + data.categories[0]);
            var startD = new Date("1/" + data.startAt);
            var months = monthDiff(beginningD, startD);
            var quarters = months / 4;
            if (type == "long") {
                data.earlyData.reverse();
                data.actualData.reverse();
                data.delayedData.reverse();
                for (var i = 0; i < months; i++) {
                    data.earlyData.push(null)
                    data.actualData.push(null)
                    data.delayedData.push(null)
                }
                data.earlyData.reverse();
                data.actualData.reverse();
                data.delayedData.reverse();
            } else if (type == "short") {
                data.earlyData.reverse();
                data.actualData.reverse();
                data.delayedData.reverse();
                for (var i = 0; i < quarters; i++) {
                    data.earlyData.push(null)
                    data.actualData.push(null)
                    data.delayedData.push(null)
                }
                data.earlyData.reverse();
                data.actualData.reverse();
                data.delayedData.reverse();
            }
        }

        /*if (trend == "up") {
            data.trendColor = "#00B050";
            data.arrowDirection = "up";
        } else if (trend == "down") {
            data.trendColor = "#FF0000"
            data.arrowDirection = "down";
        } else if (trend == "right") {
            data.trendColor = "#2E9AFE"
            data.arrowDirection = "right";
        }*/
    }catch(e){
        console.log("Error in S-Curve:"+e);
    }
        view = (typeof view == 'undefined')?2:view;
        if (view == "1") {
            return new mpxd.modules.scurve.ScurveView1({data: data, el: el});
        } else if (view == "2") {
            return new mpxd.modules.scurve.ScurveView2({data: data, el: el, componentSelector: componentSelector});
        }
}

/****************************

 GIS Map

 ***************************/

mpxd.constructors.tbm = function (items) {
    console.log(items);
}

/* Set up lookups for easier reference */
var pages_lookup_id = {};
var pages_lookup_url = {};
for (var i = 0; i < pages.length; i++) {
    pages_lookup_id[pages[i].id] = pages[i];
    pages_lookup_url[pages[i].url] = pages[i];
}
function generateBreadcrumbs(id) {
    if (typeof pages_lookup_id[id] == "undefined") {
        console.log("Unable to find page from breadcrumbs!");
        return [];
    }
    var $bc = $('#breadcrumbs');
    var $first = $bc.children('li:first');
    var crumbs = [];
    var page = pages_lookup_id[id];
    var parentid = page.parent;
    var url = page.url
    // console.log('imcalled'+url);

    crumbs.push('<a href="javascript:void(0);" onclick="loadPage(\'' + url + '\')">' + page.name + '</a>'); // last value in breadcrumbs
    while (parentid != 0) {
        url = pages_lookup_id[parentid].url;
        if (url != '#') {
                crumbs.push('<a href="javascript:void(0);" onclick="loadPage(\'' + url + '\')">' + pages_lookup_id[parentid].name + '</a>');
        } else
            crumbs.push('<a href="javascript:void(0);" style="cursor:default;color:#B2B2B2">' + pages_lookup_id[parentid].name + '</a>');
            parentid = pages_lookup_id[parentid].parent;
    }
    crumbs = crumbs.reverse();

    var $li = $("<li>" + crumbs.join("</li><li>") + "</li>");

    $bc.empty();

    $bc.append($first).append($li);

    return crumbs;


}


function ellipseTitle(text) {
    /* Not so dynamic, but temporary fix for iPad short width orientation */
    if (($(window).width() <= 768) || true) {
        var $topMenu = $('.menuzord-menu.menuzord-right.menuzord-indented'),
            $pageTitle = $('#page_title'),
            $menuZord = $('#menuzord'),
            defaultMenuWidth = 313,
            menuOuterWidth = (($topMenu.length < 1) ? defaultMenuWidth : $topMenu.outerWidth()), /* If header hasnt load use default */
            zordWidth = $menuZord.width(),
            allowance = zordWidth - menuOuterWidth - parseInt($pageTitle.css('padding-left')) - parseInt($pageTitle.css('padding-right')),
            rlen,
            rtext,
            font = $pageTitle.css('font'),
            bg = $pageTitle.css('background'),
            current = getTextWidth(text, font),
            isExpand = false,
            fexpand = function () {
                $pageTitle.css('background', '#000000');
                $pageTitle.css('border-color', '#000000');
                $pageTitle.text(text);
                isExpand = true;
            },
            fcollapse = function () {
                $pageTitle.css('background', bg);
                $pageTitle.css('border-color', 'transparent');
                $pageTitle.text(rtext);
                isExpand = false;
            },
            ftoggle = function () {
                if (isExpand)
                    fcollapse();
                else
                    fexpand();
            }
        //console.log("current = {0}\n allowence = {1}\n title = {2}\n font = {3}\n zord = {4}\n text = {5}".format(current,allowance,text,font,menuOuterWidth,text));
        if (current > allowance) {
            /* Current text longer than allowed, we try to build the ellipse text */

            for (var i = 0; i < text.length; i++) {
                rtext = text.substring(0, i) + "...";
                rlen = getTextWidth(rtext, font);
                if (rlen > allowance) {
                    rtext = text.substring(0, i - 1) + "...";
                    rlen = getTextWidth(rtext, font);
                    break;
                }
                rtext = text;
                rlen = text.length;
            }
            $pageTitle.text(rtext);
            $pageTitle.on('mouseenter', fexpand).on('mouseleave', fcollapse).on('click', ftoggle);

        } else {
            /* No worries, current is less */
            $pageTitle.text(text);

            /* Reset events */
            $pageTitle.off('mouseenter').off('mouseleave').off('click');
            //console.log('events reset');
        }

    }
}


var currentSlug = "";
var currentPageID = 0;
//var currentPage = "";


/*From: http://stackoverflow.com/questions/13721651/javascript-get-absolute-url-from-relative-escaped-url */
function relativeToAbsolute(url) {
    arr = url.split("/") // Cut the url up into a array
    while (!!~arr.indexOf("..")) { // If there still is a ".." in the array
        arr.splice(arr.indexOf("..") - 1, 2); // Remove the ".." and the element before it.
    }
    return arr.join("/"); // Rebuild the url and return it.
}
enableDays = [];
function loadPage(p, dontsavestate) {
    $("div#loading_pad").removeClass("loading_pad_gohide");
    $("div#cover").removeClass("overlay_gohide").addClass("overlay");
    reallink = p;
    p = p.substr(0, (p.indexOf('?') == -1) ? p.length : p.indexOf('?'));
    //var date = getParameterByName('date');
    $('.megamenu').fadeOut(300);
    if ((typeof p == "undefined") || (p == "") || (p == "#"))
        return;
    if ((typeof dontsavestate != "undefined") && (dontsavestate)) {
    }
    else {

        /* Cant use '../' in pushState, because it will cause a redundant pushstate to parse to the real url! Need to parse it using script to avoid this */
        var parsedlink = relativeToAbsolute(location.href + "/../../" + reallink);
        History.pushState({
            _index: History.getCurrentIndex(),
            p: reallink,
            state: "Pushstate"
        }, document.title, parsedlink);
    }
    //console.log("Pushstate!!");


    //if (

    var currentRoute = p;
    //Added by Sebin for invalid page handling.
    if(typeof pages_lookup_url[p] == "undefined"){
        loadPage("404/index");
    }else {
        currentPageID = pages_lookup_url[p].id;
    }
    var title = pages_lookup_url[p].name;// + (((typeof data_dates[p] == "undefined") || (data_dates[p] == "")) ? "" : " ("+data_dates[p]+")");
    //$('#data_date').text(((typeof data_dates[p] == "undefined") || (data_dates[p] == "")) ? "" : data_dates[p] );
    generateBreadcrumbs(currentPageID);
    $(".breadcrumbs_title").text(title);
    // console.log(title);
    //$('#page_title').text(pages_lookup_url[p].name); // set the page title

    var currentRouteArr = currentRoute.split('/');
    currentSlug = currentRouteArr[0];
    //currentPage = currentRouteArr[1];


    //Get the date list of current slug
    mpxd.getDateList("api/get?date_list=" + currentSlug, function (result) {
        var datelist = $("#date_list").empty();
        var curr_data_date = "";


        for (var i = 0; i < result.length; i++) {
            var date = result[i].date;
            enableDays.push(date);
            if (i == 0)
                datelist.append("<li><a href='javascript:void(0)' onClick=loadPage('" + p + "')>" + date + " (Latest)</a></li>");
            else
                datelist.append("<li><a href='javascript:void(0)' onClick=loadPage('" + p + "?date=" + date + "')>" + date + "</a></li>");

            //Update the current date field
            if (getParameterByName("date") === date) {
                $("#data_date").val(moment(getParameterByName("date"), "DD-MMM-YY").format("DD MMM YYYY").toUpperCase());
                curr_data_date = date;
            }
        }
        if (getParameterByName("date").length == 0) {
            console.log(result[0].date);
            $("#data_date").val(moment(result[0].date, "DD-MMM-YY").format("DD MMM YYYY").toUpperCase());
            curr_data_date = result[0].date;
        }
        //ellipseTitle(title +" ("+ moment(curr_data_date, "DD-MMM-YY").format("DD MMMM YYYY") +")");
        var titletext = title + " (" + moment(curr_data_date, "DD-MMM-YY").format("DD MMMM YYYY") + ")";
        if (!isUseCustomPortlet) {
            ellipseTitle(titletext);
        }
        else {
            $('#page_title').text(titletext);
        }
        setPageTitle(title);


    });

    mpxd.getportletFromURL(p, function (data) {
        //$('#portlet_container').empty();

        //Draw the portlets
        drawPortlets(data);
        var j = p.split("/");
        mpxd.getData(data,function (result) {
            mpxd.resetDatasource();
            for (var i in result.data) {
                //var json = jQuery.parseJSON(result.data[i].value);
                //var name = data[i].name;
                mpxd.storeDatasourceToArray(result.data[i], (typeof result.static_data[i] == "undefined") ? "[]" : result.static_data[i]);
                //temp.push(json);
                //console.log(result.data[i].value);
            }
            // console.log(result);
            var array = mpxd.generatePortletContent(result.item);
            var temp = [];
            $("div#loading_pad").addClass("loading_pad_gohide");
            $("div#cover").addClass("overlay_gohide").removeClass("overlay");
            //mpxd.datasource = temp;
        });
    });
}

function enableAllTheseDays(date) {
    var sdate = $.datepicker.formatDate('dd-M-y', date)
    // console.log(enableDays);
    if ($.inArray(sdate, enableDays) != -1) {
        return [true];
    }
    return [false];
}

function getRoute() {
    var l = location.href;
    var find = "/ppms/";
    var start = l.indexOf(find);
//alert (start);
    var currentRoute = l.substr(start + find.length);
    var currentRoute = currentRoute.substr(0, (currentRoute.indexOf('#') == -1) ? currentRoute.length : currentRoute.indexOf('#'));
    return currentRoute;
}

$(function () {
    console.log("Fire!")
    setTimeout(function () {
        console.log("Timeout");
        $(window).trigger("resize");
    }, 2000);
    var State = History.getState()

    //History.log('initial:', State.data, State.title, State.url);

    // Bind to State Change
    History.Adapter.bind(window, 'statechange', function () { // Note: We are using statechange instead of popstate
        // Log the State
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
        //History.log('statechange:', State.data, State.title, State.url);
        /* Using the fix from https://github.com/browserstate/history.js/issues/47#issuecomment-25750285 for popstate on pushstate state call*/
        var currentIndex = History.getCurrentIndex();
        var internal = (History.getState().data._index == (currentIndex - 1));
        if (!internal) {
            if ((typeof State.data.state != "undefined") && (State.data.state == "Pushstate")) {
                if (typeof State.data.p != "undefined") {
                    loadPage(State.data.p, true);
                }
            }
            ;
            // your action
        }

        //console.log(State);
    });
    // $("#data_date").hide();
    $('#data_date').datepicker({
       dateFormat: "dd M yy",
       beforeShowDay: enableAllTheseDays,
       nextText: "",
       prevText: "",
       altField: '#data_date_selected',
       altFormat: "dd-M-y",
       onSelect: function (dateText, inst) {
           p = reallink.substr(0, (reallink.indexOf('?') == -1) ? reallink.length : reallink.indexOf('?'));
           var selected = $('#data_date_selected').val();
           $("#date-display").text(selected);
           loadPage(p + '?date=' + selected)

       }
    });

    $('#date_selector').on('click', function () {
        $('#data_date').datepicker('show');
    })
});


/* Utility functions */


function getTextWidth(text, font) {
    // re-use canvas object for better performance
    var canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
    var context = canvas.getContext("2d");
    context.font = font;
    var metrics = context.measureText(text);
    return metrics.width;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function monthDiff(d1, d2) {
    var months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth() + 1;
    months += d2.getMonth();
    return months <= 0 ? 0 : months;
}