<script>
    var children_handler={
        i:<?= $this->getEntryNum()+1; ?>,
        additional_html: "<?= mysql_escape_string($this->getSingleEntry()); ?>",
        addEntry: function(){
            jQuery('.morechild').remove();
            var add_html=this.additional_html.replace(/\$i/g,this.i);
            jQuery('#etnries').html(function(i,old){
                return old+add_html;
            });
            
            Calendar.setup({
                inputField     :    "birthdate"+this.i,    // id of the input field
                ifFormat       :    "%Y-%m-%d",     // format of the input field
                button         :    "birthdate"+this.i,    // trigger for the calendar (button ID)
                align          :    "Bl",           // alignment (defaults to "Bl" = Bottom Left, 
        // "Tl" = Top Left, "Br" = Bottom Right, "Bl" = Botton Left)
                singleClick    :    true
            });
            jQuery('.morechild').unbind('click');
            jQuery('.morechild').bind('click',function(){
                children_handler.addEntry();
            });
            ++this.i;
        },
        init: function(){
            jQuery('.morechild').bind('click',function(){
                children_handler.addEntry();
            });
            jQuery('#children-block h3').click(function(e) {
                $e=jQuery(e.target).closest('#children-block');
                if($e.hasClass('children-block-closed')) $e.removeClass('children-block-closed');
                else $e.addClass('children-block-closed');
            });
        }
    };
    jQuery(document).ready(function(){
        children_handler.init();
    });
</script>
