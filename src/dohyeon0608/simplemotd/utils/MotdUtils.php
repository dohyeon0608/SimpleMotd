<?php

use pocketmine\command\CommandSender;

class MotdUtils
{
	public static function dontHavePermission(CommandSender $player) {
		$player->sendMessage(\dohyeon0608\simplemotd\DohyeonMotd::$prefix . "당신은 권한이 없습니다.");
	}
}