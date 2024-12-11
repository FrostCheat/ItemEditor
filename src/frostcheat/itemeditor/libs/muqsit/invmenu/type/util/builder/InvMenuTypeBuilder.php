<?php

declare(strict_types=1);

namespace frostcheat\itemeditor\libs\muqsit\invmenu\type\util\builder;

use frostcheat\itemeditor\libs\muqsit\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}