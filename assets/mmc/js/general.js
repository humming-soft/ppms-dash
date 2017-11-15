$(document).ready(function(){
	
	
	// 
	// directory
	// 
	
	$('#pl_package_V201').click(function(){
		window.location.href = "viaduct.php";
	})
	$('span.iv_pierchart').click(function(){
		window.location.href = "pier.php";
	})
	$('#plate_vector_map').load('assets/mmc/svg/plate_vector_map.svg',function(){

		

		// vector_track
		var json = [ 
			{"vector_track":"legend_v_track_1","vector_status":"0","url":""},
			{"vector_track":"legend_v_track_2","vector_status":"1","url":""},
			{"vector_track":"legend_v_track_3","vector_status":"2","url":""},
			{"vector_track":"legend_v_track_4","vector_status":"3","url":""},
			{"vector_track":"v_track_201","vector_status":"1","url":""},
			{"vector_track":"v_track_202","vector_status":"2","url":""},
			{"vector_track":"v_track_203","vector_status":"3","url":""},
			{"vector_track":"v_track_ug","vector_status":"1","url":""},
			{"vector_track":"v_track_204","vector_status":"0","url":""},
			{"vector_track":"v_track_205","vector_status":"0","url":""},
			{"vector_track":"v_track_206","vector_status":"0","url":""},
			{"vector_track":"v_track_207","vector_status":"0","url":""},
			{"vector_track":"v_track_208","vector_status":"0","url":""},
			{"vector_track":"v_track_209","vector_status":"0","url":""},
			{"vector_track":"v_track_210","vector_status":"2","url":""}
		];
		for (i = 0; i < json.length; i++) {
			var b = json[i];
			vector_track_name = b.vector_track;
			vector_track_status = b.vector_status;
			vector_track_url = b.url;
			if (vector_track_status==0) {/*blank*/
				$('#'+vector_track_name).css({'fill':'#777777','stroke':'#222222'});
			} else if (vector_track_status==1) {/*on schedule*/
				$('#'+vector_track_name).css({'fill':'#00ff55','stroke':'#00ff55'});
			} else if (vector_track_status==2) {/*behind late*/
				$('#'+vector_track_name).css({'fill':'#ff0055','stroke':'#ff0055'});
			} else if (vector_track_status==3) {/*critical*/
				blink('#'+vector_track_name, -1, 500);
				function blink(elem, times, speed) {
					if (times > 0 || times < 0) {
						if ($(elem).fadeTo( 500, 0.33 )) 
							$(elem).fadeTo( 500, 1);
						else
							$(elem).fadeTo( 500, 0.33 );
					}
					clearTimeout(function () {
						blink(elem, times, speed);
					});
					
					if (times > 0 || times < 0) {
						setTimeout(function () {
							blink(elem, times, speed);
						}, speed);
						times -= .5;
					}
				}
				$('#'+vector_track_name).toggle('pulsate').css({'stroke-width':'5','fill':'#ff0055','stroke':'#ff0055'});
			};
		}
		
		var json = [ 
			{"vector_station":"legend_v_station_1","vector_status":"0"},
			{"vector_station":"legend_v_station_2","vector_status":"1"},
			{"vector_station":"legend_v_station_3","vector_status":"2"},
			{"vector_station":"legend_v_station_4","vector_status":"3"},
			{"vector_station":"v_station_1","vector_status":"1"},
			{"vector_station":"v_station_2","vector_status":"2"},
			{"vector_station":"v_station_3","vector_status":"3"},
			{"vector_station":"v_station_4","vector_status":"0"},
			{"vector_station":"v_station_5","vector_status":"1"},
			{"vector_station":"v_station_6","vector_status":"1"},
			{"vector_station":"v_station_7","vector_status":"1"},
			{"vector_station":"v_station_8","vector_status":"1"},
			{"vector_station":"v_station_9","vector_status":"1"},
			{"vector_station":"v_station_10","vector_status":"2"},
			{"vector_station":"v_station_11","vector_status":"3"},
			{"vector_station":"v_station_12","vector_status":"0"},
			{"vector_station":"v_station_13","vector_status":"1"},
			{"vector_station":"v_station_14","vector_status":"2"},
			{"vector_station":"v_station_15","vector_status":"3"},
			{"vector_station":"v_station_16","vector_status":"0"},
			{"vector_station":"v_station_17","vector_status":"1"},
			{"vector_station":"v_station_18","vector_status":"2"},
			{"vector_station":"v_station_19","vector_status":"3"},
			{"vector_station":"v_station_20","vector_status":"0"},
			{"vector_station":"v_station_21","vector_status":"1"},
			{"vector_station":"v_station_22","vector_status":"1"},
			{"vector_station":"v_station_23","vector_status":"1"},
			{"vector_station":"v_station_24","vector_status":"1"},
			{"vector_station":"v_station_25","vector_status":"1"},
			{"vector_station":"v_station_26","vector_status":"1"},
			{"vector_station":"v_station_27","vector_status":"1"},
			{"vector_station":"v_station_28","vector_status":"1"},
			{"vector_station":"v_station_29","vector_status":"1"},
			{"vector_station":"v_station_30","vector_status":"1"},
			{"vector_station":"v_station_31","vector_status":"2"},
			{"vector_station":"v_station_32","vector_status":"3"},
			{"vector_station":"v_station_33","vector_status":"0"},
			{"vector_station":"v_station_34","vector_status":"0"},
			{"vector_station":"v_station_35","vector_status":"1"},
			{"vector_station":"v_station_36","vector_status":"2"},
			{"vector_station":"v_station_37","vector_status":"3"},
			{"vector_station":"v_station_38","vector_status":"1"}
		];
		for (i = 0; i < json.length; i++) {
			var b = json[i];
			vector_station_name = b.vector_station;
			vector_station_status = b.vector_status;
			if (vector_station_status==0) {/*blank*/
				$('#'+vector_station_name).css({'fill':'#ffffff','stroke':'#222222'});
			} else if (vector_station_status==1) {/*on schedule*/
				$('#'+vector_station_name).css({'fill':'#ffffff','stroke':'#00ff55'});
			} else if (vector_station_status==2) {/*behind late*/
				$('#'+vector_station_name).css({'fill':'#ffffff','stroke':'#ff0055'});
			} else if (vector_station_status==3) {/*critical*/
				blink('#'+vector_station_name, -1, 500);
				function blink(elem, times, speed) {
					if (times > 0 || times < 0) {
						if ($(elem).fadeTo( 500, 0.33 )) 
							$(elem).fadeTo( 500, 1);
						else
							$(elem).fadeTo( 500, 0.33 );
					}
					clearTimeout(function () {
						blink(elem, times, speed);
					});

					if (times > 0 || times < 0) {
						setTimeout(function () {
							blink(elem, times, speed);
						}, speed);
						times -= .5;
					}
				}
				$('#'+vector_station_name).toggle('pulsate').css({'fill':'#ffffff','stroke':'#ff0055'});
			};
		}
		
		var json = [ 
			{"vector_depot":"legend_v_depot_1","vector_status":"0"},
			{"vector_depot":"legend_v_depot_2","vector_status":"1"},
			{"vector_depot":"legend_v_depot_3","vector_status":"2"},
			{"vector_depot":"legend_v_depot_4","vector_status":"3"},
			{"vector_depot":"v_depot_1","vector_status":"1"}
		];
		for (i = 0; i < json.length; i++) {
			var b = json[i];
			vector_depot_name = b.vector_depot;
			vector_depot_status = b.vector_status;
			if (vector_depot_status==0) {/*blank*/
				$('#'+vector_depot_name).css({'fill':'#ffffff','stroke':'#222222'});
			} else if (vector_depot_status==1) {/*on schedule*/
				$('#'+vector_depot_name).css({'fill':'#ffffff','stroke':'#00ff55'});
			} else if (vector_depot_status==2) {/*behind late*/
				$('#'+vector_depot_name).css({'fill':'#ffffff','stroke':'#ff0055'});
			} else if (vector_depot_status==3) {/*critical*/
				blink('#'+vector_depot_name, -1, 500);
				function blink(elem, times, speed) {
					if (times > 0 || times < 0) {
						if ($(elem).fadeTo( 500, 0.33 )) 
							$(elem).fadeTo( 500, 1);
						else
							$(elem).fadeTo( 500, 0.33 );
					}
					clearTimeout(function () {
						blink(elem, times, speed);
					});

					if (times > 0 || times < 0) {
						setTimeout(function () {
							blink(elem, times, speed);
						}, speed);
						times -= .5;
					}
				}
				$('#'+vector_depot_name).toggle('pulsate').css({'fill':'#ffffff','stroke':'#ff0055'});
			};
		}
		
		
		
		
		
	});
	
	
	
	
	
	var json = [ 
		{"chart_title":"System Overall","chart_name":"","chart_id":"db_donut_0","chart_value":"47","set_donut":"1","url":"systems/summary"},
		{"chart_title":"S&TC /PSD /APG","chart_name":"Signalling & Train Control, Automatic Platform Gate / Platform Screen Door","chart_id":"db_donut_1","chart_value":"41","set_donut":"2","url":"stc-psd-apg/index"},
		{"chart_title":"ICSS& CMMS","chart_name":"Integrated Control Supervisory System & Computerised Maintenance Management System","chart_id":"db_donut_2","chart_value":"75","set_donut":"2","url":"icss-cmms/index"},
		{"chart_title":"ET&DE","chart_name":"Electric Train & Depot Equipment","chart_id":"db_donut_3","chart_value":"15","set_donut":"2","url":"etde/index"},
		{"chart_title":"TW&MV","chart_name":"Trackworks & Maintenance Vehicle / Works Train","chart_id":"db_donut_4","chart_value":"49","set_donut":"2","url":"twmv/index"},
		{"chart_title":"PS&DS","chart_name":"Power Supply & Distribution System","chart_id":"db_donut_5","chart_value":"50","set_donut":"2","url":"psds/index"},
		{"chart_title":"COMMS","chart_name":"Communications, GIRN & IDS","chart_id":"db_donut_6","chart_value":"85","set_donut":"2","url":"comms/index"},
		{"chart_title":"AFC","chart_name":"Automatic Fare Collection","chart_id":"db_donut_7","chart_value":"17","set_donut":"2","url":"afc/index"}
	];
	
	for (i = 0; i < json.length; i++) {
		var b = json[i];
		use_chart_title = b.chart_title;
		use_chart_id = b.chart_id;
		use_chart_value = b.chart_value;
		use_chart_set_donut = b.set_donut;
		use_chart_name = b.chart_name;
		use_chart_url= b.url;
		
		
		
		if (use_chart_set_donut==1) {
			use_chart_font_size = '25px';
			use_chart_font_color = '#ffffff';
			if (use_chart_value >= 75) {
				use_chart_donut_color = '#00ff55';
			} else if (use_chart_value >= 50) {
				use_chart_donut_color = '#ffff00';
			} else if (use_chart_value >= 25) {
				use_chart_donut_color = '#ff7700';
			} else if (use_chart_value < 25) {
				use_chart_donut_color = '#ff0055';
			};
			use_chart_circle_border = 'border:1px solid '+use_chart_donut_color+';'
			use_chart_svg_width = 165;
			use_chart_svg_height = 165;
			use_chart_circle_r = 80;
			use_chart_circle_cx = 81.5;
			use_chart_circle_cy = 81.5;
			use_chart_circle_data_total = 503;
			use_chart_circle_data_used = use_chart_value / 100 * use_chart_circle_data_total;

			
			donut_body = '<div><a class="url_donut_system" style="outline:0; text-decoration: none; color: #fff;" href="'+use_chart_url+'" title="'+use_chart_name+'"><span class="donut_title1_name">'+use_chart_title+'</span></a><svg class="svg_donut_system" width="'+use_chart_svg_width+'" height="'+use_chart_svg_height+'" style="border:1px solid '+use_chart_donut_color+';"><text style="font-style:normal;font-weight:bold;font-size:'+use_chart_font_size+';fill:'+use_chart_font_color+';" transform="matrix(0,1,-1,0,0,0)"><tspan sodipodi:role="line" x="50" y="-65">'+use_chart_value+'%</tspan></text><circle class="svg_donut_system_circle" r="'+use_chart_circle_r+'" cx="'+use_chart_circle_cx+'" cy="'+use_chart_circle_cy+'" class="pie" style="stroke: '+use_chart_donut_color+';stroke-dasharray: '+use_chart_circle_data_used+','+use_chart_circle_data_total+';"></circle></svg></div>';
			
				
			$('.dp_top').append(donut_body);
		} else if (use_chart_set_donut==2) {
			use_chart_font_size = '20px';
			use_chart_font_color = '#ffffff';
			if (use_chart_value >= 75) {
				use_chart_donut_color = '#00ff55';
			} else if (use_chart_value >= 50) {
				use_chart_donut_color = '#ffff00';
			} else if (use_chart_value >= 25) {
				use_chart_donut_color = '#ff7700';
			} else if (use_chart_value < 25) {
				use_chart_donut_color = '#ff0055';
			};
			use_chart_svg_width = 62;
			use_chart_svg_height = 62;
			use_chart_circle_r = 30;
			use_chart_circle_cx = 30;
			use_chart_circle_cy = 30;
			use_chart_circle_data_total = 189;
			use_chart_circle_data_used = use_chart_value / 100 * use_chart_circle_data_total;

			
			donut_body = '<div><a class="url_donut_system" style="text-decoration: none; color: #fff;" href="'+use_chart_url+'" title="'+use_chart_name+'"><span class="donut_title2_name">'+use_chart_title+'</span></a><svg class="svg_donut_system" width="'+use_chart_svg_width+'" height="'+use_chart_svg_height+'" style="border:1px solid '+use_chart_donut_color+';"><text style="font-style:normal;font-weight:bold;font-size:'+use_chart_font_size+';fill:'+use_chart_font_color+';" transform="matrix(0,1,-1,0,0,0)"><tspan sodipodi:role="line" x="11" y="-24">'+use_chart_value+'%</tspan></text><circle class="svg_donut_system_circle" r="'+use_chart_circle_r+'" cx="'+use_chart_circle_cx+'" cy="'+use_chart_circle_cy+'" class="pie" style="stroke: '+use_chart_donut_color+';stroke-dasharray: '+use_chart_circle_data_used+','+use_chart_circle_data_total+';"></circle></svg></div>';
				
			$('#dp_'+use_chart_id+'').append(donut_body);
			
		};
		
		
		
		
		
	}
	
	// current date
	var fullDate = new Date()
	var month = new Array();
	month[0] = "JAN";
	month[1] = "FEB";
	month[2] = "MAR";
	month[3] = "APR";
	month[4] = "MAY";
	month[5] = "JUN";
	month[6] = "JUL";
	month[7] = "AUG";
	month[8] = "SEP";
	month[9] = "OCT";
	month[10] = "NOV";
	month[11] = "DEC";
	var n = month[fullDate.getMonth()];
	var currentDate = fullDate.getDate() + " " + n + " " + fullDate.getFullYear();
	$('#current_date').text(currentDate);
	
	
	
	
});	
