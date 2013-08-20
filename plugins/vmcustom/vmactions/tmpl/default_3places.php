<div class="three-places-action">
	<h4>Комплект товаров по выгодной цене</h4>
    <div class="place-1">
        <?php //print('<xmp>'); var_dump($this->params->places[1]->product); die('</xmp>'); ?>
        <div class="img-wrapper">
        <?= $this->params->places[1]->images[0]->displayMediaThumb('class="img-place1"',false); ?>
        </div>
        <a href="<?= $this->params->places[1]->link ?>"><?= $this->params->places[1]->product_name ?> </a>
        <div class="price-wrapper">
            <div class="singlePrice"> 
                <?= $this->currency->createPriceDiv('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $this->params->places[1]->prices);?> 
            </div>
            <div class="cl"></div>
        </div>
    </div>
       		
    <div class="place-2">
    <div class="krest"><img src="<?php $this->baseurl ?>/templates/lemond/images/krest.png"></div> 
        <div class="img-wrapper">
        <?= $this->params->default_prod->images[0]->displayMediaThumb('class="img-place1"',false); ?>
        </div> 
        <a class='name' href="<?= $this->params->default_prod->link ?>"><?= $this->params->default_prod->product_name ?> </a>
        <div class="price-wrapper">    
            <div class="newPrice"> 
                 <canvas id="complCanvasp2" width="80" height="20">
                 </canvas>
                 <script type="text/javascript">
                    var cnv=document.getElementById('complCanvasp2');
                    var ctx=cnv.getContext("2d");
                    var gradient=ctx.createLinearGradient(0,0,0,15);
                    gradient.addColorStop(0.0, '#ff0000');
                    gradient.addColorStop(0.9, '#9A0027');
                    ctx.fillStyle = gradient;
                    ctx.font = "bold 17px Microsoft YaHei";
                    ctx.fillText("<?= $this->getNewPrice() ?> грн.", 0, 15);
                </script>
            </div>
            <div class="oldPrice"> 
                <?= $this->currency->createPriceDiv('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $this->params->default_prod->prices);?> 
            </div>
        </div>
     </div>
	<div class="place-3">
            <div class="krest" style='display: none;'><img src="<?php $this->baseurl ?>/templates/lemond/images/krest.png"></div> 
		<div class="box-suprise"><img src="<?php $this->baseurl ?>/templates/lemond/images/suprise.png"></div>
		<span class="span-suprise" href="#">Добавить товар<br>в комплект</span>
	</div>
	<div class="place-4">
                <div class='oldPrice'><span><?= round($this->params->places[1]->prices['salesPrice'] + 
                        $this->params->default_prod->prices['salesPrice']) ?></span> грн.</div>
                        <div class="newPrice"> 
                 <canvas id="complCanvasFull" width="80" height="20">
                 </canvas>
                 <script type="text/javascript">
                    var cnv=document.getElementById('complCanvasFull');
                    var ctx=cnv.getContext("2d");
                    var gradient=ctx.createLinearGradient(0,0,0,15);
                    gradient.addColorStop(0.0, '#ff0000');
                    gradient.addColorStop(0.9, '#9A0027');
                    ctx.fillStyle = gradient;
                    ctx.font = "bold 17px Microsoft YaHei";
                    ctx.fillText("<?= round($this->params->compl_prod->prices['salesPrice'],0) ?> грн.", 0, 15);
                </script>
                </div>
            
                <?= $productModel->getBuyForm(array($this->params->compl_prod)); ?>
		<!--<img src="<?php $this->baseurl ?>/templates/lemond/images/cart-in.png"> -->
	</div>
	<div class="place-5">
		<span >Экономия<br>на комплекте<br><span class='economy'><?= round($this->params->places[1]->prices['salesPrice'] + 
                        $this->params->default_prod->prices['salesPrice'] - 
                        $this->params->compl_prod->prices['salesPrice'],0)  ?></span> грн.</span>
	</div>
        
       <div id="compl-3place" style="display:none">
		<div class="div-ul">
            <ul class="complect-carousel  jcarousel-skin-lemond-horiz-slide" >
                <?php foreach($this->params->places[2] as $k=> $product){  ?>
                <li oldPrice='<?= round($product->prices['salesPrice'],0) ?>' 
                    economy='<?= round($this->params->places[1]->prices['salesPrice'] + 
                        $product->prices['salesPrice'] - 
                        $this->params->compl_prod->prices['salesPrice'],0)  ?>'
                    fullPrice='<?= round($this->params->places[1]->prices['salesPrice'] + 
                        $product->prices['salesPrice']) ?>'
                    image='<?= $product->images[0]->getThumbUrl() ?>'
                    product-name='<?= $product->product_name ?>'
                    product-link='<?= $product->link ?>'
                    product_id="<?= $product->virtuemart_product_id ?>"> 
                    <div class="relative-wrapper">
                    <div class="img-wrapper">    
                    <?= $product->images[0]->displayMediaThumb('class="slider-catalogue-imgs"', false); ?>
                    </div>
                    <span class='product-name'> <?= $product->product_name ?> </span>
                    <div class="price-wrapper">    
                        <span class="newPrice"> 
                             <canvas id="complCanvasp2_<?= $k ?>" width="80" height="20">
                             </canvas>
                             <script type="text/javascript">

                            </script>
							
                        </span>
						<span class="oldPrice"> 
							<?= $this->currency->createPriceDiv('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices);?> 
						</span>
                        
                    </div>
                    <?php // echo $productModel->getBuyForm(array($product)); ?>
                    </div>
                </li>
                <?php } ?>
            </ul>
		</div>
       </div>

</div>
