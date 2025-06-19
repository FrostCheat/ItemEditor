<?php

namespace frostcheat\itemeditor;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;

use JackMD\UpdateNotifier\UpdateNotifier;

use muqsit\invmenu\InvMenuHandler;

use frostcheat\itemeditor\listeners\PlayerChatListener;
use frostcheat\itemeditor\screen\ScreenManager;
use frostcheat\itemeditor\commands\ItemEditorCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class ItemEditor extends PluginBase
{
    use SingletonTrait;

    private ScreenManager $screenManager;

    protected function onLoad(): void {
        self::setInstance($this);
    }

    /**
     * @throws HookAlreadyRegistered
     */
    public function onEnable(): void {
        $this->screenManager = new ScreenManager();

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());

        $this->getServer()->getPluginManager()->registerEvents(new PlayerChatListener(), $this);
        $this->getServer()->getCommandMap()->register("itemeditor", new ItemEditorCommand($this));
    }

    public function getScreenManager(): ScreenManager
    {
        return $this->screenManager;
    }
}