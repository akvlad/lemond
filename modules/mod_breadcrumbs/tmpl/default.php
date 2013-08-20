<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="breadcrumbs<?php echo $moduleclass_sfx; ?>">
<?php if ($params->get('showHere', 1))
	{
		echo '<span class="showHere">' .JText::_('MOD_BREADCRUMBS_HERE').'</span>';
	}

	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i ++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i-1]->link) && $list[$i]->link == $list[$i-1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);
        $prev_item='';
        $i=0;
        $filtered=$params->get('crumbsFilter');
        $filtered=  explode(',', $filtered);
	// Generate the trail
	foreach ($list as $key=>$item) :
            if(in_array($i, $filtered)){
                ++$i; continue;
            }
            else {
                ++$i;
            }
        $isSearchInVirtuemart=  JRequest::getVar('option',null,'get','string')=='com_virtuemart' && 
                JRequest::getVar('keyword',null,'get','string')!=null;

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);
        if($isSearchInVirtuemart)
            $show_last=false;
	if ($key != $last_item_key)
	{
		// Render all but last item - along with separator
		if (!empty($item->link))
		{
			echo '<a href="' . $item->link . '" class="pathway">' . $item->name . '</a>'.''.$separator.' ';
		}
		else
		{
			echo '<span>' . $item->name . '</span>';
		}

		/*if (($key != $penult_item_key) || $show_last)
		{
			echo ''.$separator.' ';
		}*/

	}
	elseif ($show_last)
	{
        if(JRequest::getVar('option')=='com_virtuemart' && JRequest::getVar('view')=='productdetails') echo '<br />';
		// Render last item if reqd.
		echo '<span>' . $item->name . '</span>';
	}
	endforeach; ?>
</div>
