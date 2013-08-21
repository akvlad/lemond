<?php defined('_JEXEC') or die('Restricted access'); 

/*$document =& JFactory::getDocument();
$save_js16="Joomla.submitbutton=function(pressbutton)
	{
	
    if (pressbutton == 'remove') 
		{
			var confm = confirm('".JText::_('CONFIRM_DELETE')."');
			if(confm == true)
			{
				Joomla.submitform( pressbutton );
				return;		
			}
			else
			{
				return 0;
			}
							
		}
		
     }
     ";
$save_js15="function submitbutton(pressbutton)
	{
	
    if (pressbutton == 'remove') 
		{
			var confm = confirm('".JText::_('CONFIRM_DELETE')."');
			if(confm == true)
			{
				Joomla.submitform( pressbutton );
				return;		
			}
			else
			{
				return 0;
			}
							
		}
		
     }
     ";     

      if(JVERSION >= '1.6.0')
        $document->addScriptDeclaration($save_js16);	
	  else
        $document->addScriptDeclaration($save_js15);	
*/

		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();	
		
		$cid	= JRequest::getVar( 'cid','' );
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">

<table>
	<tr>
		<td align="left">
		Search: 
		<input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
		<button type="submit">Go</button>
		</td>
	</tr>
</table>
	<table class="adminlist">
	<thead>
		<tr>
			
			<th width="20" >
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->alert_items ); ?>);" />
			</th>	
			<th width="10">
				<?php echo JText::_( 'ID' ); ?>
			</th>		
			<th align="left">
				<?php echo JText::_( 'ALERT_NAME' ); ?>
			</th>
			
			<th>
			
			<?php echo JText::_( 'PLUGINS' ); ?>
			</th>
			
			<th nowrap="nowrap" >
		    
		     <?php echo JText::_( 'PUBLISHED' ); ?>
		     
		    </th>
			
			<th nowrap="nowrap" >
		     
			 <?php echo JText::_( 'DEFOULT' ); ?>
		    
		    </th>
		    
			<th>
				<?php echo JText::_( 'SUBSCRIBERS' ); ?>
			</th>
			
			<th>
			
			<?php echo JText::_( 'UNSUBSCRIBERS' ); ?>
			</th>
			
			<th>
					<?php echo JText::_( 'NOTOPTED' ); ?>
			</th>
			
		</tr>
	</thead>
	<?php

    $k = 0;
	for ($i=0, $n=count( $this->alert_items ); $i < $n; $i++)	
	{
		$row = &$this->alert_items[$i]; 
		
		$published 	= JHTML::_('grid.published', $row, $i );
		$link 		= JRoute::_( 'index.php?option=com_jmailalerts&controller=alertype&task=edit&cid[]='. $row->id );
	?>
	<tr class="<?php echo "row$k"; ?>">
			
			<td align="center">
			
				<?php //echo $checked; ?>
			   <?php echo JHTML::_('grid.id', $i, $row->id ); ?>
	     	</td>
			
			<td>
				<?php echo $row->id; ?>
			</td>
			
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->alert_name; ?></a><br /><?php echo $row->description; ?>
			</td>
		    
		    <td> 
			   <?php
				 $model = $this->getModel();
			     $plugins_name =$model->getPlugnames($row->template);
			     echo $plugins_name;
			   ?>
			</td>
			<td align="center">
		     <?php echo $published ; ?>
			
			</td>
			
			<td align="center">
			 
			   <?php //echo JHTML::_( 'grid.boolean' , $i , $row->isdefault ,'toggledefault' ,'toggledefault' ); 
					 if(JVERSION < '1.6.0')
					 {
						 ?>
						 <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','toggledefault')"><img src="images/<?php echo ($row->isdefault ) ? 'tick.png' : 'publish_x.png';?>" width="16" height="16" border="0" /></a> 
						<?php
					    	echo JHTML::_( 'grid.id' , $i , $row->isdefault ,'toggledefault' ,'toggledefault' );
					 } 
					 else
					 {
							echo JHTML::_( 'grid.boolean' , $i , $row->isdefault ,'toggledefault' ,'toggledefault' );		   
			          }
			   ?>
			
			</td>
			
			<td>
				<?php echo $row->subscribe; ?>
			</td>
			<td>
				<?php echo $row->unsubscribe; ?>
			</td>
			<td>
				<?php echo $row->notopted; ?>
			</td>
			
			
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	
	<tfoot>
	<tr>
		<td colspan="9"><?php  echo $this->pagination->getListFooter(); 
		 ?></td>
	</tr>
</tfoot>
	</table>
</div>

<input type="hidden" name="option" value="com_jmailalerts" />
<input type="hidden" name="view" value="alertypes" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="alertype" />
</form>

