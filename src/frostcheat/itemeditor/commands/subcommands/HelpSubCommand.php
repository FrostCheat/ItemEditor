<?php

namespace frostcheat\itemeditor\commands\subcommands;

use frostcheat\itemeditor\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class HelpSubCommand extends BaseSubCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($plugin, 'help', 'Help commands Item Editor');
        $this->setPermission('itemeditor.command.help');
        $this->setPermissionMessage("§cYou don't have permission to us this command!");
    }

    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $messages = [
            "help" => "Help commands Item Editor",
            "edit" => "Edit a item in hand",
            "reopen" => "ReOpen a editor",
            "rename" => "Rename a item in hand",
        ];

        foreach ($messages as $key => $message) {
            $sender->sendMessage(TextFormat::colorize("&b/ie $key &f- &7$message"));
        }
    }
}