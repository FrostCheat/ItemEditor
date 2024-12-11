<?php

namespace CortexPE\std;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\ModalForm;
use JetBrains\PhpStorm\Pure;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

// huge thanks to SOF3!!
final class AsyncForms {
	private function __construct(){}

	public static function custom(Player $player, string $title, array $elements): \Generator {
		$f = yield Await::RESOLVE;
		$player->sendForm(new CustomForm(
			$title, $elements,
			function(Player $player, CustomFormResponse $result) use ($f): void { $f($result); },
			function(Player $player) use ($f): void { $f(null); }
		));
		return yield Await::ONCE;
	}

	public static function menu(Player $player, string $title, string $text, array $options): \Generator {
		$f = yield Await::RESOLVE;
		$player->sendForm(new MenuForm(
			$title, $text, $options,
			function(Player $player, int $selectedOption) use ($f): void { $f($selectedOption); },
			function(Player $player) use ($f): void { $f(null); }
		));
		return yield Await::ONCE;
	}

	public static function modal(Player $player, string $title, string $text, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no"): \Generator {
		$f = yield Await::RESOLVE;
		$player->sendForm(new ModalForm(
			$title, $text,
			function(Player $player, bool $choice) use ($f): void { $f($choice); },
			$yesButtonText, $noButtonText
		));
		return yield Await::ONCE;
	}

	/**
	 * @param string|null $path URL or path
	 * @return FormIcon|null
	 */
	#[Pure] public static function formIcon(?string $path): ?FormIcon {
		if($path === null) return null;
		return new FormIcon($path, filter_var($path, FILTER_VALIDATE_URL) !== false ? FormIcon::IMAGE_TYPE_URL : FormIcon::IMAGE_TYPE_PATH);
	}
}