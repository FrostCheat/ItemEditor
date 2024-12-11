<?php

namespace CortexPE\std;

use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use SOFe\AwaitGenerator\Await;

final class AsyncUtils {
	private function __construct() {
	}

	public static function sleep(TaskScheduler $scheduler, float $seconds): \Generator {
		yield from Await::promise(fn($y) => $scheduler->scheduleDelayedTask(new ClosureTask($y), $seconds * 20));
	}

	/**
	 * Returns once the timeout passes, or once the amount of generators that have returned satisfied $minResponses
	 *
	 * @param TaskScheduler $scheduler
	 * @param array $generators
	 * @param float $seconds
	 * @param int|null $minResponses
	 * @return \Generator
	 */
	public static function timeout(TaskScheduler $scheduler, array $generators, float $seconds, ?int $minResponses = null): \Generator {
		if($minResponses === null) $minResponses = count($generators);

		return yield from Await::promise(function($resolve) use ($scheduler, $generators, $seconds, $minResponses) {
			$out = [];
			Await::f2c(function() use ($scheduler, $seconds, $resolve, &$out) {
				yield from self::sleep($scheduler, $seconds);
				$resolve($out);
			});
			foreach($generators as $k => $g) {
				Await::f2c(function() use ($minResponses, $resolve, $k, $g, &$out) {
					$out[$k] = yield from $g;
					if(count($out) >= $minResponses) $resolve($out);
				});
			}
		});
	}
}