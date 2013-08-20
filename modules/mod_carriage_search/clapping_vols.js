function headerClick(e)
{
	var volClicked=jQuery(e.target).closest('.search_vol');
	var volContent=volClicked.find('.vol_contents');
	var volHead=volClicked.find('.vol_header');
	if(volContent.is(':visible')){
		volHead.removeClass('open');
	}
	else{
		volHead.addClass('open');
	}
	volContent.toggle();
}

jQuery(document).ready(function(){
	jQuery('.vol_header').click(headerClick);
})