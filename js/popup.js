jQuery(document).ready(function($){
	$('.lm-popup').click(function(){
		var popup_id = $(this).attr('data-target');
		$(popup_id).modal('show');
	});
});
