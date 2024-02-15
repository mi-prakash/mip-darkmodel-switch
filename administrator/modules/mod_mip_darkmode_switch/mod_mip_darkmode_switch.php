<?php

/**
 * @package     MIP_DarkmodeSwitch.Administrator
 * @subpackage  mod_mip_darkmode_switch
 *
 * @copyright   (C) 2024 Mahmudul Islam Prakash <prakash.a7x@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

// Check permissions.
if (!$app->getIdentity()->authorise('core.login.admin')) {
    return;
}

require ModuleHelper::getLayoutPath('mod_mip_darkmode_switch', $params->get('layout', 'default'));
