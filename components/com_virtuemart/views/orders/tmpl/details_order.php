<?php
/**
 *
 * Order detail view
 *
 * @package	VirtueMart
 * @subpackage Orders
 * @author Oscar van Eijk, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: details_order.php 5341 2012-01-31 07:43:24Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<p><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_DATE') ?>: <?php echo vmJsApi::date($this->orderdetails['details']['BT']->created_on, 'LC4', true); ?>
<?php
    JPluginHelper::importPlugin('vmshipment');
    $_dispatcher = JDispatcher::getInstance();
    $returnValues = $_dispatcher->trigger('plgVmOnShowOrderFEInfo',array(  $this->orderdetails['details']['BT']->virtuemart_order_id));
?>
<p>Адрес доставки: <?php     foreach ($returnValues as $returnValue) {
        if ($returnValue !== null) {
            echo $returnValue;
        }
    } ?> </p>
