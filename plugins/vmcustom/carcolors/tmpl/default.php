<div class="colors">
	<span class="color-name">Цвет:</span>
	<span class="color-type">
	<?php foreach($this->params->items as $color) { ?>
		<span class="color-item"><?= $color->color_name ?>, </span>
	<?php } ?>
	</span>
</div>