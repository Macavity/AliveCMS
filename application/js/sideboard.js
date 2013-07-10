var ts3v_url_1 = "http://tsviewer.com/ts3viewer.php?ID=1014260&text=e6c210&text_size=11&text_family=1&js=1&text_s_weight=bold&text_s_style=normal&text_s_variant=normal&text_s_decoration=none&text_s_color_h=525284&text_s_weight_h=bold&text_s_style_h=normal&text_s_variant_h=normal&text_s_decoration_h=underline&text_i_weight=normal&text_i_style=normal&text_i_variant=normal&text_i_decoration=none&text_i_color_h=525284&text_i_weight_h=normal&text_i_style_h=normal&text_i_variant_h=normal&text_i_decoration_h=underline&text_c_weight=normal&text_c_style=normal&text_c_variant=normal&text_c_decoration=none&text_c_color_h=525284&text_c_weight_h=normal&text_c_style_h=normal&text_c_variant_h=normal&text_c_decoration_h=underline&text_u_weight=bold&text_u_style=normal&text_u_variant=normal&text_u_decoration=none&text_u_color_h=525284&text_u_weight_h=bold&text_u_style_h=normal&text_u_variant_h=normal&text_u_decoration_h=none";
$(document).ready(function() {
	$("#ts_button").mouseover(function(){ $("#sb_passive_large").addClass("sb_open"); });
	$("#ts_button").mouseout(function(){ $("#sb_passive_large").removeClass("sb_open"); });
	$("#ts_button").click(function(){
		// open slide
		if(!$(this).hasClass("sb_active")){
			$("#ts_label, #sb_passive_large, #ts_button").addClass("sb_active");
			$("#ts_control").addClass("sb_open");
			$("#ts_control").addClass("sb_n_load");
			
			if($("#ts3viewer_1014260").length === 0){
				$("#ts_overlay").addClass("sb_n_load");
				$("#ts_overlay").append('<div id="ts3viewer_1014260" style="margin-left:3px; margin-top:8px"> </div>');
				ts3v_display.init(ts3v_url_1, 1014260, 100);
			}
			$("#ts_overlay").show("slide", { to: { width: 319 } }, 500, callback_sb_open );
			$("#ts_control").animate({left:"320px"}, 499);
		}
		// close slide
		else{
			$("#ts_overlay").hide("slide", { to: { width: 0 } }, 500, callback_sb_close );
			$("#ts_control").animate({left:"0px"}, 499);
		}
	});
	
	function callback_sb_open(){
		$("#ts_control").css("left", "320px");
		$("#ts_control, #ts_overlay").removeClass("sb_n_load");
	}

	function callback_sb_close(){
		$("#ts_label, #sb_passive_large, #ts_button").removeClass("sb_active");
		$("#ts_control").removeClass("sb_open");
		$("#ts_control").css("left", "0px");
			
	}
});
