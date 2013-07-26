<?php $doc->addScriptDeclaration('
jQuery(document).ready(function(){
    jQuery(".moduletable'.$moduleclass_sfx.' h3").click(function(){
        window.location="'.JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=15').'";
    })
})
') ?>
<div class="day_product_<?= $classSfx ?>">
	
	<?php echo "<div id='product1-img' style=\"background:url('".$product1->images[0]->getThumbUrl()."') center center no-repeat transparent; background-size:contain; \"></div>"; 
		/*<!----- для большой картники - displayMediaFull ---->*/
	
		echo "<div id='product2-img' style=\"background:url('".$product2->images[0]->getThumbUrl()."') center center no-repeat transparent; background-size:contain; \"></div>"; ?>
		<!----- для большой картники - displayMediaFull --
		//var_dump($product1->prices);-->

	<div id="bant-img"> 
		<img src="/templates/lemond/images/bant.png">
	</div>
	<div id="price1"> 
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="40" width="70">
                <defs>
                    <path id="price1-path" d="M 0 22 q 17 13 60 0" stroke="blue" stroke-width="1" fill="none" />
                </defs>
                <text x="0" y="0" style="fill:white" font-size="15" font-family="Arial">
                    <textPath xlink:href="#price1-path"><?php echo round($product1->prices['salesPrice']);?> грн.</textPath>
                </text>
            </svg>
		
	</div>
	<div id="price2"> 
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="40" width="70">
                <defs>
                    <path id="price2-path" d="M 10 40 q 17 -16 43 -22 T 63 -5" stroke="blue" stroke-width="1" fill="none" />
                </defs>
                <text x="0" y="0" style="fill:white" font-size="15" font-family="Arial">
                    <textPath xlink:href="#price2-path"><?php echo round($product2->prices['salesPrice']);?> грн.</textPath>
                </text>
                
            </svg>
	</div>

	<div id="price-summ"> 
		<?php echo round($product1->prices['salesPrice']+$product2->prices['salesPrice']); ?> грн.
	</div>
	<div id="new-price"> 
            <canvas id="MerDayCanvas<?= $product1->virtuemart_product_id ?>" width="95" height="20">
                                                            </canvas>
                                                            <script type="text/javascript">
                                                                var cnv=document.getElementById('MerDayCanvas<?= $product1->virtuemart_product_id ?>');
                                                                var ctx=cnv.getContext("2d");
                                                                var gradient=ctx.createLinearGradient(0,0,0,15);
                                                                gradient.addColorStop(0.0, '#ff0000');
                                                                gradient.addColorStop(0.9, '#9A0027');
                                                                ctx.fillStyle = gradient;
                                                                ctx.font = "bold 18px Microsoft YaHei";
                                                                ctx.fillText("<?php echo $newPrice;?> грн.", 0, 15);
                                                            </script>
	</div>
        <?= $actprodModel->getBuyForm(array($actionProd)); ?>
</div>


