<?php

namespace frostcheat\itemeditor\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class AmountSubCommand extends BaseSubCommand {

    public function __construct()
    {
        parent::__construct('amount', 'Edit the amount of the item');
        $this->setPermission('itemeditor.command.amount');
    }

    public function prepare(): void {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerArgument(0, new IntegerArgument("amount"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if ($sender instanceof Player) {
            if (count($args) < 1) {
                $sender->sendMessage(TextFormat::colorize('&cUse /ie amount [amount]'));
                return;
            }

            $amount = $args["amount"];
            if ($amount < 1) {
                $sender->sendMessage(TextFormat::colorize('&cThe amount of the item must be greater than or equal to 1'));
                return;
            }

            if ($amount > 64) {
                $sender->sendMessage(TextFormat::colorize('&cThe amount of the item must be less than or equal to 64'));
                return;
            }

            $item = $sender->getInventory()->getItemInHand();
            if ($item === null) {
                $sender->sendMessage(TextFormat::colorize('&cYou must have an item in your hand'));
                return;
            }

            $sender->getInventory()->setItemInHand($item->setCount($amount));
            $sender->sendMessage(TextFormat::colorize('&aYou have successfully edited the item amount.'));
        }
    }
}