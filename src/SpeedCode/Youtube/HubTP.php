<?php

declare(strict_types=1);

namespace SpeedCode\Youtube;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use SpeedCode\Youtube\command\HubTPCommand;

class HubTP extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
      

        $this->getServer()->getLogger()->info("HubTP geladen!");

        $this->saveResource("perms.yml");
        $this->saveResource("command.yml");
        $this->saveResource("world.yml");
        $this->saveResource("server.yml");
        $wcfg = new Config($this->getDataFolder() . "world.yml", 2);
        $world = $wcfg->getNested("HubTP.World");
        $this->getServer()->getWorldManager()->loadWorld($world);
        if($this->getServer()->getWorldManager()->isWorldLoaded($world)){
            $this->getLogger()->warning("World '" . $world . "' is loaded!");
            $this->getServer()->getWorldManager()->loadWorld($world);
        } else {
            $this->getLogger()->critical("World '" . $world . "' is not loaded anyway!");
        }

        $this->getLogger()->warning("The World is " . $wcfg->getNested("HubTP.World"));
        if($wcfg->getNested("HubTP.msg.title") === "yes"){
            $this->getLogger()->warning("The Hubtitle is set to §a§lyes§r..");
        } elseif($wcfg->getNested("HubTP.msg.title") === "no"){
            $this->getLogger()->warning("The Hubtitle is set to §c§lNo§r....");
        }
        $this->getServer()->getCommandMap()->registerAll("hubtp", [
            new HubTPCommand($this)
        ]);
    }


}
