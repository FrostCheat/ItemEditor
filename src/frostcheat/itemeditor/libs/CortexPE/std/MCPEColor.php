<?php


namespace CortexPE\std;

use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color as RGBAColor;

final class MCPEColor {

	public function __construct(
		private string $name,
		private string $fmtString,
		private RGBAColor $color,
		private DyeColor $dyeColor
	) {
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getFormatString(): string {
		return $this->fmtString;
	}

	/**
	 * @return RGBAColor
	 */
	public function getColor(): RGBAColor {
		return $this->color;
	}

	/**
	 * @return DyeColor
	 */
	public function getDyeColor(): DyeColor {
		return $this->dyeColor;
	}
}