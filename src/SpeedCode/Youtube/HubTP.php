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
use pocketmine\utils\MainLogger;
use SpeedCode\Youtube\command\HubTPCommand;

class HubTP extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("perms.yml");
        $this->saveResource("command.yml");
        $this->saveResource("world.yml");
        $this->saveResource("server.yml");
        $this->saveResource("settings.yml");

        $perm = new Config($this->getDataFolder() . "perms.yml", 2);
        $settings = new Config($this->getDataFolder() . "settings.yml", 2);
        if($settings->getNested("Console.Type") === "warning"){
            $this->getLogger()->warning($settings->getNested("Console.Log.Enabled"));
        }
        $this->getLogger()->info($settings->getNested("Console.Logger.Enabled"));



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

    public function onDisable(): void {
        $settings = new Config($this->getDataFolder() . "settings.yml", 2);
        switch ($settings->getNested("Console.Type")){
            case "info":
                $this->getServer()->getLogger()->info($settings->getNested("Console.Log.Disabled"));
                break;
            case "warning":
                $this->getServer()->getLogger()->warning($settings->getNested("Console.Log.Disabled"));
                break;
            case "error":
                $this->getServer()->getLogger()->error($settings->getNested("Console.Log.Disabled"));
                break;
            default:
                $this->getServer()->getLogger()->info($settings->getNested("Console.Log.Disabled"));
        }
    }

    public function sendLog(string $log){
        $this->getServer()->getLogger()->log(1, $log);
    }


}
