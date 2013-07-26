<input type="radio" name="<?= $pluginmethod_id?>" id="<?= $this->_psType ?>_id_<?= $plugin->$pluginmethod_id ?>"   value="<?= $plugin->$pluginmethod_id ?>"
       <?= $checked ?>>
<label for="<?= $this->_psType ?>_id_<?= $plugin->$pluginmethod_id ?>">
    <span class="<?= $this->_type ?>"> <?= $plugin->$pluginName ?> </span>
</label>
<p>адрес <input type="text" name="doors_ship[adress]" value=""/>
<p>номер дома <input type="text" name="doors_ship[house]" value=""/>
<p>квартира <input type="text" name="doors_ship[apart]" value=""/>
<p>офис <input type="text" name="doors_ship[ofice]" value=""/>
<p>временной интервал С <input type="text" name="doors_ship[from]" value=""/> По <input type="text" name="doors_ship[to]" value=""/>
