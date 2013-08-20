<?php $doc->addScriptDeclaration('
jQuery(document).ready(function(){
	var action_clock = jQuery(".action-clock").FlipClock({
		"countdown": true,
		"autoStart": false,
                "clockFace": "dailyCounter"
	});
	action_clock.setTime('.$interval.');
	action_clock.start();
});
') ;

?>

<div class="action-clock"></div>
