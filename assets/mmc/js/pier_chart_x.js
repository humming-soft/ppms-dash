$(document).ready(function(){
	
	
	// $('#plate_vector_map').load('../assets/svg/plate_vector_map.svg',function(){
	$('.pier_plate').load('a',function(){
		alert('poi');
		var json = [ 
			{"vector_track":"legend_v_track_1","vector_status":"0","url":""},
		];
		for (i = 0; i < json.length; i++) {
			var b = json[i];
			vector_track_name = b.vector_track;
			vector_track_status = b.vector_status;
			vector_track_url = b.url;
		}
	});
	
	
	$('div#pier_id_0001 .pier span.content').load('../assets/svg/d1/pier_a1b1c1d1.svg');
	$('div#pier_id_0001 .span span.content').load('../assets/svg/span/span_1.svg');
	$('div#pier_id_0002 .pier span.content').load('../assets/svg/d1/pier_a2b1c1d1.svg');
	$('div#pier_id_0002 .span span.content').load('../assets/svg/span/span_1.svg');
	$('div#pier_id_0003 .pier span.content').load('../assets/svg/d1/pier_a3b1c1d1.svg');
	$('div#pier_id_0003 .span span.content').load('../assets/svg/span/span_1.svg');
	$('div#pier_id_0004 .pier span.content').load('../assets/svg/d1/pier_a4b1c1d1.svg');
	$('div#pier_id_0004 .span span.content').load('../assets/svg/span/span_1.svg');
	$('div#pier_id_0005 .pier span.content').load('../assets/svg/d1/pier_axb1c1d1.svg');
	$('div#pier_id_0005 .span span.content').load('../assets/svg/span/span_1.svg');
	
	$('div#pier_id_0006 .pier span.content').load('../assets/svg/d2/pier_a1b1c1d2.svg');
	$('div#pier_id_0006 .span span.content').load('../assets/svg/span/span_x.svg');
	$('div#pier_id_0007 .pier span.content').load('../assets/svg/d2/pier_a2b1c1d2.svg');
	$('div#pier_id_0007 .span span.content').load('../assets/svg/span/span_x.svg');
	$('div#pier_id_0008 .pier span.content').load('../assets/svg/d2/pier_a3b1c1d2.svg');
	$('div#pier_id_0008 .span span.content').load('../assets/svg/span/span_x.svg');
	$('div#pier_id_0009 .pier span.content').load('../assets/svg/d2/pier_a4b1c1d2.svg');
	$('div#pier_id_0009 .span span.content').load('../assets/svg/span/span_x.svg');
	$('div#pier_id_0010 .pier span.content').load('../assets/svg/d2/pier_axb1c1d2.svg');
	$('div#pier_id_0010 .span span.content').load('../assets/svg/span/span_x.svg');
	
	$('div#pier_id_0011 .pier span.content').load('../assets/svg/d3/pier_a1b1c1d3.svg');
	$('div#pier_id_0011 .span span.content').load('../assets/svg/span/span_1.svg');
	$('div#pier_id_0012 .pier span.content').load('../assets/svg/d3/pier_a2b1c1d3.svg');
	$('div#pier_id_0012 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0013 .pier span.content').load('../assets/svg/d3/pier_a3b1c1d3.svg');
	$('div#pier_id_0013 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0014 .pier span.content').load('../assets/svg/d3/pier_a4b1c1d3.svg');
	$('div#pier_id_0014 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0015 .pier span.content').load('../assets/svg/d3/pier_axb1c1d3.svg');
	$('div#pier_id_0015 .span span.content').load('../assets/svg/span/span_2.svg');
	
	$('div#pier_id_0016 .pier span.content').load('../assets/svg/d4/pier_a1b1c1d4.svg');
	$('div#pier_id_0016 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0017 .pier span.content').load('../assets/svg/d4/pier_a2b1c1d4.svg');
	$('div#pier_id_0017 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0018 .pier span.content').load('../assets/svg/d4/pier_a3b1c1d4.svg');
	$('div#pier_id_0018 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0019 .pier span.content').load('../assets/svg/d4/pier_a4b1c1d4.svg');
	$('div#pier_id_0019 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0020 .pier span.content').load('../assets/svg/d4/pier_axb1c1d4.svg');
	$('div#pier_id_0020 .span span.content').load('../assets/svg/span/span_4.svg');
	
	$('div#pier_id_0021 .pier span.content').load('../assets/svg/d5/pier_a1b1c1d5.svg');
	$('div#pier_id_0021 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0022 .pier span.content').load('../assets/svg/d5/pier_a2b1c1d5.svg');
	$('div#pier_id_0022 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0023 .pier span.content').load('../assets/svg/d5/pier_a3b1c1d5.svg');
	$('div#pier_id_0023 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0024 .pier span.content').load('../assets/svg/d5/pier_a4b1c1d5.svg');
	$('div#pier_id_0024 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0025 .pier span.content').load('../assets/svg/d5/pier_axb1c1d5.svg');
	$('div#pier_id_0025 .span span.content').load('../assets/svg/span/span_4.svg');
	
	$('div#pier_id_0026 .pier span.content').load('../assets/svg/d6/pier_a2b2c2d6.svg');
	$('div#pier_id_0026 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0027 .pier span.content').load('../assets/svg/d7/pier_a2b2c2d7.svg');
	$('div#pier_id_0027 .span span.content').load('../assets/svg/span/span_4.svg');
	$('div#pier_id_0028 .pier span.content').load('../assets/svg/d8/pier_a11b2c2d811.svg');
	$('div#pier_id_0028 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0029 .pier span.content').load('../assets/svg/d8/pier_a11b2c2d812.svg');
	$('div#pier_id_0029 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0030 .pier span.content').load('../assets/svg/d8/pier_a11b2c2d821.svg');
	$('div#pier_id_0030 .span span.content').load('../assets/svg/span/span_2.svg');
	
	$('div#pier_id_0031 .pier span.content').load('../assets/svg/d8/pier_a22b2c2d811.svg');
	$('div#pier_id_0031 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0032 .pier span.content').load('../assets/svg/d8/pier_a22b2c2d812.svg');
	$('div#pier_id_0032 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0033 .pier span.content').load('../assets/svg/d8/pier_a22b2c2d821.svg');
	$('div#pier_id_0033 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0034 .pier span.content').load('../assets/svg/d3/pier_a1b1c1d3.svg');
	$('div#pier_id_0034 .span span.content').load('../assets/svg/span/span_2.svg');
	$('div#pier_id_0035 .pier span.content').load('../assets/svg/d3/pier_a1b1c1d3.svg');
	$('div#pier_id_0035 .span span.content').load('../assets/svg/span/span_2.svg');
	
	
});	
