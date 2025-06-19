<?php

namespace frostcheat\itemeditor\screen;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;

use frostcheat\itemeditor\ItemEditor;
use frostcheat\itemeditor\utils\Utils;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ScreenManager
{
    /** @var array<string, array{InvMenu, Item}>*/
    public array $players = [];

    public array $managers = [];

    public function openMainScreen(Player $player, Item $item, InvMenu $send = null): void {
        if ($send === null) {
            $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        } else {
            $menu = $send;
        }
        $glass = VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK)->asItem();
        $menu->getInventory()->clearAll();
        $menu->getInventory()->setContents([
            0 => $glass,
            1 => $glass,
            2 => $glass,
            9 => $glass,
            10 => $glass,
            11 => $glass,
            18 => $glass,
            19 => $glass,
            20 => $glass,
            27 => $glass,
            28 => $item,
            29 => $glass,
            36 => $glass,
            37 => $glass,
            38 => $glass,
            45 => $glass,
            46 => $glass,
            47 => $glass,
        ]);
        $menu->setName(TextFormat::colorize("&c&lI&f&lE &rv" . ItemEditor::getInstance()->getDescription()->getVersion()));
        $menu = $this->addFunctionsToMenu($menu);
        if ($send === null) {
            $menu->send($player);
            $player->getInventory()->setItemInHand(VanillaBlocks::AIR()->asItem());
            $this->players[$player->getName()] = [$menu, $item];
        }
    }

    public function addFunctionsToMenu(InvMenu $menu): InvMenu {
        $items = [
            31 => VanillaItems::NAME_TAG()->setCustomName(TextFormat::colorize("&eChange Name"))->setLore([TextFormat::colorize("&8* Change the name of the item")]),
            32 => VanillaItems::BOOK()->setCustomName(TextFormat::colorize("&eAdd Enchantment"))->setLore([TextFormat::colorize("&8* Add enchantment to the item")]),
            33 => VanillaItems::OAK_SIGN()->setCustomName(TextFormat::colorize("&eChange Lore"))->setLore([TextFormat::colorize("&8* Change the lore lines of the item")]),
        ];

        foreach ($items as $slot => $item) {
            $menu->getInventory()->setItem($slot, $item);
        }

        $menu->setListener(function (InvMenuTransaction $transaction) use ($menu): InvMenuTransactionResult {
            $item = $transaction->getItemClicked();
            $player = $transaction->getPlayer();

            if ($item->equalsExact($this->players[$player->getName()][1])) {
                $player->getInventory()->setItemInHand($this->players[$player->getName()][1]);
                $player->removeCurrentWindow();
                unset($this->players[$player->getName()]);
            } else if ($item->getTypeId() === VanillaItems::NAME_TAG()->getTypeId()) {
                $player->removeCurrentWindow();
                $this->players[$player->getName()] = [$this->players[$player->getName()][0], $this->players[$player->getName()][1]];
                $this->managers[$player->getName()] = ["name" => true];
                $player->sendMessage(TextFormat::colorize("&eWrite the name you want in the chat &7(remember that you can use '&' for the color format)"));
            } else if ($item->getTypeId() === VanillaItems::BOOK()->getTypeId()) {
                $this->openEnchantManager($menu, $player);
            } else if ($item->getTypeId() === VanillaItems::OAK_SIGN()->getTypeId()) {
                $this->openLoreManager($menu, $player);
            }
            return $transaction->discard();
        });
        return $menu;
    }

    const ITEMS_PER_PAGE = 44;

    public function openEnchantManager(InvMenu $menu, Player $player, int $currentPage = 1): void {
        $enchants = VanillaEnchantments::getAll();
        $menu->getInventory()->clearAll();

        $totalItems = 0;
        foreach ($enchants as $enchantment) {
            $totalItems += $enchantment->getMaxLevel();
        }
        $totalPages = ceil($totalItems / self::ITEMS_PER_PAGE);

        $startIndex = ($currentPage - 1) * self::ITEMS_PER_PAGE;
        $slot = 0;

        foreach ($enchants as $enchantment) {
            for ($i = 1; $i <= $enchantment->getMaxLevel(); $i++) {
                if ($slot < $startIndex) {
                    $slot++;
                    continue;
                }

                if ($slot >= $startIndex + self::ITEMS_PER_PAGE) {
                    break 2;
                }

                $item = VanillaItems::ENCHANTED_BOOK()
                    ->setCustomName(TextFormat::colorize("&e" . $player->getServer()->getLanguage()->translate($enchantment->getName()) . " " . Utils::numberToRoman($i)));
                $item->getNamedTag()->setString("enchantment", $player->getServer()->getLanguage()->translate($enchantment->getName()));
                $item->getNamedTag()->setInt("level", $i);

                $menu->getInventory()->addItem($item);
                $slot++;
            }
        }

        if ($currentPage < $totalPages) {
            $nextPageItem = VanillaItems::ARROW()
                ->setCustomName(TextFormat::colorize("&aNext Page (" . ($currentPage + 1) . "/" . $totalPages . ")"));
            $menu->getInventory()->setItem(53, $nextPageItem);
        }

        if ($currentPage > 1) {
            $previousPageItem = VanillaItems::ARROW()
                ->setCustomName(TextFormat::colorize("&cPrevious Page (" . ($currentPage - 1) . "/" . $totalPages . ")"));
            $menu->getInventory()->setItem(45, $previousPageItem);
        }

        $return = VanillaItems::ARROW()
            ->setCustomName(TextFormat::colorize("&cReturn to Main"));
        $menu->getInventory()->setItem(49, $return);

        $menu->setListener(function (InvMenuTransaction $transaction) use ($menu, $player, $currentPage, $totalPages): InvMenuTransactionResult {
            $item = $transaction->getItemClicked();

            if (TextFormat::clean($item->getName()) === "Return to Main") {
                $this->openMainScreen($player, $this->players[$player->getName()][1], $menu);
                return $transaction->discard();
            }

            if (TextFormat::clean($item->getName()) === "Next Page (" . ($currentPage + 1) . "/" . $totalPages . ")") {
                $this->openEnchantManager($menu, $player, $currentPage + 1);
                return $transaction->discard();
            }

            if (TextFormat::clean($item->getName()) === "Previous Page (" . ($currentPage - 1) . "/" . $totalPages . ")") {
                $this->openEnchantManager($menu, $player, $currentPage - 1);
                return $transaction->discard();
            }

            if ($item->getNamedTag()->getTag("enchantment") !== null && $item->getNamedTag()->getTag("level") !== null) {
                if ($item->getNamedTag()->getString("enchantment") !== null && $item->getNamedTag()->getInt("level") !== null) {
                    $enchant = StringToEnchantmentParser::getInstance()->parse($item->getNamedTag()->getString("enchantment"));
                    if ($enchant instanceof Enchantment) {
                        $v = $this->players[$player->getName()][1]->addEnchantment(new EnchantmentInstance($enchant, $item->getNamedTag()->getInt("level")));
                        $this->players[$player->getName()][1] = $v;
                        $this->openMainScreen($player, $v, $menu);
                    }
                }
            }

            return $transaction->discard();
        });
    }

    public function openLoreManager(InvMenu $menu, Player $player, bool $send = false): void {
        $item = $this->players[$player->getName()][1];
        $ls = $item->getLore();
        $menu->getInventory()->clearAll();

        $menu->getInventory()->setItem(45, VanillaItems::ARROW()->setCustomName(TextFormat::colorize("&4Back")));
        $menu->getInventory()->setItem(49, $item);
        $menu->getInventory()->setItem(53, VanillaBlocks::BEACON()->asItem()->setCustomName(TextFormat::colorize("&eAdd line")));

        $i = 0;
        foreach ($ls as $lore) {
            if (ctype_space($lore) || trim($lore) === '') {
                $v = VanillaItems::PAPER()->setCustomName("space$i")->setLore([TextFormat::colorize("&cClick for delete this line")]);
            } else {
                $v = VanillaItems::PAPER()->setCustomName($lore)->setLore([TextFormat::colorize("&cClick for delete this line")]);
            }

            $menu->getInventory()->addItem($v);
            $i++;
        }

        $menu->setListener(function (InvMenuTransaction $transaction) use ($item): InvMenuTransactionResult {
            $i = $transaction->getItemClicked();
            $player = $transaction->getPlayer();

            if ($i->equalsExact($this->players[$player->getName()][1])) {
                $this->openMainScreen($player, $this->players[$player->getName()][1], $this->players[$player->getName()][0]);
            } else if ($i->getTypeId() === VanillaItems::PAPER()->getTypeId()) {
                $ls = $this->players[$player->getName()][1]->getLore();
                $line = $i->getName();

                if (preg_match('/^space(\d+)$/', $line, $matches)) {
                    $index = (int)$matches[1];

                    if (isset($ls[$index])) {
                        unset($ls[$index]);
                    }

                    $this->players[$player->getName()][1] = $this->players[$player->getName()][1]->setLore($ls);
                    $this->openLoreManager($this->players[$player->getName()][0], $player);
                    return $transaction->discard();
                }

                foreach ($ls as $key => $lore) {
                    if (strtolower(TextFormat::clean($lore)) === strtolower(TextFormat::clean($line))) {
                        unset($ls[$key]);
                        break;
                    }
                }
                $this->players[$player->getName()][1] = $this->players[$player->getName()][1]->setLore($ls);
                $this->openLoreManager($this->players[$player->getName()][0], $player);
            } else if ($i->getTypeId() === VanillaBlocks::BEACON()->asItem()->getTypeId()) {
                $player->removeCurrentWindow();
                $this->managers[$player->getName()] = ["lore" => true];
                $player->sendMessage(TextFormat::colorize("&eWrite the lore line you want in the chat &7(remember that you can use '&' for the color format) (&efor space write %space%&7)"));
            } else if ($i->getTypeId() === VanillaItems::ARROW()->getTypeId()) {
                $this->openMainScreen($player, $this->players[$player->getName()][1], $this->players[$player->getName()][0]);
            }
            return $transaction->discard();
        });
        $this->players[$player->getName()][0] = $menu;
        if ($send) {
            $menu->send($player);
        }
    }
}