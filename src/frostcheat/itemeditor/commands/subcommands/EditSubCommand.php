<?php

namespace frostcheat\itemeditor\commands\subcommands;

use frostcheat\itemeditor\ItemEditor;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class EditSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct('edit', 'Edit a item');
        $this->setPermission('itemeditor.command.edit');
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