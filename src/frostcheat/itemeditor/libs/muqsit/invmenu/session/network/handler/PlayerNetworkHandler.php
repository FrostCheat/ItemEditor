<?php

declare(strict_types=1);

namespace frostcheat\itemeditor\libs\muqsit\invmenu\session\network\handler;

use Closure;
use frostcheat\itemeditor\libs\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}