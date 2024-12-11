<?php

declare(strict_types=1);

namespace frostcheat\itemeditor\libs\muqsit\invmenu\type;

use frostcheat\itemeditor\libs\muqsit\invmenu\InvMenu;
use frostcheat\itemeditor\libs\muqsit\invmenu\type\graphic\InvMenuGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType{

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic;

	public function createInventory() : Inventory;
}