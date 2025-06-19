<?php

namespace frostcheat\itemeditor\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class UnbreakableSubCommand extends BaseSubCommand {

    public function __construct()
    {
        parent::__construct('unbreakable', 'Set unbreakable state');
        $this->setPermission('itemeditor.command.unbreakable');
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
                if ($item->isUnbreakable()) {
                    $item->setUnbreakable(false);
                } else {
                    $item->setUnbreakable();
                }

                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(TextFormat::colorize("&eThe unbreakable of this item has changed to: " . ($item->isUnbreakable() ? "&aTrue" : "&cFalse")));
            } else {
                $sender->sendMessage(TextFormat::colorize('&cThis item is not valid, use an item that can be worn out.'));
            }
        }
    }
}