jQuery(document).ready(function(){
   jQuery('.three-places-action .place-2 .krest').click( function() {
       jQuery('.three-places-action .place-2').fadeTo('fast',0.5);
       jQuery('.three-places-action .place-3').fadeTo('fast',1);
       jQuery('.three-places-action .place-2 .krest').hide();
       jQuery('.three-places-action .place-3 .krest').show();
       jQuery('.three-places-action .box-suprise,.three-places-action .span-suprise').click(function() {
        jQuery.facebox({ div: '#compl-3place' });
        jQuery('.popup .complect-carousel').jcarousel({visible: 3 , initCallback: function() {
            var cnv=null; var ctx=null; var gradient=null;
            <?php foreach ($this->params->places[2] as $k => $product) { ?>
                                    cnv=jQuery('.popup #complCanvasp2_<?= $k ?>');
                                    ctx=cnv[0].getContext("2d");
                                    gradient=ctx.createLinearGradient(0,0,0,15);
                                    gradient.addColorStop(0.0, '#ff0000');
                                    gradient.addColorStop(0.9, '#9A0027');
                                    ctx.fillStyle = gradient;
                                    ctx.font = "bold 17px Microsoft YaHei";
                                    ctx.fillText("<?= $this->getNewPrice() ?> грн.", 0, 15);
            <?php } ?> 
            jQuery('.popup li').click(function(e){
                var $e=jQuery(e.target).closest('li');
                jQuery('.three-places-action .place-2 .img-wrapper img').attr('src',$e.attr('image'));
                jQuery('.three-places-action .place-2 span.PricesalesPrice').html($e.attr('oldPrice')+' грн.');
                jQuery('.three-places-action .place-5 span.economy').html($e.attr('economy'));
                jQuery('.three-places-action .place-4 #second-product').val($e.attr('product_id'));
                jQuery('.three-places-action .place-4 .oldPrice span').html($e.attr('fullPrice'));
                jQuery('.place-2 .name').html($e.attr('product-name'));
                jQuery('.place-2 .name').attr('href',$e.attr('product-link'));
                jQuery('.three-places-action .place-3').fadeTo('fast',0.5);
                jQuery('.three-places-action .place-2').fadeTo('fast',1);
                jQuery('.three-places-action .place-3 .krest').hide();
                jQuery('.three-places-action .place-2 .krest').show();
                jQuery('.three-places-action .box-suprise,.three-places-action .span-suprise').unbind();
                jQuery.facebox.close();
                
            });                        
        }
      });
       });
   });
   jQuery('.three-places-action .place-3 .krest').click( function() {
       jQuery('.three-places-action .place-3').fadeTo('fast',0.5);
       jQuery('.three-places-action .place-2').fadeTo('fast',1);
       jQuery('.three-places-action .place-3 .krest').hide();
       jQuery('.three-places-action .place-2 .krest').show();
       jQuery('.three-places-action .box-suprise,.three-places-action .span-suprise').unbind();
   });
});
