<?php

namespace frostcheat\itemeditor;

use frostcheat\itemeditor\commands\ItemEditorCommand;
use frostcheat\itemeditor\libs\CortexPE\Commando\exception\HookAlreadyRegistered;
use frostcheat\itemeditor\libs\CortexPE\Commando\PacketHooker;
use frostcheat\itemeditor\libs\JackMD\UpdateNotifier\UpdateNotifier;
use frostcheat\itemeditor\libs\muqsit\invmenu\InvMenuHandler;
use frostcheat\itemeditor\listeners\PlayerChatListener;
use frostcheat\itemeditor\screen\ScreenManager;
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