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
use OCP\IDBConnection;
use OCP\ILogger;

class TransactionAspect extends Aspect {

	/** @var ILogger */
	private $logger;

	/** @var IDBConnection */
	private $dbConnection;

	public function __construct(ILogger $logger, IDBConnection $dbConnection) {
		$this->logger = $logger;
		$this->dbConnection = $dbConnection;
	}

	public function around(Closure $proceed, array $params) {
		$this->logger->info('before method call');

		$this->dbConnection->beginTransaction();
		try {
			$ret = parent::around($proceed, $params);
		} catch (Exception $e) {
			$this->dbConnection->rollBack();
			throw $e;
		}
		$this->dbConnection->commit();

		$this->logger->info('after method call');
		return $ret;
	}

}
