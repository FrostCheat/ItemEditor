<?php

namespace frostcheat\itemeditor\commands\subcommands;

use frostcheat\itemeditor\ItemEditor;
use frostcheat\itemeditor\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\itemeditor\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

class EditSubCommand extends BaseSubCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($plugin, 'edit', 'Edit a item');
        $this->setPermission('itemeditor.command.edit');
        $this->setPermissionMessage("§cYou don't have permission to us this command!");
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if ($sender instanceof Player) {
            ItemEditor::getInstance()->getScreenManager()->openMainScreen($sender, $sender->getInventory()->getItemInHand());
        }
    }
}