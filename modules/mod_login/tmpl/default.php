<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');

$doc=& JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true).'/components/com_virtuemart/assets/css/facebox.css');
$doc->addScript(JURI::base(true).'/components/com_virtuemart/assets/js/jcarousel/jquery-1.9.1.min.js');
$doc->addScript(JURI::base(true).'/components/com_virtuemart/assets/js/facebox.js');
$doc->addScriptDeclaration('
    jQuery(document).ready(function(){
        jQuery("a[rel=facebox]").facebox();
    });
');

?>

<div class="div-log-top">
	<h2>Вход в личный кабинет</h2>
</div>
<?php if ($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
	<div id="form-body">
		<?php if ($params->get('greeting')) : ?>
			<div class="login-greeting">
			<?php if($params->get('name') == 0) : {
				echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
			} else : {
				echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
			} endif; ?>
			</div>
		<?php endif; ?>
			<div class="logout-button">
				<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.logout" />
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	<div id="errors">
	</div>
	<div class="sprt"></div>
	<div class="div-log-bottom"></div>
</form>
<?php else : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
	<div id="form-body">
		<?php if ($params->get('pretext')): ?>
			<div class="pretext">
				<p><?php echo $params->get('pretext'); ?></p>
			</div>
		<?php endif; ?>
		<fieldset class="userdata">
		<div id="form-login-username">
			<div id="usr-nm">
				<label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
			</div>
			<div id="txt-fld"><input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" /></div>
		</div>
		<div id="form-login-password">
			<div id="div-inside">
				<label for="modlgn-passwd">Ведите пароль<?php/* echo JText::_('JGLOBAL_PASSWORD')*/ ?></label>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
				<div class="cl"></div>
			</div>
			<div id="txt-fld"><input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  /></div>
		</div>
		<?php /* if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember">
			<div><label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label></div>
			<div><input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/></div>
		</div>
		<?php endif; */ ?>
		
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		</fieldset>
		<?php if ($params->get('posttext')): ?>
			<div class="posttext">
			<p><?php echo $params->get('posttext'); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<div id="errors">
	</div>
	<div class="sprt"></div>
	<div class="div-log-bottom">
		<div id="div-log-sub">
			<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
		</div>
	</div>
</form>
<?php endif; ?>