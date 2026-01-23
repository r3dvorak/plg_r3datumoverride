<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class plg_system_r3datumoverrideInstallerScript
{
	/**
	 * Wird nach der Installation/Update ausgeführt
	 */
	public function postflight($type, $parent)
	{
		// Nur bei Neuinstallation (oder Discover-Install) anzeigen
		if ($type !== 'install' && $type !== 'discover_install') {
			return;
		}

		$app = Factory::getApplication();

		// Link zur Plugin-Liste, gefiltert nach diesem Plugin
		$link = 'index.php?option=com_plugins&view=plugins&filter[search]=r3datumoverride';

		// Nachricht ausgeben (String muss in der .sys.ini definiert sein)
		$msg = Text::sprintf('PLG_SYSTEM_R3DATUMOVERRIDE_POST_INSTALL_MSG', $link);
		$app->enqueueMessage($msg, 'warning');
	}
}