<script type="text/javascript">
function BuyOnclick(){    

}
</script>
<div id="vm_popup_wrapper">
	<div id="div-h2-popup-cart">	
		<h2>Товары в Вашей корзине</h2>
	</div>
    <div id="products_wrapper">
		
			<?php
				$i=0;
				$this->productModel->addImages($this->cart->products);
				foreach($this->cart->products as $key=>$product)
				{
					$i++;?>
					<div class="prod-item <?php if(($i % 2)==0) echo "blue-fon" ?>">
						<form action="/component/virtuemart/" method="post" class="inline">
						<input type="hidden" name="option" value="com_virtuemart">
							<!--<input type="text" title="Обновить количество в корзине" class="inputbox" size="3" maxlength="4" name="quantity" value="1" /> -->
							
						<input type="hidden" name="view" value="cart">
						<input type="hidden" name="task" value="updateJS">
						<input type="hidden" name="cart_virtuemart_product_id" value="<?php echo $key;?>">
									<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $key;?>">
						<?php //<input type="submit" class="vmicon vm2-add_quantity_cart" name="update" title="Обновить количество в корзине" align="middle" value=" "> ?>
		
						<input type="button" class="update-product" onclick="updateClick(event)" name="update" value=""  />  <? //<img src="../templates/lemond/images/cart-out.png"></a> ?>
						<div class="div-prod-image">
							<div class="div-cell">
							<?php 
								echo $product->images[0]->displayMediaThumb('class="prod-image"',false); //выводим картинку с классом
							?>
							</div>
						</div>
						<div class="div-prod-name">
							<?php $dispatcher = JDispatcher::getInstance();
                                                                $product_name=$product->product_name;
                                                                $dispatcher->trigger('plgVmProductName',array(&$product,&$product_name));
                                                                echo $product_name;    //имя товара?>
						</div>
						<div class="div-prod-count">
							<span>
                                                            
                                                            <input type="text"
                                                                   title="Обновить количество в корзине" class="quantity-input js-recalculate" size="3" maxlength="4" name="quantity" 
                                                                   value="<?php echo $product->quantity;    //количество?>" 
                                                                   price="<?php echo round($this->cart->pricesUnformatted[$key]['salesPrice'],0) ?>" />
                                                            </span>
							<span class="sht">шт.</span>
						</div>
						<div class="div-prod-price">
							<?php echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->pricesUnformatted[$key],'', FALSE, FALSE, $product->quantity); //цена
							//var_dump($product);?>
						</div>
                                           </form>
					</div>
				<?php
				}
			?>
		
    </div>
	<div id="div-buttons-popup-cart">
		<div class="lets-go"><a  href="#" onclick="jQuery.facebox.close(); return false;"><img src="<?php $this->baseurl; ?>/templates/lemond/images/go-on-in-cart.png"></a></div>
                <div class="buy-it"> <a  href="<?= JRoute::_("index.php?option=com_virtuemart&view=cart") ?>"><img src="<?php $this->baseurl; ?>/templates/lemond/images/lets-buy-it-in-cart.png"></a></div>
	</div>
</div>
