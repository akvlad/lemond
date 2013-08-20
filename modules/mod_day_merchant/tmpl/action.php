<?php $doc->addScriptDeclaration('
jQuery(document).ready(function(){
    jQuery(".moduletable'.$moduleclass_sfx.' h3").click(function(){
        window.location="'.JRoute::_('/').'";
    })
})
') ?>
<div class="day_product_<?= $classSfx ?>">

	<?php echo "<div id=\"product1-img\" style=\"background:url(".$product1->images[0]->getThumbUrl().") center center no-repeat transparent; background-size: contain; \"></div>"; ?>
	<div id="flower">
		<img src="/templates/lemond/images/flower.png">
	</div>
	<div id="title">
		<?php echo $product1->product_name;?>
	</div>
	<div id="oldPrice">
		<?php echo round($product1->prices['priceWithoutTax']); ?> грн.
	</div>
	<div id="newPrice">
            <canvas id="ActCanvas<?= $product1->virtuemart_product_id ?>" width="95" height="20">
                                                            </canvas>
                                                            <script type="text/javascript">
                                                                var cnv=document.getElementById('ActCanvas<?= $product1->virtuemart_product_id ?>');
                                                                var ctx=cnv.getContext("2d");
                                                                var gradient=ctx.createLinearGradient(0,0,0,15);
                                                                gradient.addColorStop(0.0, '#ff0000');
                                                                gradient.addColorStop(0.9, '#9A0027');
                                                                ctx.fillStyle = gradient;
                                                                ctx.font = "bold 18px Microsoft YaHei";
                                                                ctx.fillText("<?php echo round($product1->prices['salesPrice']);?> грн.", 0, 15);
                                                            </script>
	</div>
    <?= $prodModel->getBuyForm(array($product1)) ?>
</div>
