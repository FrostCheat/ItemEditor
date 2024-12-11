<?php

namespace frostcheat\itemeditor\commands\subcommands;

use frostcheat\itemeditor\ItemEditor;
use frostcheat\itemeditor\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\itemeditor\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

class ReOpenSubCommand extends BaseSubCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($plugin, 'reopen', 'ReOpen a editor');
        $this->setPermission('itemeditor.command.reopen');
        $this->setPermissionMessage("§cYou don't have permission to us this command!");
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