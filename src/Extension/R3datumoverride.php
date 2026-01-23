<?php
/**
 * @package     plg_r3datumoverride
 * @version     1.0.7
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvořák <dev@r3d.de> - https://r3d.de
 */

namespace Joomla\Plugin\System\R3datumoverride\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

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

		// --- Konfiguration auslesen und anwenden ---
		// Beispiel: Header-Farbe aus den Plugin-Einstellungen holen
		$headerColor = $this->params->get('header_bg_color', '');
		
		// Typografie & Layout Einstellungen
		$bodyFontSize      = $this->params->get('body_font_size', '0.95rem');
		$headingWeight     = $this->params->get('heading_font_weight', '500');
		$sidebarFontSize   = $this->params->get('sidebar_font_size', '0.9rem');
		$sidebarItemHeight = $this->params->get('sidebar_item_height', '36px');
		$tableFontSize     = $this->params->get('table_font_size', '0.875rem');
		$tablePadY         = $this->params->get('table_padding_y', '0.6rem');
		$tablePadX         = $this->params->get('table_padding_x', '0.75rem');
		$quickIconWidth    = $this->params->get('quickicon_width', '150px');

		$css = [];

		if ($headerColor) {
			// CSS-Variable von ATUM überschreiben
			$css[] = ":root { --atum-header-bg: {$headerColor} !important; }";
		}

		// 1. Globale Schriftgröße (Joomla 6 nutzt --body-font-size Variable)
		if ($bodyFontSize) {
			$css[] = ":root { --body-font-size: {$bodyFontSize} !important; }";
			$css[] = "body { font-size: var(--body-font-size) !important; }";
		}

		// 2. Überschriften Gewichtung
		if ($headingWeight) {
			$css[] = "h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 { font-weight: {$headingWeight} !important; }";
		}

		// 3. Sidebar (Schrift & Höhe)
		if ($sidebarFontSize) {
			$css[] = ".main-nav { font-size: {$sidebarFontSize} !important; }";
		}
		if ($sidebarItemHeight) {
			$css[] = ".sidebar-wrapper .item > a { min-block-size: {$sidebarItemHeight} !important; }";
		}

		// 4. Tabellen (Schrift & Padding)
		if ($tableFontSize) {
			$css[] = ".table > :not(caption) > * > * { font-size: {$tableFontSize} !important; }";
		}
		if ($tablePadY || $tablePadX) {
			$py = $tablePadY ?: '0.75rem';
			$px = $tablePadX ?: '1rem';
			$css[] = ".table > :not(caption) > * > * { padding: {$py} {$px} !important; }";
		}

		// 5. Quick Icons Grid
		if ($quickIconWidth) {
			// auto-fit statt auto-fill sorgt für besseres Verhalten bei wenigen Icons
			$css[] = ".quick-icons .nav { grid-template-columns: repeat(auto-fit, minmax({$quickIconWidth}, 1fr)) !important; }";
		}

		// CSS in den Head schreiben
		if (!empty($css)) {
			$document->addStyleDeclaration(implode("\n", $css));
		}

		// Optional: also inject CSS directly (no asset manager)
		$document->addStyleSheet(
			Uri::root() . 'media/plg_system_r3datumoverride/css/atum-override.css'
		);
	}

}
