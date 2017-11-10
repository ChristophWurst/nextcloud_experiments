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

namespace OCA\AOP\AppInfo;

use OCA\AOP\Contracts\IUserService;
use OCA\AOP\Service\UserService;
use OCA\AOP\Support\MethodInterceptor;
use OCA\AOP\Support\TransactionAspect;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use ProxyManager\Factory\RemoteObjectFactory;

class Application extends App {

	public function __construct(array $urlParams = []) {
		parent::__construct('aop', $urlParams);

		$container = $this->getContainer();

		$container->registerService(IUserService::class, function(IAppContainer $container) {
			$service = $container->query(UserService::class);
			$interceptor = new MethodInterceptor($service);
			$transactionAspect = $container->query(TransactionAspect::class);
			$interceptor->setAspect($transactionAspect);

			$factory = new RemoteObjectFactory($interceptor);
			$proxy = $factory->createProxy(UserService::class);

			return $proxy;
		});
	}

}
