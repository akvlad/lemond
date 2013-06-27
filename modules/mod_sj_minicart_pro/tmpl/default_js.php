<?php
/**
 * @package Sj MiniCart Pro
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */

defined('_JEXEC') or die; ?>

<script type="text/javascript">

 </script>
 <script type="text/javascript">   
	//<![CDATA[	
        var minicart={};
    
	minicart.$minicart = jQuery('#sj_minicart_pro_<?php echo $uniqued;?>');
		
			/*
			 * Set display jscrollpanel
			 */
			minicart.jscrollDisplay = function(){
				var $h0 =0;
				for(var $i=0; $i < jQuery('.mc-product',this.$minicart).length ;$i++){
					$h0 = $h0 + jQuery(jQuery('.mc-product',this.$minicart)[$i]).height();
				}
				var element = jQuery('.mc-list-inner',this.$minicart).jScrollPane({
					showArrows: true
					
				});
				var api = element.data('jsp');
				if( $h0 > jQuery('.mc-list',this.$minicart).height()){
					element;
				}else{
					api.destroy(); 
				}		
			}
			
					
			
			/*
			 *  Ajax url
			 */
			minicart.ajax_url = '<?php echo (string)JURI::getInstance(); ?>';
		
			/*
			 * Refresh
			 */ 
			minicart.productsRefresh = function(cart){
                            console.log('YEYEYEYE!!!!');
                            var $cart = cart ? jQuery(cart) : this.$minicart;
                            var $this=minicart; 
				jQuery.ajax({
					type: 'POST',
					url: $this.ajax_url,
					data: {
						minicart_ajax:1,
						option: 'com_virtuemart',
						minicart_task: 'refresh',
						minicart_modid:'<?php echo $module->id; ?>',
						view: 'cart',
					},	
					success: function(list){
						console.log('GOGOGOG!!!');
                                                var $mpEmpty = $cart.find('.mc-product-zero');					
						/*jQuery('.mc-product-wrap',$cart).html($.trim(list.list_html));
						jQuery('.mc-totalprice ,.mc-totalprice-footer',$cart).html(list.billTotal);*/
						jQuery('.mc-totalproduct',$cart).html(list);
						$this.deleteProduct($this);
						if(list.length>1){
							$cart.find('.mc-status').html('<?php echo JText::_('ITEMS') ?>');	
						}else{
							$cart.find('.mc-status').html('<?php echo JText::_('ITEM') ?>');	
						}
						
						if(list.length>0){
							$cart.find('.mc-empty').hide();
							$cart.find('.mc-content-inner').show();
							$cart.find('.mc-totalprice').show();
							$cart.find('.mc-checkout-top').show();
					 		$mpEmpty.remove();
						}else{
							$cart.find('.mc-totalprice').hide()	;
							$cart.find('.mc-content-inner').hide();
							$cart.find('.mc-empty').show();
							$cart.find('.mc-checkout-top').hide();
						}
						$this.jscrollDisplay(); 
					},
                                        error: function( jqXHR, textStatus, errorThrown ){
                                            console.log(textStatus);
                                        },
					dataType: 'text'
				});
				return;
			};
                        
			/*
			 * Delete product
			 */
			minicart.deleteProduct = function(cart){	
			var product_delete= jQuery('.mc-product', cart.$minicart);
				product_delete.each(function(){
					var $this = jQuery(this);
					var product_id = jQuery('.product-id', $this).text();
					jQuery('.mc-remove', $this).click(function(){
						$this.addClass('mc-product-zero');	
						jQuery.ajax({
							type: 'POST',
							url : $this.ajax_url,
							data: {
								minicart_ajax:1,
								tmpl: 'component',
								option: 'com_virtuemart',
								view: 'cart',
								minicart_task: 'delete',
								cart_virtuemart_product_id: product_id // important
							},
							success: function($json){
								console.log($json);
								if($json.status && $json.status==1){
									productsRefresh();
								}
							},
							dataType: 'json'
						});
						});
					});
			};
                        minicart.init=function ($) { var $this=this;
                        /*
			 * MouseOver - MouseOut
			 */
			jQuery('.mc-wrap' ,this.$minicart).hover(function(){
				var $this = jQuery(this);
				if ($this.data('timeout')){
					clearTimeout($this.data('timeout'));
				}
				if($this.hasClass('over')){
					return ;
				}
				$this.addClass('over');
				jQuery('.mc-content', $this).slideDown(250);
				$this.jscrollDisplay(); 
			}, function(){
				var $this = jQuery(this);
				var timeout = setTimeout(function(){            
					jQuery('.mc-content', $this).not(':animated').slideUp(250);
					$this.removeClass('over');
	        	 }, 250);
				$this.data('timeout', timeout);
			});
			
			/*
			 * Event Addtocart Button - no load page
			 */ console.log('YOYOYO!!!');
                        var ddd=jQuery('input[name="addtocart"], span.addtocart-button-inactive');
			jQuery('input[name="addtocart"], span.addtocart-button-inactive').bind('click',function(){
                                        
					if ($this.$minicart.data('timeout')){
							clearTimeout($this.$minicart.data('timeout'));
						}
					var timeout = setTimeout(function(){
						$this.productsRefresh();
					},1000);
					$this.$minicart.data('timeout',timeout);
			});
		
			/*
			 *  Set coupon
			 */
			jQuery('.coupon-button-add',this.$minicart).click(function(){
					jQuery('.preloader',this.$minicart).show();
					jQuery('.coupon-message',this.$minicart).hide(); 
					jQuery('.coupon-input',this.$minicart).hide();
					jQuery('.coupon-title',this.$minicart).hide();	
					jQuery.ajax({
						type: 'POST',
						url : $this.ajax_url,
						data: {					
							minicart_ajax: 1,
							option: 'com_virtuemart',
							view:'cart',
							minicart_task: 'setcoupon',
							coupon_code: jQuery('.coupon-code', this.$minicart).val(),
							tmpl: 'component'					
						},
						success: function($json){
							console.log($json);
								jQuery('.preloader',this.$minicart).hide();
							if($json.status && $json.status==1)	{
								jQuery('.coupon-message',this.$minicart).hide();
								jQuery('.coupon-input',this.$minicart).hide();					
								jQuery('.coupon-label',this.$minicart).show();
								jQuery('.coupon-title',this.$minicart).show();
								jQuery('.coupon-text',this.$minicart).html($json.message);
								 productsRefresh(); 
							}else{
								jQuery('.coupon-title',this.$minicart).show();
								jQuery('.coupon-input',this.$minicart).show();
								jQuery('.coupon-message',this.$minicart).show();
							}
																	 
						},
						dataType: 'json'
					});
			});
		
			/*
			 * Close coupon
			 */
			jQuery('.coupon-close',this.$minicart).click(function(){			
				jQuery('.preloader',this.$minicart).show();
				jQuery('.coupon-label',this.$minicart).hide();
				jQuery('.coupon-title',this.$minicart).hide();	
				jQuery.ajax({
					type: 'POST',
					url : $this.ajax_url,
					data: {					
						minicart_ajax: 1,
						view:'cart',
						option: 'com_virtuemart',
						minicart_task: 'setcoupon',
						coupon_code: null,
						tmpl: 'component'	
					},
					success: function($json){							
						jQuery('.preloader',this.$minicart).hide();
						jQuery('.coupon-title',this.$minicart).show();
						jQuery('.coupon-input',this.$minicart).show();					
						jQuery('.coupon-code', this.$minicart).val('Enter your Coupon code');
					 	productsRefresh();				 
					},
					dataType: 'json'
				});
				
			});

			/*
			 * Update Products
			 */ 
			jQuery('.mc-update-btn',this.$minicart).click(function(){
				var product_update = jQuery('.mc-product', this.$minicart), array_id = [], array_qty = [];
				product_update.each(function(){
					var $this = jQuery(this);
					var product_id = jQuery('.product-id', $this).text();
					var	qty = jQuery( $this.find('.mc-quantity')).val();
						array_id.push(product_id);
						array_qty.push(qty);
						if(qty==0){
							$this.addClass('mc-product-zero');
						}
				});
				
				jQuery.ajax({
					type: 'POST',
					url : $this.ajax_url,
					data: {
						minicart_ajax:1,
						tmpl: 'component',
						option: 'com_virtuemart',
						view: 'cart',
						minicart_task: 'update',
						cart_virtuemart_product_id: array_id, 
						quantity: array_qty 
					},
					success: function($json){
						if($json.status && $json.status==1){
							productsRefresh();
						}
					},
					dataType: 'json'
				});
			});
                        this.deleteProduct(this);
			};
			

        jQuery(document).ready(function(){minicart.init($)});
	//]]>
	</script>