<?php echo JHTML::_('behavior.calendar'); ?>

<div id='children-block'>
<h3>Пару строк о счастливом обладателе товара</h3>
<div id='etnries'>
<!--SINGLE ENTRY BEGIN-->
<?php $i=$single_entry ? '$i' : 1; foreach($children as $child) { ?>
<?php if(!$single_entry) { ?>
<script  type="text/javascript">
window.addEvent('domready', function() {Calendar.setup({
        inputField     :    "birthdate<?= $i ?>",    // id of the input field
        ifFormat       :    "%Y-%m-%d",     // format of the input field
        button         :    "birthdate<?= $i ?>",    // trigger for the calendar (button ID)
        align          :    "Bl",           // alignment (defaults to "Bl" = Bottom Left, 
// "Tl" = Top Left, "Br" = Bottom Right, "Bl" = Botton Left)
        singleClick    :    true
    });});
</script>
<?php } ?>
<table>
    
    <tr>
        <td>Имя ребенка</td>
        <td>
            <input type="text" class="child-name-field" name="children[<?= $i ?>][name]" value="<?= $child['name'] ?>">
        </td>
    </tr>
    <tr>
        <td>Дата рождения ребенка</td>
        <td> <input name="children[<?= $i ?>][birthdate]" id="birthdate<?=$i?>" type="text" value='<?=$child['birthdate']?>' /> 
            
        </td>
    </tr>
    <tr>
        <td>Пол ребенка</td>
        <td>
            
           
            <?php
                    echo JHTML::_("select.genericlist",                            
                            array(array('val'=>'m','text'=>'Мальчик'),
                                array('val'=>'f','text'=>'Девочка')), 
                            'children['.$i.'][gender]',$attribs=null,
                            $value='val', $text='text',
                            $selected=$child['gender']); 
             ?><input type="button" name="children[moreone]" class='morechild' value='Еще ребенок'>
    </tr>
</table>
<?php if(!$single_entry) ++$i; } ?>
<!--SINGLE ENTRY END-->
</div>
</div>
