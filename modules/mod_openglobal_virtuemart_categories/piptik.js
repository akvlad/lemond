
function processPiptik(cat,subcat){
    if(subcat.length==0) return;
    var li_left=jQuery(cat).position().left;
    var li_abs_left=jQuery(cat).offset().left;
    var sub_abs_left=jQuery(subcat).offset().left
    var li_width = jQuery(cat).outerWidth();
    var sub_left=parseInt(subcat.css('margin-left'));
    var sub_width=jQuery(subcat).outerWidth();
    if(sub_left+sub_width - 15 < li_left + li_width * 0.5 )
        {
            sub_left= li_left + li_width * 0.5 - sub_width + 15;
            //sub.css('margin-left',sub_left+'px');
        }
    var piptik=subcat.find('.piptik');
    piptik.css('left',
        Math.max( li_left - sub_left + li_width * 0.5 , 15)+'px' );
}

jQuery(document).ready(function (){
    var cats=jQuery('.virtuemartcategories .menu li');
    
    jQuery.each(cats,function(index, value){
       var selector1='.virtuemartcategories .menu li#'+jQuery(value).attr('id');
       var selector2=jQuery(value).attr('id');
       selector2='.virtuemartcategories div#'+selector2.replace('li','subcat');
       
       processPiptik(jQuery(selector1),jQuery(selector2)) 
    });
})