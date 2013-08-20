<?php $needVideo1=isset($product1->displayPlugins['vmvideo']);
$needVideo2=isset($product2->displayPlugins['vmvideo']);
$needVideo=$needVideo1 || $needVideo2;
$maxImages=2;
if($needVideo) $maxImages=1;
$totalImages=count($product1->images)+count($product2->images);
$showImages=min($totalImages,$maxImages);
if($totalImages>1 || $needVideo ){ $i=1; ?> 
<div class="showable-medias action">
    <?php for($i=0;$i<count($product1->images) && $showImages > 0; ++$i) { ?>
	 <?php if($needVideo1){ ?>
     <div class="showable-image">
        <?= $product1->displayPlugins['vmvideo']['thumb'] ?>
    </div>                               
    <? } elseif ($needVideo2){ ?>
     <div class="showable-image">
        <?= $product2->displayPlugins['vmvideo']['thumb'] ?>
    </div>
    <?php } ?>
    <div class="showable-image">
        <a rel="facebox" href="#images<?= $product->virtuemart_product_id ?>" >
        <?php echo $product1->images[$i]->displayMediaThumb('', false);	?>
        </a>
    </div>
    <?php --$showImages; } ?>
    <?php for($i=0;$i<count($product2->images) && $showImages > 0; ++$i) { ?>
    <div class="showable-image">
        <a rel="facebox" href="#images<?= $product->virtuemart_product_id ?>" >
        <?php echo $product2->images[$i]->displayMediaThumb('', false);	?>
        </a>
    </div>
    <?php --$showImages; } ?>    
   
</div>
<?php } ?>

<div class="spacer">
    <div class="prod1-img">
        <a title="<?php echo $product1->link ?>" rel="vm-additional-images" href="<?php echo $product1->link; ?>">
                   <div class="tbl-div"> <?php echo $product1->images[0]->displayMediaThumb('class="browseProductImage"', false);	?></div>
             </a>
    </div>
    <div class="prod2-img">
        <a title="<?php echo $product2->link ?>" rel="vm-additional-images" href="<?php echo $product2->link; ?>">
                   <div class="tbl-div">  <?php echo $product2->images[0]->displayMediaThumb('class="browseProductImage"', false);	?></div>
             </a>
    </div>
<div class="prod-name">

    <span class="prod-name-span">
           <?php    $prod_name1=shopFunctionsF::limitStringByWord($product1->product_name, 15, '...');
                    $prod_name2=shopFunctionsF::limitStringByWord($product2->product_name, 15, '...');
                echo JHTML::link ($product1->link, $prod_name1); ?> + <?php echo JHTML::link ($product2->link, $prod_name2); ?>
           <? /* useful thing: shopFunctionsF::limitStringByWord($product->product_s_desc, 40, '...') */ ?>
    </span>
    
</div>
<div class="product-price marginbottom12" id="productPrice<?php echo $product->virtuemart_product_id ?>">
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
                    <span class="PricepriceWithoutTax action"><?= $this->params->old_price /*round($product1->prices['salesPrice']+$product2->prices['salesPrice'],0)*/ ?> грн.</span>
</div>

<div class="clear"></div>
<?= $product->buyForm ?>
</div>


<div class="carousel-wrapper" style="display:none">
    <div id="images<?= $product->virtuemart_product_id ?>">
        <div class="popup-imgs">
            <p class="popup-thumbs">

      <?php $i=1; foreach ($product1->images as $image) { ?>
              <?= $image->displayMediaThumb('onclick="imageClick(event,'.$i.')"', false); ?>
        <?php ++$i; } ?> 
      <?php foreach ($product2->images as $image) { ?>
              <?= $image->displayMediaThumb('onclick="imageClick(event,'.$i.')"', false); ?>
        <?php ++$i; } ?> 
            
        <?php if($needVideo1){  ?> 
            <img src="/images/video_icon.jpg" onclick="imageClick(event,<?= $i ?>)" />
        <?php ++$i; } ?>
        <?php if($needVideo2){  ?> 
            <img src="/images/video_icon.jpg" onclick="imageClick(event,<?= $i ?>)" />
        <?php ++$i; } ?>
            </p>
    <ul  class="product-carousel jcarousel-skin-milkbox">
        <?php foreach ($product1->images as $image) {?>
        <li><div class="popup-img">
                    <?= $image->displayMediaFull('', false); ?>
        </div></li>
        <?php } ?>
        <?php foreach ($product2->images as $image) {?>
        <li><div class="popup-img">
                    <?= $image->displayMediaFull('', false); ?>
        </div></li>
        <?php } ?>
        <?php if($needVideo1){ ?>
        <li class="showable-image"><div class="popup-img">
            <?= $product1->displayPlugins['vmvideo']['full'] ?>
        </div></li>                               
        <? } ?>
        <?php if($needVideo2){ ?>
        <li class="showable-image"><div class="popup-img">
            <?= $product2->displayPlugins['vmvideo']['full'] ?>
        </div></li>                               
        <? } ?>
    </ul>
        </div>
    </div>
</div>
