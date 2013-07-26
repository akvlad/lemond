if(typeof Virtuemart === "undefined")
	{
		var Virtuemart = {
			setproducttype : function (form, id) {
				form.view = null;
				var $ = jQuery, datas = form.serialize();
				var prices = form.parents(".productdetails").find(".product-price");
				if (0 == prices.length) {
					prices = $("#productPrice" + id);
				}
				datas = datas.replace("&view=cart", "");
				prices.fadeTo("fast", 0.75);
				$.getJSON(window.vmSiteurl + 'index.php?option=com_virtuemart&nosef=1&view=productdetails&task=recalculate&virtuemart_product_id='+id+'&format=json' + window.vmLang, encodeURIComponent(datas),
					function (datas, textStatus) {
						prices.fadeTo("fast", 1);
						// refresh price
						for (var key in datas) {
							var value = datas[key];
							if (value!=0) prices.find("span.Price"+key).show().html(value);
							else prices.find(".Price"+key).html(0).hide();
						}
					});
				return false; // prevent reload
			},
			productUpdate : function(mod) {

				var $ = jQuery ;
				$.ajaxSetup({ cache: false })
				$.getJSON(window.vmSiteurl+"index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json"+window.vmLang,
					function(datas, textStatus) {
						if (datas.totalProduct >0) {
							mod.find(".vm_cart_products").html("");
							$.each(datas.products, function(key, val) {
								$("#hiddencontainer .container").clone().appendTo(".vmCartModule .vm_cart_products");
								$.each(val, function(key, val) {
									if ($("#hiddencontainer .container ."+key)) mod.find(".vm_cart_products ."+key+":last").html(val) ;
								});
							});
							mod.find(".total").html(datas.billTotal);
							mod.find(".show_cart").html(datas.cart_show);
						}
						mod.find(".total_products").html(datas.totalProductTxt);
					}
				);
			},
			sendtocart : function (form,target){
                                if(!target) target=null;

				if (Virtuemart.addtocart_popup ==1) {
					Virtuemart.cartEffect(form,target) ;
				} else {
					form.append('<input type="hidden" name="task" value="add" />');
					form.submit();
				}
			},
                        delfromcart : function (form,target){
                                if(!target) target=null;

				if (Virtuemart.addtocart_popup ==1) {
					Virtuemart.cartDelEffect(form,target) ;
				} else {
					form.append('<input type="hidden" name="task" value="delete" />');
					form.submit();
				}
			},
                        updatecart : function (form,target){
                                if(!target) target=null;

				if (Virtuemart.addtocart_popup ==1) {
					Virtuemart.cartUpdateEffect(jQuery(target).closest('form'),target) ;
				} else {
					form.append('<input type="hidden" name="task" value="delete" />');
					form.submit();
				}
			},
                        cartDelEffect : function(form) {

					var $ = jQuery ;
					$.ajaxSetup({ cache: false })
					var datas = form.serialize();
                                        var targetForm=form;
					$.getJSON(vmSiteurl+'index.php?option=com_virtuemart&nosef=1&view=cart&task=delJS&format=json'+vmLang,encodeURIComponent(datas),
					function(datas, textStatus) {
						if(datas.stat ==1){
							//var value = form.find('.quantity-input').val() ;
							var txt = form.find(".pname").val()+' '+vmCartText;
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: datas.msg }, 'my-groovy-style');
						} else if(datas.stat ==2){
							var value = form.find('.quantity-input').val() ;
							var txt = form.find(".pname").val();
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: datas.msg}, 'my-groovy-style');
						} else {
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: "<H4>"+vmCartError+"</H4>"+datas.msg }, 'my-groovy-style');
						}
						if ($(".vmCartModule")[0]) {
							Virtuemart.productUpdate($(".vmCartModule"));
						}
                                                var addForm=jQuery(targetForm).find('.addtocart-button');
                                                addForm.show();
                                                targetForm.find('.addtocart-button-inactive').hide();
                                                var cart = jQuery(this);
                                                jQuery("#facebox .update-product").click(function (e) { 
                                                    Virtuemart.delfromcart(jQuery(e.target).closest('form'),e.target);

                                                    return false;
                                                });
                                                var intervalID=0;
                                               jQuery("#facebox input[type=text]").focusin('change',function (e) { 
                                                    var $e=jQuery(e.target);
                                                    intervalID=setInterval(function(){
                                                            var price=parseFloat($e.attr('price'))*parseInt($e.val());
                                                            if(isNaN(price)) price=0;
                                                            $e.closest('.prod-item').find('.PricesalesPrice').html(
                                                            price+' грн.'
                                                        )},500);
                                                    
                                                    
                                                    return false;
                                                });
                                                jQuery("#facebox input[type=text]").focusout(function (e) { 
                                                    clearInterval(intervalID);
                                                    Virtuemart.updatecart(cart,e.target);
                                                    return false;
                                                });
                                                jQuery("#facebox .buy-it").click(function(e){
                                                        var href=jQuery("#facebox .buy-it a").attr('href');
                                                        window.setTimeout(function(){
                                                            window.location=href; },500);
                                                        return false;
                                                });
                                                minicart.productsRefresh();
                                                jQuery('form.product .addtocart-button-inactive').hide();
                                                jQuery('form.product .addtocart-button').show();
                                                jQuery('.popup form input[name=cart_virtuemart_product_id]').each(function(ind, el){
                                                    $e=jQuery(el);
                                                    jQuery('form.product-'+$e.val()+' .addtocart-button-inactive').show();
                                                    jQuery('form.product-'+$e.val()+' .addtocart-button').hide();
                                                })
                                            
					});
					$.ajaxSetup({ cache: true });
			},
                        cartUpdateEffect : function(form) {

					var $ = jQuery ;
					$.ajaxSetup({ cache: false })
					var datas = form.serialize();
                                        var targetForm=form;
					$.getJSON(vmSiteurl+'index.php?option=com_virtuemart&nosef=1&view=cart&task=updateJS&format=json'+vmLang,encodeURIComponent(datas),
					function(datas, textStatus) {
						/*if(datas.stat ==1){
							//var value = form.find('.quantity-input').val() ;
							var txt = form.find(".pname").val()+' '+vmCartText;
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: datas.msg }, 'my-groovy-style');
						} else if(datas.stat ==2){
							var value = form.find('.quantity-input').val() ;
							var txt = form.find(".pname").val();
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: datas.msg}, 'my-groovy-style');
						} else {
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: "<H4>"+vmCartError+"</H4>"+datas.msg }, 'my-groovy-style');
						}
						if ($(".vmCartModule")[0]) {
							Virtuemart.productUpdate($(".vmCartModule"));
						}
                                                var addForm=jQuery(targetForm).find('.addtocart-button-inactive');
                                                addForm.show();
                                                targetForm.find('.addtocart-button').hide(); 
                                                var cart = jQuery(this);
                                                jQuery("#facebox input[type=text]").click(function (e) { 
                                                    Virtuemart.updatecart(cart,e.target);
                                                    /*if ($minicart.data('timeout')){
                                                            clearTimeout($minicart.data('timeout'));
                                                        }
                                                    var timeout = setTimeout(function(){
                                                        
                                                    },1000);
                                                    $minicart.data('timeout',timeout);
                                                    return false;
                                                });*/
                                                minicart.productsRefresh();
					});
					$.ajaxSetup({ cache: true });
			},
			cartEffect : function(form) {

					var $ = jQuery ;
					$.ajaxSetup({ cache: false })
					var datas = form.serialize();
                                        var targetForm=form;
					$.getJSON(vmSiteurl+'index.php?option=com_virtuemart&nosef=1&view=cart&task=addJS&format=json'+vmLang,encodeURIComponent(datas),
					function(datas, textStatus) {
						if(datas.stat ==1){
							//var value = form.find('.quantity-input').val() ;
							var txt = form.find(".pname").val()+' '+vmCartText;
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: datas.msg }, 'my-groovy-style');
						} else if(datas.stat ==2){
							var value = form.find('.quantity-input').val() ;
							var txt = form.find(".pname").val();
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: datas.msg}, 'my-groovy-style');
						} else {
													$.facebox.settings.closeImage = closeImage;
													$.facebox.settings.loadingImage = loadingImage;
													$.facebox.settings.faceboxHtml = faceboxHtml;
							$.facebox({ text: "<H4>"+vmCartError+"</H4>"+datas.msg }, 'my-groovy-style');
						}
						if ($(".vmCartModule")[0]) {
							Virtuemart.productUpdate($(".vmCartModule"));
						}
                                                var addForm=jQuery(targetForm).find('.addtocart-button-inactive');
                                                addForm.show();
                                                targetForm.find('.addtocart-button').hide();   
                                                var cart = jQuery(this);
                                                var intervalID=0;
                                                jQuery("#facebox input[type=text]").focusin('change',function (e) { 
                                                    var $e=jQuery(e.target);
                                                    intervalID=setInterval(function(){
                                                            var price=parseFloat($e.attr('price'))*parseInt($e.val());
                                                            if(isNaN(price)) price=0;
                                                            $e.closest('.prod-item').find('.PricesalesPrice').html(
                                                            price+' грн.'
                                                        )},500);
                                                    
                                                    
                                                    return false;
                                                });
                                                jQuery("#facebox input[type=text]").focusout(function (e) { 
                                                    clearInterval(intervalID);
                                                    Virtuemart.updatecart(cart,e.target);
                                                    /*if ($minicart.data('timeout')){
                                                            clearTimeout($minicart.data('timeout'));
                                                        }
                                                    var timeout = setTimeout(function(){
                                                        productsRefresh();
                                                    },1000);
                                                    $minicart.data('timeout',timeout);*/
                                                    return false;
                                                });

                                                jQuery("#facebox .update-product").click(function (e) { 
                                                    Virtuemart.delfromcart(jQuery(e.target).closest('form'),e.target);

                                                    return false;
                                                });
                                                jQuery("#facebox .buy-it").click(function(e){
                                                        var href=jQuery("#facebox .buy-it a").attr('href');
                                                        window.setTimeout(function(){
                                                            window.location=href; },500);
                                                        return false;
                                                });
                                            
                                                //productsRefresh();
					});
					$.ajaxSetup({ cache: true });
			},
			product : function(carts) {
				carts.each(function(){
					var cart = jQuery(this),
					step=cart.find('input[name="quantity"]'),
					addtocart = cart.find('input.addtocart-button'),
                                        delfromcart = cart.find('.addtocart-button-inactive'),
					plus   = cart.find('.quantity-plus'),
					minus  = cart.find('.quantity-minus'),
					select = cart.find('select'),
					radio = cart.find('input:radio'),
					virtuemart_product_id = cart.find('input[name="virtuemart_product_id[]"]').val(),
					quantity = cart.find('.quantity-input');

                    var Ste = parseInt(step.val());
                    //Fallback for layouts lower than 2.0.18b
                    if(isNaN(Ste)){
                        Ste = 1;
                    }
                                        delfromcart.click(function(e){
                                            Virtuemart.delfromcart(cart,e.target);
						return false;
                                        });
					addtocart.click(function(e) { 
						Virtuemart.sendtocart(cart,e.target);
						return false;
					});
					plus.click(function() {
						var Qtt = parseInt(quantity.val());
						if (!isNaN(Qtt)) {
							quantity.val(Qtt + Ste);
						Virtuemart.setproducttype(cart,virtuemart_product_id);
						}
						
					});
					minus.click(function() {
						var Qtt = parseInt(quantity.val());
						if (!isNaN(Qtt) && Qtt>Ste) {
							quantity.val(Qtt - Ste);
						} else quantity.val(Ste);
						Virtuemart.setproducttype(cart,virtuemart_product_id);
					});
					select.change(function() {
						Virtuemart.setproducttype(cart,virtuemart_product_id);
					});
					radio.change(function() {
						Virtuemart.setproducttype(cart,virtuemart_product_id);
					});
					quantity.keyup(function() {
						Virtuemart.setproducttype(cart,virtuemart_product_id);
					});
				});

			}
		};
		jQuery.noConflict();
		jQuery(document).ready(function($) {

			Virtuemart.product($("form.product"));

			$("form.js-recalculate").each(function(){
				if ($(this).find(".product-fields").length) {
					var id= $(this).find('input[name="virtuemart_product_id[]"]').val();
					Virtuemart.setproducttype($(this),id);

				}
			});
		});
	}
