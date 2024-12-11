<?php

namespace frostcheat\itemeditor\commands\subcommands;

use frostcheat\itemeditor\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\itemeditor\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\itemeditor\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use frostcheat\itemeditor\libs\CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class RenameSubCommand extends BaseSubCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($plugin, 'rename', 'Rename a item in hand');
        $this->setPermission('itemeditor.command.rename');
        $this->setPermissionMessage("§cYou don't have permission to us this command!");
    }

    /**
     * @inheritDoc
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerArgument(0, new RawStringArgument("name"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!($sender instanceof Player)) {
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage(TextFormat::colorize('&cUse /ie rename [name]'));
            return;
        }
        $item = clone $sender->getInventory()->getItemInHand();
        $name = $args["name"];

        if (!$item instanceof Tool && !$item instanceof Armor) {
            $sender->sendMessage(TextFormat::colorize('You have no armor and no tools in your hand'));
            return;
        }
        $item->setCustomName(TextFormat::colorize($name));
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage(TextFormat::colorize('&aYou have successfully renamed the item'));
    }
}