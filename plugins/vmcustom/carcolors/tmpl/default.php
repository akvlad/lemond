<script type='text/javascript'>
jQuery(document).ready(function(){
        jQuery("a.color-item").click(function(e) {
            var $e=jQuery(e.target);
            jQuery.facebox({ div: '#images<?= $product->virtuemart_product_id ?>' },function() {
            var imgs=jQuery('#images<?= $product->virtuemart_product_id ?>');
                var link='img[src$=\''+$e.attr('link')+'\']';
                var li=imgs.find(link).closest('li');
                var li_n=imgs.find('li').index(li);
                initialCarouselPosition=li_n+1;
            });
        });
})
</script>
<div class="colors">
	<span class="color-name">Цвет:</span>
	<span class="color-type">
	<?php foreach($this->params->items as $color) { ?>
		<a class="color-item" href="#images<?= $product->virtuemart_product_id ?>" 
                   link='<?= $color->link ?>'><?= $color->color_name ?>, </a>
	<?php } ?>
	</span>
</div>