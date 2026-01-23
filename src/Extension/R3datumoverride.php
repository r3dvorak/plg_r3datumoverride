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
		// Header-Farben für Light und Dark Mode
		$headerLight = $this->params->get('header_bg_light', '');
		$headerDark  = $this->params->get('header_bg_dark', '');
		
		// Typografie & Layout Einstellungen
		$bodyFontSize      = $this->params->get('body_font_size', '0.95rem');
		$bodyLineHeight    = $this->params->get('body_line_height', '1.4');
		$headingWeight     = $this->params->get('heading_font_weight', '500');
		$smallFontSize     = $this->params->get('small_font_size', '0.76rem');
		
		// Sidebar
		$sidebarFontSize   = $this->params->get('sidebar_font_size', '0.9rem');
		$sidebarFontWeight = $this->params->get('sidebar_font_weight', '600');
		$sidebarItemHeight = $this->params->get('sidebar_item_height', '36px');
		
		// Tabellen
		$tableFontSize     = $this->params->get('table_font_size', '0.875rem');
		$tableHeadFontSize = $this->params->get('table_head_font_size', '0.9rem');
		$tablePadY         = $this->params->get('table_padding_y', '0.6rem');
		$tablePadX         = $this->params->get('table_padding_x', '0.75rem');
		
		// Buttons
		$btnFontSize       = $this->params->get('btn_font_size', '0.85rem');
		$btnLineHeight     = $this->params->get('btn_line_height', '1.25');
		$btnPadY           = $this->params->get('btn_padding_y', '0.25rem');
		$btnPadX           = $this->params->get('btn_padding_x', '0.75rem');

		// Cards
		$cardTitleSize     = $this->params->get('card_title_font_size', '1.1rem');
		$cardTitleWeight   = $this->params->get('card_title_font_weight', '400');

		// Quick Icons
		$quickIconWidth    = $this->params->get('quickicon_width', '150px');

		$css = [];
		$rootVars = [];

		// --- 1. CSS Variablen definieren (:root) ---
		if ($headerLight) {
			$rootVars[] = "--atum-header-bg: {$headerLight} !important;";
		}

		// Typografie Vars
		if ($bodyFontSize)   $rootVars[] = "--body-font-size: {$bodyFontSize};";
		if ($bodyLineHeight) $rootVars[] = "--body-line-height: {$bodyLineHeight};";
		if ($headingWeight)  $rootVars[] = "--heading-font-weight: {$headingWeight};";
		if ($smallFontSize)  $rootVars[] = "--small-font-size: {$smallFontSize};";

		// Tabellen Vars
		if ($tableFontSize)     $rootVars[] = "--table-font-size: {$tableFontSize};";
		if ($tableHeadFontSize) $rootVars[] = "--table-head-font-size: {$tableHeadFontSize};";
		if ($tablePadY)         $rootVars[] = "--table-cell-padding-y: {$tablePadY};";
		if ($tablePadX)         $rootVars[] = "--table-cell-padding-x: {$tablePadX};";

		// Button Vars
		if ($btnFontSize)   $rootVars[] = "--btn-font-size: {$btnFontSize};";
		if ($btnLineHeight) $rootVars[] = "--btn-line-height: {$btnLineHeight};";
		if ($btnPadY)       $rootVars[] = "--btn-padding-y: {$btnPadY};";
		if ($btnPadX)       $rootVars[] = "--btn-padding-x: {$btnPadX};";

		// Card Vars
		if ($cardTitleSize)   $rootVars[] = "--card-title-font-size: {$cardTitleSize};";
		if ($cardTitleWeight) $rootVars[] = "--card-title-font-weight: {$cardTitleWeight};";

		if (!empty($rootVars)) {
			$css[] = ":root { " . implode(' ', $rootVars) . " }";
		}

		// --- 1b. Dark Mode Overrides ---
		if ($headerDark) {
			$css[] = '[data-bs-theme="dark"] { --atum-header-bg: ' . $headerDark . ' !important; }';
		}

		// --- 2. Variablen anwenden (Bindings) ---
		
		// Body
		$css[] = "body { font-size: var(--body-font-size) !important; line-height: var(--body-line-height) !important; }";
		
		// Headings
		$css[] = "h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 { font-weight: var(--heading-font-weight) !important; }";
		
		// Small text
		$css[] = "small, .small { font-size: var(--small-font-size) !important; }";

		// Tabellen
		$css[] = ".table { font-size: var(--table-font-size) !important; }";
		$css[] = ".table thead th { font-size: var(--table-head-font-size) !important; }";
		$css[] = ".table > :not(caption) > * > * { padding: var(--table-cell-padding-y) var(--table-cell-padding-x) !important; }";

		// Buttons
		$css[] = ".btn { font-size: var(--btn-font-size) !important; line-height: var(--btn-line-height) !important; padding: var(--btn-padding-y) var(--btn-padding-x) !important; }";

		// Cards
		$css[] = ".card-title { font-size: var(--card-title-font-size) !important; font-weight: var(--card-title-font-weight) !important; }";

		// Sidebar (Spezifisch)
		if ($sidebarFontSize || $sidebarFontWeight) {
			$sSize = $sidebarFontSize ?: '0.9rem';
			$sWeight = $sidebarFontWeight ?: '600';
			$css[] = ".sidebar-nav li, .main-nav li { font-size: {$sSize} !important; font-weight: {$sWeight} !important; }";
		}
		if ($sidebarItemHeight) {
			$css[] = ".sidebar-wrapper .item > a { min-block-size: {$sidebarItemHeight} !important; }";
		}

		// Quick Icons Grid
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
