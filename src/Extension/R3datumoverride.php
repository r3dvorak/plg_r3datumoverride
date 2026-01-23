<?php
/**
 * @package     plg_r3datumoverride
 * @version     1.0.5
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvořák <dev@r3d.de> - https://r3d.de
 */

namespace Joomla\Plugin\System\R3datumoverride\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Backend-only system plugin for ATUM administrator template overrides (Joomla 6+).
 *
 * Key requirements:
 * - Must NEVER run on the frontend.
 * - Must load custom CSS AFTER ATUM template CSS to avoid breaking color modes.
 */
final class R3datumoverride extends CMSPlugin
{
	/**
	 * Inject backend-only CSS via WebAssetManager.
	 *
	 * Notes:
	 * - This hook runs during head compilation.
	 * - We hard-stop if not in administrator context (defense in depth),
	 *   even though the manifest already declares client="administrator".
	 * - We only act on HTML documents.
	 */
	public function onBeforeCompileHead(): void
	{
		$app = \Joomla\CMS\Factory::getApplication();

		// Backend only
		if (!$app->isClient('administrator')) {
			return;
		}

		$document = $app->getDocument();

		// HTML documents only
		if ($document->getType() !== 'html') {
			return;
		}

		// === HARD DEBUG ===
		// This must appear in the browser console if the plugin is executed
		$document->addScriptDeclaration(
			'console.log("R3D ATUM Override plugin executed (onBeforeCompileHead)");'
		);

		// Optional: also inject CSS directly (no asset manager)
		$document->addStyleSheet(
			'/media/plg_system_r3datumoverride/css/atum-override.css'
		);
	}

}
