<?php

namespace dohyeon0608\simplemotd;

use dohyeon0608\simplemotd\commands\MotdCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class DohyeonMotd extends PluginBase  {

	private static string $motd = "";
	private Config $config;

	public static string $prefix = "§l§b[MOTD] §r";

	public static function getMotd() : string {
		return self::$prefix . "현재 motd 내용은 다음과 같습니다:\n".self::$motd;
	}

	public static function setMotd(string $motd) : string {
		self::$motd = $motd;
		Server::getInstance()->getNetwork()->setName($motd);
		return self::$prefix . "현재 motd 내용을 다음으로 설정하였습니다:\n".$motd;
	}

	public function onEnable()
	{
		// Load Config
		@mkdir( $this->getDataFolder() );
		$default = [];
		$this->config = new Config( $this->getDataFolder()."data.json", Config::JSON, $default);

		// Set Motd
		$this->setMotd($this->config->get("motd", $this->getServer()->getMotd()));

		// Register Command
		$this->getServer()->getCommandMap()->register(MotdCommand::$name, new MotdCommand($this));
		$motdCommand = $this->getCommand(MotdCommand::$name);
		$motdCommand->setDescription("서버 motd 설정");
		$motdCommand->setUsage("motd <get/set> <내용>");
	}

	public function onDisable()
	{
		// Save Config
		$this->config->set("motd", self::$motd);
		$this->config->save();
	}
}
