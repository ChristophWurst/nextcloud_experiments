<?php

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\ServiceProviders\Provider;

use ChristophWurst\Nextcloud\ServiceProviders\ServiceProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\Route\IRouter;

class RouteServiceProvider extends ServiceProvider {

	private function getRoutes() {
		return [
			[
				'name' => 'page#index',
				'url' => '/',
				'verb' => 'GET'
			],
		];
	}

	private function getResources() {
		return [];
	}

	public function register(App $app, IAppContainer $container) {
		$router = $container->query(IRouter::class);

		$app->registerRoutes($router, [
			'routes' => $this->getRoutes(),
			'resources' => $this->getResources(),
		]);
	}

}
