<?= $product->images[0]->displayMediaThumb('class="img-place1"',false); ?>
<p class='product-name'><?= $product->product_name ?>
<p class='product-price'> <?= $currency->createPriceDiv('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices); ?>
