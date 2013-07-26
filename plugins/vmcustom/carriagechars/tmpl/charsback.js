function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

jQuery.fn.manualChoose=function(params)
{
    $params=params;
    jQuery(this).click(function(){
        if(jQuery(this).is(':checked')){
            jQuery(params['manual-selector']).show();
            jQuery(params['manual-selector']).attr('name',params['name']);
            jQuery(params['list-selector']).hide();
            jQuery(params['list-selector']).attr('name',makeid());
        }
        else{
            jQuery(params['manual-selector']).hide();
            jQuery(params['manual-selector']).attr('name',makeid());
            jQuery(params['list-selector']).show();
            jQuery(params['list-selector']).attr('name',params['name']);
        }
    });
};
