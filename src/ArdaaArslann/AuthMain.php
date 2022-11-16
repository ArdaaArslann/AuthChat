<?php

namespace ArdaaArslann;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class AuthMain extends PluginBase implements Listener{

	public function onEnable():void{
	$this->getLogger()->info("§2AuthChat is Enabled");
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->saveResource("config.yml");
	$this->config = $this->Config("config");
	}

	public function onDisable():void{
	$this->getLogger()->info("§4AuthChat is Disabled");
	}

	public function Config(string $string):Config{
		return new Config($this->getDataFolder().$string.".yml", Config::YAML);
	}

	public function onChat(PlayerChatEvent $event):void{
		$g = $event->getPlayer();
		$message = $event->getMessage();
		$newmessage = substr($message, 1);
		if(substr($message, 0, 1) != $this->config->get("chat-symbol")) return;
		if(!$g->hasPermission($this->config->get("permission")) and !$this->getServer()->isOp($g->getName())) return;
		foreach($this->getServer()->getOnlinePlayers() as $player){
		if($player->hasPermission($this->config->get("permission")) or $this->getServer()->isOp($player->getName())){
		$player->sendMessage(str_replace(["{player}", "{message}"], [$g->getName(), $newmessage], $this->config->get("format")));
		}
		}
		$this->getLogger()->info(str_replace(["{player}", "{message}"], [$g->getName(), $newmessage], $this->config->get("format")));
		$event->cancel();
	}
}
