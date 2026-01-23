<?php
/**
 * @package     plg_r3datumoverride
 * @version     1.0.6
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvořák <dev@r3d.de> - https://r3d.de
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * System plugin: R3D ATUM Override
 *
 * IMPORTANT:
 * This class MUST exist in this file, otherwise Joomla will NOT
 * load the plugin at all.
 */
final class PlgSystemR3datumoverride extends CMSPlugin
{
	public function onBeforeCompileHead(): void
	{
		$app = Factory::getApplication();

		// Administrator only
		if (!$app->isClient('administrator')) {
			return;
		}

		$document = $app->getDocument();

		if ($document->getType() !== 'html') {
			return;
		}

		// HARD DEBUG – MUST appear in browser console
		$document->addScriptDeclaration(
			'console.log("R3D ATUM Override plugin EXECUTED");'
		);

		// Load CSS directly (no WebAssetManager yet)
		$document->addStyleSheet(
			'/media/plg_system_r3datumoverride/css/atum-override.css'
		);
	}
}
