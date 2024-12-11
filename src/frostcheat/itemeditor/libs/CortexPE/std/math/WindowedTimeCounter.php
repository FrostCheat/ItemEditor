<?php

namespace CortexPE\std\math;

class WindowedTimeCounter {
	private $timeSeries = [];

	public function __construct(private int $windowSeconds = 1, private int $precision = 1) {
	}

	public function increment(int $amount = 1):void {
		$this->timeSeries[$k] = ($this->timeSeries[$k = (string)round(microtime(true), $this->precision)] ?? 0) + $amount;
	}

	public function get():int {
		$sum = 0;
		$ct = microtime(true) - $this->windowSeconds;
		foreach($this->timeSeries as $time => $hits) {
			if((float)$time < $ct){
				unset($this->timeSeries[$time]);
				continue;
			}
			$sum += $hits;
		}
		return $sum;
	}

	public function reset():void {
		$this->timeSeries = [];
	}
}