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

namespace OCA\ServiceProviders\Command;

use OCA\ServiceProviders\Contracts\ICalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends Command {

	/** @var ICalculator */
	private $calculator;

	public function __construct(ICalculator $calculator) {
		parent::__construct('calculator:add');

		$this->addArgument('a', InputArgument::REQUIRED);
		$this->addArgument('b', InputArgument::REQUIRED);
		$this->calculator = $calculator;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$argA = (int) $input->getArgument('a');
		$argB = (int) $input->getArgument('b');

		$sum = $this->calculator->add($argA, $argB);

		$output->writeln($sum);
	}

}
