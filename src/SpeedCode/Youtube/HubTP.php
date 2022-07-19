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
        //$this->getServer()->getPluginManager()->addPermission(new Permission($hub->getNested("Permission.Perms"), $hub->getNested("Permission.Desc"), Permission::DEFAULT_OP));

        $perm = new Config($this->getDataFolder() . "perms.yml", 2);
        //$this->getServer()->getPluginManager()->addPermission(new Permission($perm->getNested("Permission.Perms"), $perm->getNested("Permission.Desc")));
        //$this->getServer()->getLogger()->info("HubTP geladen!");
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
        #$this->getLogger()->warning("The Hubtitle is set to " . $wcfg->getNested("HubTP.msg.title"));

        /*$wcfg = new Config($this->getDataFolder() . "world.yml", 2);
        if($wcfg->exists("HubTP.World")){
            $this->getLogger()->warning("The World is " . $wcfg->getNested("HubTP.World"));
        } else {
            $wcfg->setNested("HubTP.World", "world");
        }

        if($wcfg->exists("HubTP.msg.title")){
            $this->getLogger()->warning("The Title is " . $wcfg->getNested("HubTP.msg.title"));
        } else {
            $this->getLogger()->info("the HubTITLE was set to " . $wcfg->getNested("HubTP.msg.title"));
            $wcfg->setNested("HubTP.msg.title", "no");
        }

        $wcfg->setNested("HubTP.msg.title", "yes");
        $wcfg->setNested("HubTP.msg.Prefix", "&c&lHub&f&lTP&r &8» &7");
        $wcfg->setNested("HubTP.msg.Teleported", "&7You have been Teleported!");
        $wcfg->save();*/
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
               // $this->getLogger()->info("[LOG] " . $settings->getNested("Console.Log.Disabled"));
                $this->sendLog($settings->getNested("Console.Log.Disabled"));
        }
        /*if($settings->getNested("Console.Type") === "info"){
            $this->getServer()->getLogger()->info($settings->getNested("Console.Log.Disabled"));
        } elseif($settings->getNested("Console.Type") === "warning"){
            $this->getServer()->getLogger()->warning($settings->getNested("Console.Log.Disabled"));
        } elseif($settings->getNested("Console.Type")){

        }*/
    }

    public function sendLog(string $log){
        $this->getServer()->getLogger()->log(1, $log);
    }


}