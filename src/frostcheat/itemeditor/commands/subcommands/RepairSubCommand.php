<?php

namespace frostcheat\itemeditor\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RepairSubCommand extends BaseSubCommand {
    public function __construct()
    {
        parent::__construct('repair', 'Repair the item in hand');
        $this->setPermission('itemeditor.command.repair');
    }

    public function prepare(): void {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if ($sender instanceof Player) {
            $item = $sender->getInventory()->getItemInHand();
            if ($item === null) {
                $sender->sendMessage(TextFormat::colorize('&cYou must have an item in your hand'));
                return;
            }

            if ($item instanceof Durable) {
                $sender->getInventory()->setItemInHand($item->setDamage(0));
                $sender->sendMessage(TextFormat::colorize('&aThe item has been repaired successfully'));
            } else {
                $sender->sendMessage(TextFormat::colorize('&cThis item is not valid, use an item that can be worn out.'));
            }
        }
    }
}