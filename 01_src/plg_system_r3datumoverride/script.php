<?php
/**
 * @package     plg_system_r3datumoverride
 * @version     1.0.16
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvorak <info@r3d.de> - https://extensions.r3d.de
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class plg_system_r3datumoverrideInstallerScript
{
	/**
	 * Runs after installation/update.
	 */
	public function postflight($type, $parent)
	{
		if ($type !== 'install' && $type !== 'discover_install') {
			return;
		}

		$app = Factory::getApplication();
		$link = 'index.php?option=com_plugins&view=plugins&filter[search]=r3datumoverride';
		$msg = Text::sprintf('PLG_SYSTEM_R3DATUMOVERRIDE_POST_INSTALL_MSG', $link);
		$app->enqueueMessage($msg, 'warning');
	}
}
