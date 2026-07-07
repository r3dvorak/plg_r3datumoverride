<?php
/**
 * @package     plg_system_r3datumoverride
 * @version     1.0.18
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvorak <info@r3d.de> - https://extensions.r3d.de
 */

namespace Joomla\Plugin\System\R3datumoverride\Form\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\Plugin\System\R3datumoverride\Extension\R3datumoverride;

final class ResetcolorsField extends FormField
{
	protected $type = 'Resetcolors';

	protected function getInput(): string
	{
		$this->registerScript();
		$label = htmlspecialchars(Text::_('PLG_SYSTEM_R3DATUMOVERRIDE_COLOR_RESET_LABEL'), ENT_QUOTES, 'UTF-8');
		$title = htmlspecialchars(Text::_('PLG_SYSTEM_R3DATUMOVERRIDE_COLOR_RESET_DESC'), ENT_QUOTES, 'UTF-8');

		return sprintf(
			'<button type="button" class="btn btn-outline-secondary js-r3datumoverride-reset-colors" title="%2$s">%1$s</button>',
			$label,
			$title
		);
	}

	private function registerScript(): void
	{
		static $loaded = false;

		if ($loaded) {
			return;
		}

		$loaded = true;

		$defaults = json_encode(R3datumoverride::ATUM_COLOR_DEFAULTS, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		$document = Factory::getApplication()->getDocument();

		$document->addScriptDeclaration(<<<JS
(function () {
	'use strict';

	const defaults = {$defaults};
	const fieldNames = Object.keys(defaults);

	const getField = (name) => document.querySelector(
		'input[type="color"][name$="[' + name + ']"], input[name$="[' + name + ']"]'
	);

	const applyDefaults = (force = false) => {
		fieldNames.forEach((name) => {
			const field = getField(name);

			if (!field) {
				return;
			}

			if (!force && field.value) {
				return;
			}

			field.value = defaults[name];
			field.dispatchEvent(new Event('input', { bubbles: true }));
			field.dispatchEvent(new Event('change', { bubbles: true }));
		});
	};

	const init = () => {
		applyDefaults(false);

		document.querySelectorAll('.js-r3datumoverride-reset-colors').forEach((button) => {
			button.addEventListener('click', (event) => {
				event.preventDefault();
				applyDefaults(true);
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
