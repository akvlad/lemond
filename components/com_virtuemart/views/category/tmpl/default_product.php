<?php            
            $needPlash=false;
            if(count($product->customfields) > 0 ){
            foreach($product->customfields as $customfield){
                if($customfield->virtuemart_custom_id==5){
                    $needPlash= true;
                    $plashClass=$customfield->custom_value;
                }
            }
            } $needVideo=isset($product->displayPlugins['vmvideo']);
                        $maxImages=4;
                        if($needVideo) $maxImages=3;
                        
                        if(count($product->images)>1 || $needVideo ){ ?> 
                        <div class="showable-medias">
                            <?php for($i=1;$i<min(count($product->images),$maxImages); ++$i) { ?>
                            <div class="showable-image">
                                <a rel="facebox" href="#images<?= $product->virtuemart_product_id ?>" >
                                <?php echo $product->images[$i]->displayMediaThumb('', false);	?>
                                </a>
                            </div>
                            <?php }?>
                            <?php if($needVideo){ ?>
                             <div class="showable-image">
                                <?= $product->displayPlugins['vmvideo']['thumb'] ?>
                            </div>                               
                            <? } ?>
                        </div>
                        <?php } 
                        if ($needPlash) { ?>
                        <div class="plash <?=$plashClass ?>"></div>
                        <?php } ?>
                        
                        <div class="spacer">
				<div class="prod-img">
				    <a title="<?php echo $product->link ?>" rel="vm-additional-images" href="<?php echo $product->link; ?>">
						<?php echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false);	?>
					 </a>

					<!-- The "Average Customer Rating" Part -->
					<?php if ($this->showRating) { ?>
					<span class="contentpagetitle"><?php echo JText::_ ('COM_VIRTUEMART_CUSTOMER_RATING') ?>:</span>
					<br/>
					<?php
					// $img_url = JURI::root().VmConfig::get('assets_general_path').'/reviews/'.$product->votes->rating.'.gif';
					// echo JHTML::image($img_url, $product->votes->rating.' '.JText::_('COM_VIRTUEMART_REVIEW_STARS'));
					// echo JText::_('COM_VIRTUEMART_TOTAL_VOTES').": ". $product->votes->allvotes;
					?>
					<?php } ?>
 					<?php
						if ( VmConfig::get ('display_stock', 1)) { ?>
						<!-- 						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none')){?> -->
						<div class="paddingtop8">
							<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
							<span class="stock-level"><?php echo JText::_ ('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
						</div>
						<?php } ?>
				</div>

				<div class="prod-name">

					<?php echo JHTML::link ($product->link, $product->product_name); ?>

					<?php // Product Short Description
					if (!empty($product->product_s_desc)) {
						?>
						<p class="product_s_desc">
							<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 40, '...') ?>
						</p>
						<?php } ?>
</div>
					<div class="product-price marginbottom12" id="productPrice<?php echo $product->virtuemart_product_id ?>">
						<?php
						if ($this->show_prices == '1') {
							if ($product->prices['salesPrice']<=0 and VmConfig::get ('askprice', 1) and  !$product->images[0]->file_is_downloadable) {
								echo JText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE');
							}
							//todo add config settings
							if ($this->showBasePrice) {
								echo $this->currency->createPriceDiv ('basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $product->prices);
								echo $this->currency->createPriceDiv ('basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $product->prices);
							}
							//echo $this->currency->createPriceDiv ('variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $product->prices);
                                                        //echo round($product->prices['basePrice'],$this->currency->_priceConfig['salesPrice'][1]).' '.$product->prices['salesPrice'];
							if (round($product->prices['basePrice'],2) != round($product->prices['salesPrice'],2)) {?>
                                                            <canvas id="DiscountCanvas<?= $product->virtuemart_product_id ?>" width="95" height="20">
                                                            </canvas>
                                                            <script type="text/javascript">
                                                                var cnv=document.getElementById('DiscountCanvas<?= $product->virtuemart_product_id ?>');
                                                                var ctx=cnv.getContext("2d");
                                                                var gradient=ctx.createLinearGradient(0,0,0,15);
                                                                gradient.addColorStop(0.0, '#ff0000');
                                                                gradient.addColorStop(0.9, '#9A0027');
                                                                ctx.fillStyle = gradient;
                                                                ctx.font = "bold 15px Microsoft YaHei";
                                                                ctx.fillText("<?= round($product->prices['salesPrice'],2)?> грн.", 0, 15);
                                                            </script>
                                                            <? //echo $this->currency->createPriceDiv ('salesPrice', 'redprice', $product->prices);
                                                             echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
							}
                                                        else echo $this->currency->createPriceDiv ('salesPrice', ';kl', $product->prices,'noRed');
							/*if (round($product->prices['salesPriceWithDiscount'],$this->currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) {
								echo $this->currency->createPriceDiv ('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
							}*/
							
							
							//echo $this->currency->createPriceDiv ('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $product->prices);
							//echo $this->currency->createPriceDiv ('taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $product->prices);
							//$unitPriceDescription = JText::sprintf ('COM_VIRTUEMART_PRODUCT_UNITPRICE', $product->product_unit);
							//echo $this->currency->createPriceDiv ('unitPrice', $unitPriceDescription, $product->prices);
						} ?>

					</div>


				
				<div class="clear"></div>
			
                        <!-- FORM -->
                                <?= $product->buyForm ?>
      
                        
                        
                        
                        
                        
                        </div>
                        
                        <div class="carousel-wrapper" style="display:none">
                            <div id="images<?= $product->virtuemart_product_id ?>">
                                <div class="popup-imgs">
                                    <p class="popup-thumbs">
                                
                              <?php $i=1; foreach ($product->images as $image) { ?>
                                      <?= $image->displayMediaThumb('onclick="imageClick(event,'.$i.')"', false); ?>
                                <?php ++$i; } if($needVideo){  ?> 
                                    <img src="/images/video_icon.jpg" onclick="imageClick(event,<?= $i ?>)" />
                                <?php } ?>
                                    </p>
                            <ul  class="product-carousel jcarousel-skin-milkbox">
                                <?php foreach ($product->images as $image) {?>
                                <li><div class="popup-img">
                                            <?= $image->displayMediaFull('', false); ?>
                                </div></li>
                                <?php } ?>
                                <?php if($needVideo){ ?>
                                <li class="showable-image"><div class="popup-img">
                                    <?= $product->displayPlugins['vmvideo']['full'] ?>
                                </div></li>                               
                                <? } ?>
                            </ul>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        
                        



