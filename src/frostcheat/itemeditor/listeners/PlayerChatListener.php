<?php

namespace frostcheat\itemeditor\listeners;

use frostcheat\itemeditor\ItemEditor;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class PlayerChatListener implements Listener
{
    public function handleChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        $data = ItemEditor::getInstance()->getScreenManager()->players;
        if (isset(ItemEditor::getInstance()->getScreenManager()->players[$player->getName()])) {
            if (isset(ItemEditor::getInstance()->getScreenManager()->managers[$player->getName()]["name"])) {
                if (isset($data[$player->getName()][1])) {
                    $event->cancel();
                    $item = $data[$player->getName()][1];
                    $item->setCustomName(TextFormat::colorize($message));
                    $menu = $data[$player->getName()][0];
                    $menu->getInventory()->setItem(28, $item);
                    ItemEditor::getInstance()->getScreenManager()->players[$player->getName()] = [$menu, $item];
                    $menu->send($player);
                    unset(ItemEditor::getInstance()->getScreenManager()->managers[$player->getName()]["name"]);
                }
            } else if (isset(ItemEditor::getInstance()->getScreenManager()->managers[$player->getName()]["lore"])) {
                if (isset($data[$player->getName()][1])) {
                    $event->cancel();
                    $item = $data[$player->getName()][1];
                    $ls = $item->getLore();
                    if (strtolower($message) !== "%space%") {
                        $ls[] = TextFormat::colorize($message);
                    } else {
                        $ls[] = "";
                    }
                    $item->setLore($ls);
                    ItemEditor::getInstance()->getScreenManager()->players[$player->getName()][1] = $item;
                    ItemEditor::getInstance()->getScreenManager()->openLoreManager(ItemEditor::getInstance()->getScreenManager()->players[$player->getName()][0], $player, true);
                    unset(ItemEditor::getInstance()->getScreenManager()->managers[$player->getName()]["lore"]);
                }
            }
        }
    }
}