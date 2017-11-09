<?php

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\ServiceProviders\AppInfo;

use OCA\ServiceProviders\Provider\CalculatorServiceProvider;
use OCA\ServiceProviders\Provider\ServiceProviders;
use OCP\AppFramework\App;

class Application extends App {

	use ServiceProviders;

	protected $providers = [
		CalculatorServiceProvider::class,
	];

	public function __construct(array $urlParams = []) {
		parent::__construct('service_providers', $urlParams);

		$this->registerServiceProviders();
	}

}
