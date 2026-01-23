<?php
/**
 * @package     plg_r3datumoverride
 * @version     1.0.7
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Richard Dvořák <dev@r3d.de> - https://r3d.de
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\R3datumoverride\Extension\R3datumoverride;

return new class implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$dispatcher = $container->get(DispatcherInterface::class);
				$plugin = new R3datumoverride(
					$dispatcher,
					(array) PluginHelper::getPlugin('system', 'r3datumoverride')
				);
				$plugin->setApplication($container->get(CMSApplicationInterface::class));

				return $plugin;
			}
		);
	}
};
