<?php
/**
 * @copyright           Copyright (C) 2010 - Lhacky
 * @license		GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
 *   This file is part of JINC.
 *
 *   JINC is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   JINC is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with JINC.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');
?>
<h1>Мои подписки</h1>
<form method="POST" action="/index.php">
    <table>
        <tr>
            <td>Подписаться на рассылку специальных предложений</td>
            <td><input type="checkbox" name="subscribe" value="1"></td>
        </tr>
        <?php foreach ($this->params as $param){ ?>
        <tr>
            <td>
            <?= $param['name'] ?>
            </td>
            <td>
            <?php foreach($param['children'] as $child) { ?>
                <input type="checkbox" name="param[<?= $child['first_index'] ?>]" value='1'> <?= $child['name']; ?>
            <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </table>
    <input type="submit" class="btn" value="<?php echo JText::_('COM_JINC_SUBSCRIBE'); ?>" />
    <input type="hidden" name="option" value="com_jinc" />
    <input type="hidden" name="task" value="newsletter.subscribe" />
    <?php echo JHTML::_('form.token'); ?>
</form>