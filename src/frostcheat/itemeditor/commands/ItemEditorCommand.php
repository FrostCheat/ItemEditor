<?php

namespace frostcheat\itemeditor\commands;

use frostcheat\itemeditor\commands\subcommands\EditSubCommand;
use frostcheat\itemeditor\commands\subcommands\HelpSubCommand;
use frostcheat\itemeditor\commands\subcommands\RenameSubCommand;
use frostcheat\itemeditor\commands\subcommands\ReOpenSubCommand;
use frostcheat\itemeditor\libs\CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

class ItemEditorCommand extends BaseCommand
{

    public function __construct(protected Plugin $plugin) {
        parent::__construct($plugin, "ie");
        $this->setDescription("Main command for ItemEditor");
        $this->setUsage("/ie help");
        $this->setPermission("itemeditor.command");
        $this->setAliases(["ieditor", "itemedit", "itemeditor"]);
    }

    protected function prepare(): void
    {
        $this->registerSubCommand(new EditSubCommand($this->plugin));
        $this->registerSubCommand(new HelpSubCommand($this->plugin));
        $this->registerSubCommand(new RenameSubCommand($this->plugin));
        $this->registerSubCommand(new ReOpenSubCommand($this->plugin));
    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $sender->sendMessage("§cNo subcommand provided, try using: /" . $aliasUsed . " help");
    }
}