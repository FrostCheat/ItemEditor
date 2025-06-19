<?php

namespace frostcheat\itemeditor\commands\subcommands;

use frostcheat\itemeditor\ItemEditor;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ReOpenSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct('reopen', 'ReOpen a editor');
        $this->setPermission('itemeditor.command.reopen');
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if ($sender instanceof Player) {
            if (isset(ItemEditor::getInstance()->getScreenManager()->players[$sender->getName()])) {
                $data = ItemEditor::getInstance()->getScreenManager()->players[$sender->getName()];
                if (isset($data[1])) {
                    ItemEditor::getInstance()->getScreenManager()->openMainScreen($sender, $data[1]);
                }
            }
        }
    }
}