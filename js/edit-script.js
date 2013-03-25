jQuery(function($){
	$(document).ready(function(){

	if($('#sponsorship-suggest')){
		$('#sponsorship-suggest').suggest(userSettings.ajaxurl+"?action=ajax-tag-search&tax=sponsorship-term",{
			multiple:false,
			multipleSep: ","
		});
	}

	if($('#project-suggest')){
		$('#project-suggest').suggest(userSettings.ajaxurl+"?action=ajax-tag-search&tax=project-term",{
			multiple:false,
			multipleSep: ","
		});
	}

	if($('#block-suggest')){
		$('#block-suggest').suggest(userSettings.ajaxurl+"?action=ajax-tag-search&tax=block-term",{
			multiple:false,
			multipleSep: ","
		});
	}

	if($('#area-suggest')){
		$('#area-suggest').suggest(userSettings.ajaxurl+"?action=ajax-tag-search&tax=area",{
			multiple:false,
			multipleSep: ","
		});
	}

	if ($('.user-suggest')) {
		$(".user-suggest").suggest(userSettings.ajaxurl+'?action=suggest_user',{
			idElement:"user_id",
			separatedValue:";",
			multiSelectInput:"id_card",
			multiseparatedValue:" | "
		});
	}


	if($('.datepicker').exists()){
		$('.datepicker').datepicker();
	}

	//OL
	if($('#map').exists()) {
		initMap();
	}
	
	//GMap
	if($('#Map-div').exists()) {
		initiz();
	}
	
});
});