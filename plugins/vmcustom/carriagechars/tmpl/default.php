<?php $vol_names=array("country"=>"Страна производитель",
        "age"=>"Возраст","type"=>"Тип","gender"=>"Пол ребенка",
        "brend"=>"Бренд");?>

<div class="search_vol vol-price">
	<div class="vol_header open">
	<span class="imgs"><span>Цена</span></span> <input type="submit" name="submit" value="Применить"/> 
	</div>
	<div class="vol_contents">
	<style type="text/css" media="screen">
	 .layout-slider {width: 50%; padding: 20px 0; }
	</style>
	<div class="layout-slider" style="width: 90%;margin: 0 auto;">
		<?php $value=(JRequest::getVar('price',null)!==null) ? 
						JRequest::getVar('price',null) : 
						$priceLimits->minPrice.';'.$priceLimits->maxPrice;?>
		<input id="slider-price" type="text" name="price" value="<?php echo $value ?>"/>
	</div>
	</div>
</div>
<?php $i=0; foreach($search_vols as $k=>$v){
		$posts=JRequest::getVar($k,array()); ?>
<div class="search_vol" id="search_vol<?=$i?>">
	<div class="vol_header" id="vol_header<?=$i?>">
	<span class="imgs"><span><?php echo $vol_names[$k];?></span></span>
	</div>
	<div class="vol_contents" id="vol_contents<?=$i?>" style="display: none;">
		<?php foreach ($v as $_v) { 
			$checked=in_array($_v->id,$posts) ? 'checked="true"' : '';?>
		<label><input type="checkbox" name="<?php echo $k;?>[]" value="<?php echo $_v->id; ?>" 
			<?php echo $checked;?>/> <span></span> </label> <span> <?php echo $_v->content; ?></span><br> 
		<?php } ?>
	</div>
</div>
<?php ++$i;} ?>
<div class="search_vol" id="search_vol<?=$i?>">
	<div class="vol_header" id="vol_header<?=$i?>"><span class="imgs"><span>Аксессуары</span></span></div>
	<div class="vol_contents" id="vol_contents<?=$i?>" style="display: none">
		<a href="#">Аксессуар</a>
	</div>
</div>

<input type="hidden" name="custom_parent_id" value="<?php echo $virtuemart_custom_id?>"/>