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
use ProxyManager\Factory\RemoteObject\AdapterInterface;

class MethodInterceptor implements AdapterInterface {

	/** @var mixed */
	private $wrapped;

	/** @var Aspect */
	private $aspect;

	/**
	 * @param mixed $wrapped
	 * @param Closure $closure
	 */
	public function __construct($wrapped) {
		$this->wrapped = $wrapped;
	}

	/**
	 * @param string $wrappedClass
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function call(string $wrappedClass, string $method, array $params = []) {
		$proceed = function($params) use ($method) {
			return call_user_func_array([$this->wrapped, $method], $params);
		};

		return $this->aspect->around($proceed, $params);
	}

	/**
	 * @param Aspect $aspect
	 */
	public function setAspect(Aspect $aspect) {
		$this->aspect = $aspect;
	}

}
