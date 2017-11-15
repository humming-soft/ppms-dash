/* MPXD Core javascript */
mpxd = {};

isPDF = true;
isUseCustomPortlet = (getUrlParameter("print") == "1");
//var imageBase = window.location.origin;
var imageBase = 'http://192.168.1.63:90';
//mpxd.siteurl = 'https://mpxd.mymrt.com.my/';
mpxd.siteurl = imageBase+'/';
$(function(){
	/* Optimize page for print layout */

	if (isUseCustomPortlet) {
		$.fn.mCustomScrollbar = function(){};
		$('.removeonprint').hide();
		$('.showonprint').show();
		$('#page_title').appendTo('#alternatetitle');
		$(window).trigger('resize');
	}
	
	/* Buttons for excel & pdf exports */
	
	$('#toExcelButton').on('click', function(){toExcel()});
	$('#toPDFButton').on('click', function(){toPDF(currentPageID);});

});
mpxd.templateURLs = {
    "none": {"templateURL": "assets/templates/none.html"},
    "scurve-1": {"templateURL": "assets/templates/scurve-1.html"},
    "scurve-2": {"templateURL": "assets/templates/padu_scurve.html"},
    "page_info": {"templateURL": "assets/templates/page_info.html"},
	"hsse": {"templateURL": "assets/templates/hsse.html"},
    "progress": {"templateURL": "assets/templates/padu_progress.html"},
    "kpi": {"templateURL": "assets/templates/kpi.html"},
    "kad": {"templateURL": "assets/templates/kad.html"},
	"kd": {"templateURL": "assets/templates/kd.html"},
    "slider": {"templateURL": "assets/templates/slider.html"},
    "page_station_info": {"templateURL": "assets/templates/page_info_station.html"},
    "gallery": {"templateURL": "assets/templates/gallery.html"},
    "schematic": {"templateURL": "assets/templates/slider.html"},
    "viaduct_pier_view": {"templateURL": "assets/templates/viaduct_pier_view.html"},
	"issue" :	{"templateURL": "assets/templates/issue.html"},
	"mitigation" :	{"templateURL": "assets/templates/mitigation.html"},
	"page_mspr_info": {"templateURL": "assets/templates/page_info_mspr.html"},
	"page_system_info": {"templateURL": "assets/templates/page_info_system.html"},
	"tunnel_progress": {"templateURL": "assets/templates/tunnel_progress.html"},
	"ug_summary": {"templateURL": "assets/templates/ug_summary.html"},
	"piechart_workpackage" : {"templateURL": "assets/templates/procurement_work_package.html"},
	"barchart_workpackage" : {"templateURL": "assets/templates/procurement_contract_awarded.html"},
	"ug_work_progress" : {"templateURL": "assets/templates/ug_work_progress.html"},
	"summary_list" : {"templateURL": "assets/templates/summary_list.html"},
	"report" : {"templateURL": "assets/templates/generate_report.html"},
	"padu_issuemitigation" : {"templateURL": "assets/templates/padu_issuemitigation.html"},
	"padu_latetask" : {"templateURL": "assets/templates/padu_latetask.html"},
	"padu_upcomingtask" : {"templateURL": "assets/templates/padu_upcomingtask.html"},
	"padu_scurve" : {"templateURL": "assets/templates/padu_scurve.html"},
	"padu_projectcost" : {"templateURL": "assets/templates/padu_projectcost.html"},
	"padu_progress" : {"templateURL": "assets/templates/padu_progress.html"},
	"padu_wbs" : {"templateURL": "assets/templates/padu_wbs.html"}
}

mpxd.templateData = {};

mpxd.modules = {};

mpxd.constructors = {};

mpxd.datasource = [];

mpxd.datasourceAss = {};

mpxd.getTemplate = function(t) {
    //console.log('names to identify');
    //console.log(t);
    if (typeof mpxd.templateData[t] == "undefined") {
        /* template not loaded yet or unexistant */
        if (typeof mpxd.templateURLs[t] == "undefined") {
            return; /* Unexistant */
        } else {
            var res = "";
            /* Synchronously load because maybe portlet failed to initialize previously */
            jQuery.ajax({
                url: baseURL + mpxd.templateURLs[t]["templateURL"],
                success: function(result) {
                    //console.log("SYNC");
                    if (result.isOk == false)
                        alert(result.message);
                    res = result;
                },
                async: false
            });
            mpxd.templateData[t] = res;
        }
    }
    return mpxd.templateData[t];
}

mpxd.loadTemplateAsync = function(t, callback) {
	if (typeof t == "string") {
	/* For single template */
    jQuery.ajax({
        url: baseURL + mpxd.templateURLs[t]["templateURL"],
        success: function(result) {
            if (result.isOk == false)
                console.log(result.message);
            else {
                mpxd.templateData[t] = result;
                //mpxd.templateData[t].html = result;
				if (typeof callback == "function") callback(mpxd.templateData[t]);
            }
        },
        async: true
    });
	} else if ($.isArray(t)) {
	/* For multiples, but be careful of errors. This does not handle them */
	var c = 0;
	var total = t.length;
	var imdone = function() {
		if (++c == total) {
			if (typeof callback == "function") callback();
		}
	}
	$.each(t, function(idx, t1){
		jQuery.ajax({
        url: baseURL + mpxd.templateURLs[t1]["templateURL"],
        success: function(result) {
			
            if (result.isOk == false)
                console.log(result.message);
            else {
                mpxd.templateData[t1] = result;
				imdone();
            }
        },
        async: true
    });
	})
	}
}

mpxd.getJSON = function(url, query, callback) {
    $.getJSON(baseURL + url + query, function(data) {
        callback(data);
        console.log(data);
        console.log('getjson.generate');
    }).error(function(){
		console.log("mpxd.getJSON erred");
	});
	
}

mpxd.getportletFromURL = function(u, callback) {
	var token = u.split("/");
	return mpxd.getportlet(token[0], token[1], callback);
}


mpxd.storePortletToArray = function(p) {
	if ((typeof portletArray == "undefined") || (typeof portletAssArray == "undefined") || (typeof portletLookupArray == "undefined")) { portletArray = []; portletAssArray = {}; portletLookupArray = {} }
	//console.log(p);
	portletArray.push(p);
	portletAssArray[p.id] = p;
	//portletLookupArray[p.slug+p.type+p.key] = p;
}

mpxd.storeDatasourceToArray = function(p, s) {
	try {
        var json = $.parseJSON(p.value);
        var jsonstatic = $.parseJSON(s.value);
        var result = {};
        $.extend(true, result,jsonstatic,json);
	} catch(e) { console.log("Parse JSON error at mpxd.storeDatasourceToArray");console.log(e); }
	var name = p.name;
	var arr = {"name":name, "data":result};
	mpxd.datasource.push(arr);
	mpxd.datasourceAss[name] = arr;
	//mpxd.datasourceAss
}

mpxd.resetDatasource = function() {
	mpxd.datasource = [];
}

mpxd.getportlet = function(slug, page, callback) {
//console.log(arguments.callee.caller.arguments.callee.caller + "HERERERERERERERERE\n\n\n\n\n\n\n");
	if ((slug == "") || (page == "")) {console.log("Unable to get portlet! URL problem?"); return;}
    mpxd.getJSON('portlet/', slug+"/"+page, function(data) {
        portletArray = [];
		portletAssArray = {};
        for (var i = 0; i < data.length; i++) {
            var u = jQuery.parseJSON(data[i].value);
            u.id = data[i].id;
			u.item_id = data[i].item_id;
			u.meta_group_id = data[i].meta_group_id;
			u.portlet_id = data[i].portlet_id;
			u.order = data.length+1;
			if (typeof u.screenTablet != "undefined") u.screenTablet = $.parseJSON(u.screenTablet);
			if (typeof u.screen1024 != "undefined") u.screen1024 = $.parseJSON(u.screen1024);
			if ((typeof data[i].order != "undefined") && (data[i].order != null)) u.order = parseInt(data[i].order);
			
			
			/* Define some originals for parameters */
			u.orig_row = u.row;
			u.orig_col = u.col;
			u.orig_size_x = u.size_x;
			u.orig_size_y = u.size_y;
			
			mpxd.storePortletToArray(u);
        }
        callback(portletArray)
    });
}
//Commented by Sebin : Alternate function added immediate below.
mpxd.getData = function(data, callback) {
	if ((typeof data == "undefined") || (data == [])) { console.log("No data at getData!"); return;}
    var query = "get?";
    var temp = [];
    for (var i = 0; i < data.length; i++) {
        temp.push(data[i].id);// + "=" + data[i].slug + ":" + data[i].type + ":" + data[i].key);
    }

    query += temp.join("&");

	var itemID = data[0].item_id;
	query += "&item_id=" + itemID;

	var date = getParameterByName('date');
	if(date.length>0)
		query += "&date=" + date;
	//console.log(query);
	mpxd.getJSON('api/', query, function(result) {
		callback(result);
    });
}
//Modified getData function : Modified by Sebin
/*mpxd.getData = function(data, j, callback) {
	if ((typeof data == "undefined") || (data == [])) { console.log("No data at getData!"); return;}
	var query = "get?";
	var temp = [];
	for (var i = 0; i < data.length; i++) {
		temp.push(data[i].id);// + "=" + data[i].slug + ":" + data[i].type + ":" + data[i].key);
	}

	query += temp.join("&");
	query += "&p=" + j;
	var itemID = data[0].item_id;
	query += "&item_id=" + itemID;

	var date = getParameterByName('date');
	if(date.length>0)
		query += "&date=" + date;
	//console.log(query);
	mpxd.getJSON('api/', query, function(result) {
		callback(result);
	});
}*/

mpxd.getDateList = function(data, callback){
	$.getJSON(baseURL + data, function(result) {
        callback(result);
    })
}
//Created By Sebin
// mpxd.getJSONData = function(data, callback){
//     $.getJSON(baseURL + data, function(result) {
//         callback(result);
//     })
// }

mpxd.generatePortletContent = function (data) {
	/*var lookup = {};
	$.each(portletArray, function(idx, d) { 
		lookup[d.id] = portletArray[idx];
	});*/
	for (var i in data){
		//console.log(data[i]);

		var path = $.parseJSON(data[i].meta_key);
		//var temp = mpxd.datasource;
		//var datasource_name = path[0];
		//var pointer = mpxd.datasourceAss[datasource_name]["data"];
		//console.log(pointer);
		//var pointer = mpxd.datasourceAss
		
		var result = undefined;
		$.each(mpxd.datasource, function(idx, k){
			if ((typeof k.data[currentSlug] != "undefined") && (typeof k.data[currentSlug][path[0]] != "undefined")) {
				/* Traverse through datasource using key provided in items_meta */
				if (path.length < 1) { result = k.data[currentSlug]; return; } else {
				var current = k.data[currentSlug][path[0]]
				for (var j = 1; j < path.length; j++) {
					current = current[path[j]];
				}
				result = current;
				return;
				}
			}
		});
		if (typeof result == "undefined") { console.log("Data "+currentSlug+" not found on portlet " + data[i].id + ", giving all data received instead.."); result = mpxd.datasource; }
		//console.log(result);
		/* Inject data to portletArray variable */
		//if( typeof data[i].meta_value == "undefined") { console.log("Undefined meta_value for data portlet " + data[i].id); continue;}
		//var parsed = jQuery.parseJSON(data[i].meta_value);
		//portletAssArray[data[i].id]['json'] = data[i].meta_value;
		//portletAssArray[data[i].id]['data'] = parsed;
		var f = mpxd.constructors[data[i].type_name];
		if (typeof f === "function") {
			var u = {};
			u.data = result;
			u.id = data[i].id;
			u.title = data[i].title;
			u.type = data[i].type_name;
			// console.log(u);
			f($.extend(true, {}, u));
		} else { console.log("Constructor "+data[i].type_name+" not found"); }
	}
	return portletArray;
}






/* misc. functions. Put in seperate file if need arises */
var gridster;
var editing = true;


function selectPort(port) {
	var chosenPort = port;
	
	var avail1024 = (typeof port.screen1024 != "undefined");
	var availTablet = (typeof port.screenTablet != "undefined");
	
	var should1024 = ((screenType == 2) && avail1024);
	var shouldTablet = ((((screenType == 1) || ((screenType == 2)) && (!avail1024))) && availTablet)
	
	if (should1024) chosenPort = port.screen1024;
	if (shouldTablet) chosenPort = port.screenTablet;
	return chosenPort;
}


function drawPortletHelper(id, port) {
	if (!editing) var html = '<li data-id="{0}" ><div id="portlet_{0}" class="block block-drop-shadow"></div></li>'.format(id);
	else var html = '<li data-id="{0}"><header class="editing"><i class="header-button header-button-edit fa fa-edit fa-lg"></i><i class="header-button header-button-move fa fa-arrows fa-lg"></i></header><div id="portlet_{0}" class="block block-drop-shadow"></div></li>'.format(id);
	//console.log(html);
	var chosenPort = selectPort(port);
	var $el = gridster.add_widget(html, chosenPort.size_x, chosenPort.size_y, chosenPort.col, chosenPort.row);
	/* Attach listeners */
	$el.find('.header-button.header-button-edit').on('click', function(){editButtonClick(this, $el);});
}


function drawPortlets(s) {

    //b = new bsgridster(s, 50, '');
    //var v = b.getHtml();

	if ($('.gridster').length < 1) $('#portlet_container').html('<div class="gridster"><ul></ul></div>');
	var margin = 5;
	var width = ($('.gridster').parent().width()/12) - (margin * 2) /* To get each col's width and fit in to gridster */
	
	if (isUseCustomPortlet) { 
		/* Will use our custom function to draw portlets */
		var $gridster = $(".gridster");
		var $ul = $gridster.children("ul");
		$ul.empty();
		/* Sort portlet using order */
		portletArray.sort(function(a,b){return a.order - b.order})
		
		$.each(portletArray, function(idx, a){
			var id = a.id;
			var chosenPort = selectPort(a);
			var sizex = chosenPort.size_x;
			var sizey = chosenPort.size_y;
			var col = chosenPort.col;
			var row = chosenPort.row;
			//console.log(a);
			var finalstring = '<div data-id="{0}" style="width:100%; padding-right:35px"><div id="portlet_{0}" class="block block-drop-shadow"></div></div>'.format(id, col, row, sizex, sizey);
			/*var finalstring = '<div data-id="{0}" data-col="{1}" data-row="{2}" data-sizex="12" data-sizey="{4}" class="asdasd" style="float:left"><div id="portlet_{0}" class="block block-drop-shadow"></div></div>'.format(id, col, row, sizex, sizey); */
			$ul.append($(finalstring));
		});
		if (typeof window.orientationListener == "undefined") { 
			window.orientationListener = window.addEventListener("orientationchange", function() {
				drawPortlets(s);
			}, false);
		}
		//data-id="74" data-col="1" data-row="1" data-sizex="2" data-sizey="9" class="gs-w" style="display: list-item;"
		/*setTimeout(function(){msnry = new Masonry(document.querySelector(".gridster ul"), {
			itemSelector: '.asdasd',
			columnWidth: 30
		});}, 1000);*/
	}
	else {
		/* Will use the original gridster to draw portlets */
		if (typeof gridster == "undefined") {
		/* Gridster init */
		gridster = $(".gridster ul").gridster({
		  widget_base_dimensions: [width, 35],
		  widget_margins: [margin, 5],
		  serialize_params: function($w, wgd) { 
			var id = $w.attr('data-id');
			var portlet = portletAssArray[id];
			if (typeof portlet == "undefined") { return; } /* Undefined is probably because of a new portlet */
			
			/*var key = portlet.key;
			var slug = portlet.slug;
			var type = portlet.type;
			var portlet_id = portlet.portlet_id;
			var meta_group_id = portlet.meta_group_id;*/
			/*portlet['old_col'] = portlet['col'];
			portlet['old_row'] = portlet['row'];
			portlet['old_size_x'] = portlet['size_x'];
			portlet['old_size_y'] = portlet['size_y'];*/
			
			portlet['col'] = wgd.col;
			portlet['row'] = wgd.row;
			portlet['size_x'] = wgd.size_x;
			portlet['size_y'] = wgd.size_y;
			
			
			
			//return { id: id, key: key, slug: slug, type: type, col: wgd.col, row: wgd.row, size_x: wgd.size_x, size_y: wgd.size_y, portlet_id: portlet_id, meta_group_id: meta_group_id } 
			return portlet;
		},
		  resize: { enabled: (editing) },
		  draggable: { handle: ".header-button-move" },
		  min_cols: 12,
		  max_cols: 12
				
		}).data('gridster');
		}
		if (!editing)gridster.disable(); /* Disable dragging */
		
		gridster.remove_all_widgets( function() {
			$.each(portletArray, function(idx, a) {
				/*if (!editing) var html = '<li data-id="{0}" data-key="{1}" data-slug="{2}" data-type="{3}" data-page="{4}" data-json=""><div id="portlet_{0}" class="block block-drop-shadow"></div></li>'.format(a.id,a.key,a.slug,a.type,a.page);
				else var html = '<li data-id="{0}" data-key="{1}" data-slug="{2}" data-type="{3}" data-page="{4}" data-json=""><header class="editing"><i class="header-button header-button-edit">edit</i><i class="header-button header-button-move">|||</i></header><div id="portlet_{0}" class="block block-drop-shadow"></div></li>'.format(a.id,a.key,a.slug,a.type,a.page);
				//console.log(html);
				
				/* Attach listeners 
				var $el = gridster.add_widget(html, this.size_x, this.size_y, this.col, this.row);
				$el.find('.header-button.header-button-edit').on('click', function(){editButtonClick(this, $el);});*/
				drawPortletHelper(a.id, a);
			});
		});
	}
	
	
}



function savePortletData(p, data) {
	var slug = p.slug;
	var type = p.type;
	var key = p.key;
	
	try {
		$.parseJSON(data);
	} catch (e) {
		console.log("Error while parsing JSON : " + e.message);
		return false;
	}
	
	/* Had to use base64 because json was breaking the splitter */
	a = $.post(baseURL + 'setapi/get', {
		"0" : [slug,type,key,Base64.encode(data)].join(":")
		//page : page
	}, function(data){
		console.log(data);
	}).fail(function(){console.log("Failed at saving portlet data!");});
	
}


function editButtonClick(b, $el) {
	return;
	var portlet = portletAssArray[$el.attr('data-id')];
	if (typeof portlet == "undefined") {
		/* New portlet? Lets look it up */
		portlet = portletLookupArray[$el.attr('data-slug') + $el.attr('data-type') + $el.attr('data-key')];
	}
	var json = ((typeof portlet == "undefined") || (typeof portlet.json == "undefined")) ? "" : portlet.json;

	TINY.box.show("<div><textarea id='tinytextarea' cols=100 rows=30>"+json+"</textarea><input type='button' value='Save' id='tinybutton'/></div>",0,0,0,0)
	$('#tinybutton').on('click', function(){
		savePortletData(portlet, $('#tinytextarea').val());
	});
	//console.log(portletArray[$el.attr('data-id')]);
}

function addNewPortlet(s) {
	var arr = s.split(":");
	var slug = arr[0];
	var type = arr[1];
	var key = arr[2];
	var port = {
		slug: slug,
		key: key,
		type: type,
		size_x: 3,
		size_y: 3,
		row: 99,
		col: 1,
		meta_group_id: portletArray[0].meta_group_id /* Could have been better than this */
	}
	
	mpxd.storePortletToArray(port);
	drawPortletHelper("", port);
	savePortletsConfiguration();
	//savePortletData(port, "{}");
}

function newportlet() {
	var s = prompt("Sluggies", (portletArray.length > 0) ? ("{0}:{1}:{2}".format(portletArray[0].slug, portletArray[0].type, portletArray[0].key)) : "");
	//var p = prompt("Page");
	addNewPortlet(s);
}


function savePortletsConfiguration(screen) {
	if ((typeof gridster == "undefined")) { console.log("Unable to save portlet configuration"); return; }
	var portlets = gridster.serialize();
	
	//To check for new portlets by not having ids
	$.each(portletArray, function(idx, p){
		if ((typeof p['id'] == "undefined") || (p['id'] == "")) portlets.push(p);
	});
	
	//If screen is not desktop widescreen, inject screenTablet.
	if ((typeof screen != "undefined") && (screen != 0)) {
		switch(screen) {
			case 1:
				$.each(portlets, function(idx, p) {
				p.screenTablet = {
					"size_x": p.size_x,
					"size_y": p.size_y,
					"col": p.col,
					"row": p.row
				};
				});
				break;
			case 2:
				$.each(portlets, function(idx, p) {
				p.screen1024 = {
					"size_x": p.size_x,
					"size_y": p.size_y,
					"col": p.col,
					"row": p.row
				};
				});
				break;
			default:
				break;
		}
		
		/* If we are saving portlet conf as screenTablet, we have to remain the default port conf unchanged */
		$.each(portlets, function(idx, p) {
			var oldp = portletAssArray[p.id];
			if (oldp) {
				p.size_x = oldp.orig_size_x;
				p.size_y = oldp.orig_size_y;
				p.row = oldp.orig_row;
				p.col = oldp.orig_col;
			}
		})
	}
	console.log(portlets);
	//console.log(JSON.stringify(portlets));
	//var page = currentPage;
	a = $.post(baseURL + 'save', {
		portlets : JSON.stringify(portlets)
		//page : page
	}, function(data){
		console.log(data);
	}).fail(function(data){console.log("Failed at saving!"); console.log(data);});
}




String.prototype.format = function() {
  var str = this;
  for (var i = 0; i < arguments.length; i++) {       
    var reg = new RegExp("\\{" + i + "\\}", "gm");             
    str = str.replace(reg, arguments[i]);
  }
  return str;
}

String.prototype.addslashes = function() {
	var str = this;
    return (str+'').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

String.prototype.toSlug = function (Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}

String.prototype.numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function setPageTitle(t) {
	document.title = t;
}


function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}  

function toExcel() {
	var nodeArray = document.getElementsByClassName("table");
	var arrayLength = nodeArray.length;
	var qqq = [];

	$('.excel-page_info_station').each(function(){
		var $t = $(this);
		var $s = $t.siblings();
		var title = this.innerText;
		var c1 = $s.find('.excel-d1').text(); //Name
		var c2 = $s.find('.excel-d2').text(); //Contractor
		var html = "<table><tbody><tr><td class='highlight'>Name</td><td>{0}</td></tr><tr><td class='highlight'>Contractor</td><td>{1}</td></tr></tbody></table>".format(c1,c2);
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	$('.excel-page_info_system').each(function(){
		var $t = $(this);
		var $s = $t.siblings();
		var title = this.innerText;
		var c1 = $s.find('.excel-d1').text(); //Name
		var c2 = $s.find('.excel-d2').text(); //description
		var c3 = $s.find('.excel-d3').text(); //Contractor
		var html = "<table><tbody><tr><td class='highlight'>Name</td><td>{0}</td></tr><tr><td class='highlight'>Description</td><td>{1}</td></tr><tr><td class='highlight'>Contractor</td><td>{2}</td></tr></tbody></table>".format(c1,c2,c3);
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	
	$('.excel-page_info').each(function(){
		var $t = $(this);
		var $s = $t.siblings();
		var $s2 = $t.siblings().find('table').find('tr.excel-dx');
		var title = this.innerText;
		var c1 = $s.find('.excel-d1').text();
		var c2 = $s.find('.excel-d2').text();
		var html = "<table><tbody><tr><td class='highlight'>Name</td><td>{0}</td></tr><tr><td class='highlight'>Description</td><td>{1}</td></tr>".format(c1,c2);
		$s2.each(function(){
			var $s2data = $(this);
			var c3 = $s2data.find('.excel-d3').text();
			html += "<tr><td class='highlight'>Station / Depot</td><td>{0}</td></tr>".format(c3);
		});
		html += "</tbody></table>";
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});

	$('.excel-normal').each(function(){ //### with error
		var $t = $(this);
		var title = this.innerText;
		var pre_html = $t.siblings().find('table').map(function(){return this.outerHTML}).toArray().join('');
		var html = "<!--"+pre_html+"..>";
		qqq.push({"title":title,"html":html});
		console.log(title);
		console.log(html+'9998');
	});
	
	$('.aaaa_excel-summary_report_1').each(function(){ //########################################
		var $t = $(this);
		var $tables = $t.siblings();
		var title = this.innerText;
		var html = "<table><thead><tr><td>No.</td><td>Job Title</td><td>Contractor</td><td>Date of Award</td><td>Type of Works</td><td>Contract Sum</td><td>Breakdown By Number - Local</td><td>Breakdown By Number - Asean</td><td>Breakdown By Number - International</td><td>Breakdown By Value - Local</td><td>Breakdown By Value - Asean</td><td>Breakdown By Value - International</td><td>Breakdown of Bumiputera : Based on Adjusted Contract Sum - Bumi (%)</td><td>Breakdown of Bumiputera : Based on Adjusted Contract Sum - Non Bumi (%)</td><td>Breakdown of Bumiputera : Based on Adjusted Contract Sum - Bumi (RM)</td><td>Breakdown of Bumiputera : Based on Contract Sum - Non Bumi (RM)</td><td>Breakdown of Bumiputera : Based on Contract Sum - Bumi / Non Bumi</td><td>Breakdown of Bumiputera : Based on Contract Sum - Bumi (RM)</td><td>Breakdown of Bumiputera : Based on Contract Sum - Non Bumi (RM)</td><td>Breakdown of Bumiputera : Based on Contract Sum - Bumi / Non Bumi</td><td>Adjusted Contract Sum</td></tr></thead><tbody>";
		
		$tables.each(function(){
		var $t = $(this);
		var exd1 = $t.find('.excel-d1').text();
		var exd2 = $t.find('.excel-d2').text();
		var exd3 = $t.find('.excel-d3').text();
		var exd4 = $t.find('.excel-d4').text();
		var exd5 = $t.find('.excel-d5').text();
		var exd6 = $t.find('.excel-d6').text();
		var exd7 = $t.find('.excel-d7').text();
		var exd8 = $t.find('.excel-d8').text();
		var exd9 = $t.find('.excel-d9').text();
		var exd10 = $t.find('.excel-d10').text();
		var exd11 = $t.find('.excel-d11').text();
		var exd12 = $t.find('.excel-d12').text();
		var exd13 = $t.find('.excel-d13').text();
		var exd14 = $t.find('.excel-d14').text();
		var exd15 = $t.find('.excel-d15').text();
		var exd16 = $t.find('.excel-d16').text();
		var exd17 = $t.find('.excel-d17').text();
		var exd18 = $t.find('.excel-d18').text();
		var exd19 = $t.find('.excel-d19').text();
		var exd20 = $t.find('.excel-d20').text();
		var exd21 = $t.find('.excel-d21').text();
			html += "<tr><td style='color:#999;'>{0}</td><td style='color:#999;'>{1}</td><td style='color:rgba(51,195,204,1);'>{2}</td><td style='color:rgba(51,195,204,1);'>{3}</td><td style='color:rgba(51,195,204,1);'>{4}</td><td style='color:rgba(51,195,204,1);'>{5}</td><td style='color:rgba(255,0,153,1);'>{6}</td><td style='color:rgba(255,0,153,1);'>{7}</td><td style='color:rgba(255,0,153,1);'>{8}</td><td style='color:rgba(255,102,0,1);'>{9}</td><td style='color:rgba(255,102,0,1);'>{10}</td><td style='color:rgba(255,102,0,1);'>{11}</td><td style='color:rgba(153,204,51,1);'>{12}</td><td style='color:rgba(153,204,51,1);'>{13}</td><td style='color:rgba(153,204,51,1);'>{14}</td><td style='color:rgba(153,204,51,1);'>{15}</td><td style='color:rgba(153,204,51,1);'>{16}</td><td style='color:rgba(153,204,51,1);'>{17}</td><td style='color:rgba(153,204,51,1);'>{18}</td><td style='color:rgba(153,204,51,1);'>{19}</td><td style='color:rgba(255,255,0,1);'>{20}</td></tr>".format(exd1,exd2,exd3,exd4,exd5,exd6,exd7,exd8,exd9,exd10,exd11,exd12,exd13,exd14,exd15,exd16,exd17,exd18,exd19,exd20,exd21);
		});
		html += "</tbody></table>";
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	
	$('.aaaa_excel-scorecard').each(function(){ //### with error
		var $t = $(this);
		var $tr = $t.siblings().find('table').find('tr.excel-dx');
		var title = this.innerText;
		var html = "<table style='text-align: center; font-size: 100%' class='table table-bordered table-condensed  table-hover'><tr style='font-size: 150%; background: #1f3b3f; color: #FFD461'><td>Package</td><td colspan='3'>Percentage, %</td><td colspan='2'>Weeks</td><td>Trend</td></tr><tr style='font-weight: bold;'><td></td><td style='color: #C9743A'>Early Planned</td><td style='color: #64D5FF'>Late Planned</td><td style='color: #009e8c;'>Actual Progress</td><td>Variance Early</td><td style='color: #FF7474;'>Variance Late</td><td></td></tr>";
		$tr.each(function(){
			var $row = $(this);
			var c1 = $row.find('.excel-d1')[0].outerHTML; //Package
			var c2 = $row.find('.excel-d2')[0].outerHTML; //Early Planned
			var c3 = $row.find('.excel-d3')[0].outerHTML; //Late Planned
			var c4 = $row.find('.excel-d4')[0].outerHTML; //Actual Progress
			var c5 = $row.find('.excel-d5')[0].outerHTML; //Variance Early
			var c6 = $row.find('.excel-d6')[0].outerHTML; //Variance Late
			var c7 = $row.find('.excel-d7')[0].outerHTML; //Trend
			html += "<tr>{0}{1}{2}{3}{4}{5}{6}</tr>".format(c1,c2,c3,c4,c5,c6,c7);
		});
		html += "</tbody></table>";
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});

	$('.excel-scurve-1').each(function(){
		var $t = $(this);
		var $s = $t.siblings();
		var title = this.innerText;
		var early = $s.find('.excel-d1').text();
		var actual = $s.find('.excel-d2').text();
		var late = $s.find('.excel-d3').text();
		var varearly = $s.find('.excel-d4').text();
		var varlate = $s.find('.excel-d5').text();
		var html = "<table><tbody><tr><td>% Early</td><td>{0}</td></tr><tr><td>% Actual</td><td>{1}</td></tr><tr><td>% Late</td><td>{2}</td></tr><tr><td>Variance Early</td><td>{3}</td></tr><tr><td>Variance Late</td><td>{4}</td></tr></tbody></table>".format(early,actual,late,varearly,varlate);
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	
	$('.aaaa_excel-selection-1').each(function(){ //### with error
		var $t = $(this);
		var $s = $t.siblings();
		var title = this.innerText;
		var html = $t.siblings().find('.excel-selection-selected').map(function(){return this.outerHTML}).toArray().join('');
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	
	$('.excel-kad-1').each(function(){
		var $t = $(this);
		var $tables = $t.siblings().find('table');
		var title = this.innerText;
		var html = "<table><thead><tr><th>KAD</th><th>Days left</th><th>Variance</th><th>Forecast</th><th>Contract</th><th>DPS</th></tr></thead><tbody>";
		
		$tables.each(function(){
		var $t = $(this);
		var name = $t.find('.excel-d1').text();
		var daysleft = $t.find('.excel-d2').text();
		var variance = $t.find('.excel-d3').text();
		var forecast = $t.find('.excel-d4').text();
		var contract = $t.find('.excel-d5').text();
		var dps = $t.find('.excel-d6').text();
			html += "<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td></tr>".format(name,daysleft,variance,forecast,contract,dps);
		});
		html += "</tbody></table>";
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	$('.excel-kad-2').each(function(){
		var $t = $(this);
		var $tables = $t.siblings().find('table');
		var title = this.innerText;
		var html = "<table><thead><tr><th>KAD</th><th>Days left</th><th>Variance</th><th>Forecast</th><th>Contract</th></tr></thead><tbody>";
		
		$tables.each(function(){
		var $t = $(this);
		var name = $t.find('.excel-d1').text();
		var daysleft = $t.find('.excel-d2').text();
		var variance = $t.find('.excel-d3').text();
		var forecast = $t.find('.excel-d4').text();
		var contract = $t.find('.excel-d5').text();
			html += "<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td></tr>".format(name,daysleft,variance,forecast,contract);
		});
		html += "</tbody></table>";
		qqq.push({"title":title,"html":html});
		// console.log(title);
		// console.log(html);
	});
	
	
	var jsonString = JSON.stringify(qqq);
	// console.log(qqq+'22222');
	// console.log(jsonString+'33333');
	var $form = $('<form action="/ppms/toexcel" method="POST" enctype="multipart/form-data" style="display:none"></form>');
	var $input = $('<input type="hidden" name="jsondata">');

	$input.attr('value',jsonString);
	// console.log(jsonString+'44444');
	$form.appendTo('body').append($input).submit();//.submit();
}

function toPDF(id) {
	//console.log("PDFING "+id);
	var $form = $('<form action="/ppms/topdf" method="POST" enctype="multipart/form-data" style="display:none"></form>');
	var $input = $('<input type="hidden" name="id">');
	var $input2 = $('<input type="hidden" name="print">');
	$input.attr('value',id);
	$input2.attr('value',1);
	$form.appendTo('body').append($input).append($input2).submit();
}