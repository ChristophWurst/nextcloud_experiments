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

namespace OCA\AOP\Support;

use Closure;
use OCP\AppFramework\Utility\IControllerMethodReflector;
use OCP\IDBConnection;
use OCP\ILogger;
use Symfony\Component\Config\Definition\Exception\Exception;

class TransactionAspect extends Aspect {

	/** @var ILogger */
	private $logger;

	/** @var IDBConnection */
	private $dbConnection;

	/** @var IControllerMethodReflector */
	private $reflector;

	public function __construct(ILogger $logger, IDBConnection $dbConnection, IControllerMethodReflector $reflector) {
		$this->logger = $logger;
		$this->dbConnection = $dbConnection;
		$this->reflector = $reflector;
	}

	private function isTransactional($class, $method) {
		$this->reflector->reflect($class, $method);
		return $this->reflector->hasAnnotation('Transactional');
	}

	public function around($object, $class, $method, array $params, Closure $proceed) {
		$this->logger->info('before method call');

		$isTransacted = $this->isTransactional($class, $method);

		if ($isTransacted) {
			$ret = $this->runInTransaction($object, $class, $method, $params, $proceed);
		} else {
			$ret = $this->runWithoutTransaction($object, $class, $method, $params, $proceed);
		}

		$this->logger->info('after method call');
		return $ret;
	}

	private function runInTransaction($object, $class, $method, $params, $proceed) {
		$this->dbConnection->beginTransaction();
		try {
			$ret = parent::around($object, $class, $method, $params, $proceed);
		} catch (Exception $e) {
			$this->dbConnection->rollBack();
			throw $e;
		}
		$this->dbConnection->commit();

		return $ret;
	}

	private function runWithoutTransaction($object, $class, $method, $params, $proceed) {
		return parent::around($object, $class, $method, $params, $proceed);
	}

}
