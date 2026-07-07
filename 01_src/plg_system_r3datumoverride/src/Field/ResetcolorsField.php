<?php
/**
 * @package     plg_system_r3datumoverride
 * @version     1.0.20
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvorak <info@r3d.de> - https://extensions.r3d.de
 */

namespace Joomla\Plugin\System\R3datumoverride\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

final class ResetcolorsField extends FormField
{
	protected $type = 'Resetcolors';

	protected function getLabel(): string
	{
		return '';
	}

	protected function getInput(): string
	{
		$this->registerScript();
		$label = htmlspecialchars(Text::_('PLG_SYSTEM_R3DATUMOVERRIDE_COLOR_CLEAR_LABEL'), ENT_QUOTES, 'UTF-8');
		$title = htmlspecialchars(Text::_('PLG_SYSTEM_R3DATUMOVERRIDE_COLOR_CLEAR_DESC'), ENT_QUOTES, 'UTF-8');

		return '<div class="d-flex align-items-center gap-2">'
			. '<button type="button" class="btn btn-sm btn-outline-secondary js-r3datumoverride-reset-colors" title="' . $title . '">'
			. '<span class="icon-refresh" aria-hidden="true"></span> '
			. $label
			. '</button>'
			. '</div>';
	}

	private function registerScript(): void
	{
		static $loaded = false;

		if ($loaded) {
			return;
		}

		$loaded = true;

		$document = Factory::getApplication()->getDocument();

		$document->addScriptDeclaration(<<<JS
(function () {
	'use strict';

	const fieldNames = [
		'header_bg_color',
		'header_bg_dark',
		'header_text_color',
		'header_icon_color',
		'sidebar_bg_color',
		'subhead_bg_color'
	];

	const getField = (name) => document.querySelector(
		'input[type="color"][name$="[' + name + ']"], input[name$="[' + name + ']"]'
	);

	const applyDefaults = () => {
		fieldNames.forEach((name) => {
			const field = getField(name);

			if (!field) {
				return;
			}

			field.value = '';
			field.dispatchEvent(new Event('input', { bubbles: true }));
			field.dispatchEvent(new Event('change', { bubbles: true }));
		});
	};

	const init = () => {
		document.querySelectorAll('.js-r3datumoverride-reset-colors').forEach((button) => {
			button.addEventListener('click', (event) => {
				event.preventDefault();
				applyDefaults();
			});
		});
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init, { once: true });
	} else {
		init();
	}
}());
JS);
	}
}
