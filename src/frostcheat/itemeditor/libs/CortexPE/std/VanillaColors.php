<?php


namespace CortexPE\std;


use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\utils\RegistryTrait;
use pocketmine\utils\TextFormat;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 *
 * @see RegistryTrait::_generateMethodAnnotations()
 *
 * @method static MCPEColor BLACK()
 * @method static MCPEColor DARK_BLUE()
 * @method static MCPEColor DARK_GREEN()
 * @method static MCPEColor DARK_AQUA()
 * @method static MCPEColor DARK_RED()
 * @method static MCPEColor DARK_PURPLE()
 * @method static MCPEColor GOLD()
 * @method static MCPEColor GRAY()
 * @method static MCPEColor DARK_GRAY()
 * @method static MCPEColor BLUE()
 * @method static MCPEColor GREEN()
 * @method static MCPEColor AQUA()
 * @method static MCPEColor RED()
 * @method static MCPEColor LIGHT_PURPLE()
 * @method static MCPEColor YELLOW()
 * @method static MCPEColor WHITE()
 */
final class VanillaColors {
	// todo: probably another class for text formatting? (e.g. italics, bold, etc.)
	use RegistryTrait;

	/** @var MCPEColor[] */
	private static $vanillaColors = [];

	/**
	 * @return MCPEColor[]
	 */
	public static function getAll(): array {
		return self::_registryGetAll();
	}

	protected static function setup(): void {
		self::register(new MCPEColor("black", TextFormat::BLACK, Color::fromRGB(0x000000), DyeColor::BLACK()));
		self::register(new MCPEColor("dark_blue", TextFormat::DARK_BLUE, Color::fromRGB(0x0000AA), DyeColor::BLUE()));
		self::register(new MCPEColor("dark_green", TextFormat::DARK_GREEN, Color::fromRGB(0x00AA00), DyeColor::GREEN()));
		self::register(new MCPEColor("dark_aqua", TextFormat::DARK_AQUA, Color::fromRGB(0x00AAAA), DyeColor::BLUE()));
		self::register(new MCPEColor("dark_red", TextFormat::DARK_RED, Color::fromRGB(0xAA0000), DyeColor::RED()));
		self::register(new MCPEColor("dark_purple", TextFormat::DARK_PURPLE, Color::fromRGB(0xAA00AA), DyeColor::PURPLE()));
		self::register(new MCPEColor("gold", TextFormat::GOLD, Color::fromRGB(0xFFAA00), DyeColor::ORANGE()));
		self::register(new MCPEColor("gray", TextFormat::GRAY, Color::fromRGB(0xAAAAAA), DyeColor::LIGHT_GRAY()));
		self::register(new MCPEColor("dark_gray", TextFormat::DARK_GRAY, Color::fromRGB(0x555555), DyeColor::GRAY()));
		self::register(new MCPEColor("blue", TextFormat::BLUE, Color::fromRGB(0x5555FF), DyeColor::LIGHT_BLUE()));
		self::register(new MCPEColor("green", TextFormat::GREEN, Color::fromRGB(0x55FF55), DyeColor::LIME()));
		self::register(new MCPEColor("aqua", TextFormat::AQUA, Color::fromRGB(0x55FFFF), DyeColor::CYAN()));
		self::register(new MCPEColor("red", TextFormat::RED, Color::fromRGB(0xFF5555), DyeColor::RED()));
		self::register(new MCPEColor("light_purple", TextFormat::LIGHT_PURPLE, Color::fromRGB(0xFF55FF), DyeColor::MAGENTA()));
		self::register(new MCPEColor("yellow", TextFormat::YELLOW, Color::fromRGB(0xFFFF55), DyeColor::YELLOW()));
		self::register(new MCPEColor("white", TextFormat::WHITE, Color::fromRGB(0xFFFFFF), DyeColor::WHITE()));
	}

	protected static function register(MCPEColor $color): void {
		self::_registryRegister($color->getName(), $color);
		assert(!isset(self::$vanillaColors[$color->getName()]));
		self::$vanillaColors[$color->getName()] = $color;
	}
}