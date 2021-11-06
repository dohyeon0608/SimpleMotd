<?php

namespace dohyeon0608\simplemotd\commands;

use dohyeon0608\simplemotd\DohyeonMotd;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use MotdUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;


class MotdCommand extends PluginCommand
{
	public static string $name = "motd";
	private static string $motdUIname = "§l서버 motd 관리";
	public static string $getPermission = "dohyeonmotd.getmotd";
	public static string $setPermission = "dohyeonmotd.setmotd";

	public function __construct(Plugin $owner)
	{
		parent::__construct(self::$name, $owner);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
	{
		if (sizeof($args) != 0) {
			switch ($args[0]) {
				case "get":
					if(!$sender->hasPermission(self::$getPermission)) MotdUtils::dontHavePermission($sender);
					$sender->sendMessage(DohyeonMotd::getMotd());
					break;
				case "set":
					if(!$sender->hasPermission(self::$setPermission)) MotdUtils::dontHavePermission($sender);
					if (sizeof($args) > 1) {
						unset($args[0]);
						$sender->sendMessage(DohyeonMotd::setMotd(implode(" ", $args)));
					} else return false;
					break;
			}
		} else {
			if($sender instanceof Player) {
				if(!($sender->hasPermission(self::$getPermission)) && $sender->hasPermission(self::$setPermission)) MotdUtils::dontHavePermission($sender);
				$form = new SimpleForm(function (Player $player, int $data = null) {
					$result = $data;
					if($result === null) return true;
					switch ($result) {
						case 0:
							if(!$player->hasPermission(self::$getPermission)) MotdUtils::dontHavePermission($player);
							$player->sendMessage(DohyeonMotd::getMotd());
							break;
						case 1:
							if(!$player->hasPermission(self::$setPermission)) MotdUtils::dontHavePermission($player);
							$form = new CustomForm(function (Player $player, array $data = null) {
								if($data === null) return true;
								$player->sendMessage(DohyeonMotd::setMotd(implode(" ", $data)));
								return true;
							});
							$form->setTitle(self::$motdUIname);
							$form->addInput("원하는 내용을 입력해주세요!");
							$player->sendForm($form);
							break;
					}
					return true;
				});
				$form->setTitle(self::$motdUIname);
				$form->setContent("원하시는 기능을 선택해주세요!");
				$form->addButton("§lmotd 확인\n§r현재 설정된 motd 내용을 확인합니다.");
				$form->addButton("§lmotd 설정\n§rmotd 내용을 설정합니다!");
				$sender->sendForm($form);
			} else {
				$sender->sendMessage("§c구문이 올바르지 않습니다!: /motd <get|set> <내용>");
			}
		}
		return true;
	}

}