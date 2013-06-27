jQuery(document).ready(function(){
   jQuery('.three-places-action .krest').click( function() {
       jQuery('.three-places-action .place-2').fadeTo('fast',0.5);
       jQuery('.three-places-action .place-3').fadeTo('fast',1);
       jQuery('.three-places-action .box-suprise').click(function() {
        jQuery.facebox({ div: '#compl-3place' });
        jQuery('.popup .complect-carousel').jcarousel({visible: 3 , initCallback: function() {
            var cnv=null; var ctx=null; var gradient=null;
            <?php foreach ($this->params->places[2]->products as $k => $product) { ?>
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
                var $e=jQuery(e.target);
            jQuery('.three-places-action .place-2 .img-wrapper img').attr('src',$e.attr('image'));
            jQuery('.three-places-action .place-2 span.PricesalesPrice').html($e.attr('oldPrice'));
            jQuery('.three-places-action .place-5 span.economy').html($e.attr('economy'));
            jQuery('.three-places-action .place-4 .oldPrice span').html($e.attr('fullPrice'));
                
            });                        
        }
      });
       });
   });
           
});
