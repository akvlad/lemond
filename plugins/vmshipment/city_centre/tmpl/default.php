<script>
    var city_centre_ship_handler={
        cities: { <?= $this->getCities(); ?> },
        cityApply: function(city){
            var $el=jQuery('#meetPlace')
            $el.empty();
            jQuery.each(this.cities[city], function(key, value) {
                $el.append(jQuery("<option></option>")
                   .attr("value", key).text(key));
              });
        },
        timeApply: function(city,adress){
            jQuery("#time-start-span").html(this.cities[city][adress]["time_start"]);
            jQuery("#time-end-span").html(this.cities[city][adress]["time_end"]);
        }
    };
    jQuery(document).ready(function(){
        jQuery('#ship_citiy').change(function(){
            city_centre_ship_handler.cityApply(jQuery('#ship_citiy').val());
            city_centre_ship_handler.timeApply(jQuery('#ship_citiy').val(),jQuery('#meetPlace').val());
        });
        jQuery('#meetPlace').change(function(){
            city_centre_ship_handler.timeApply(jQuery('#ship_citiy').val(),jQuery('#meetPlace').val());
        });
        city_centre_ship_handler.cityApply(jQuery('#ship_citiy').val());
        city_centre_ship_handler.timeApply(jQuery('#ship_citiy').val(),jQuery('#meetPlace').val());
    });
</script>

<input type="radio" name="<?= $pluginmethod_id?>" id="<?= $this->_psType ?>_id_<?= $plugin->$pluginmethod_id ?>"   value="<?= $plugin->$pluginmethod_id ?>"
       <?= $checked ?>>
<label for="<?= $this->_psType ?>_id_<?= $plugin->$pluginmethod_id ?>">
    <span class="<?= $this->_type ?>"> <?= $plugin->$pluginName ?> </span>
</label>
<p>место встречи: <?php echo JHTML::_("select.genericlist",                            
                            array(), 
                            'meetPlace',$attribs=null,
                            $value='val', $text='text',
                            $selected=$child['gender']);  ?>
<p>временной интервал: С <span id="time-start-span"></span> По <span id="time-end-span"></span>


