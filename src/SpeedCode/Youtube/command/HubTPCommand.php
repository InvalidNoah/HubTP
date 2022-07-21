<?php

declare(strict_types=1);

namespace SpeedCode\Youtube\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use SpeedCode\Youtube\HubTP;

class HubTPCommand extends Command {

    private $plugin;

    public function __construct(HubTP $plugin){
        $this->plugin = $plugin;
        $cmd = new Config($this->plugin->getDataFolder() . "command.yml", 2);
        parent::__construct($cmd->getNested("Command.Name"), $cmd->getNested("Command.Desc"));
        $this->setAliases([$cmd->getNested("Command.Alias")]);
        $this->setLabel($cmd->getNested("Command.Desc"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $wcfg = new Config($this->plugin->getDataFolder() . "world.yml", 2);
        $perm = new Config($this->plugin->getDataFolder() . "perms.yml", 2);
        $server = new Config($this->plugin->getDataFolder() . "server.yml", 2);
        if($sender instanceof Player){
            if($sender->hasPermission($perm->getNested("Permission.Perms"))) {
                $world = $wcfg->getNested("HubTP.World");
                $tpWorld = $this->getPlugin()->getServer()->getWorldManager()->getWorldByName($world);
                $tpMsg = $wcfg->getNested("HubTP.msg.Teleported");
                $teleported = str_replace(["{prefix}", "&"], [$wcfg->getNested("HubTP.msg.Prefix", "§")], $tpMsg);
                if($wcfg->getNested("HubTP.Type") === "world"){
                    $sender->sendMessage($teleported);
                    $sender->teleport($tpWorld->getSafeSpawn());
                    if($wcfg->getNested("HubTP.msg.title") === "yes"){
                        $sender->sendTitle($tpMsg);
                    } elseif ($wcfg->getNested("HubTP.msg.title") === "no"){
                        $sender->sendMessage($tpMsg);
                    }
                } elseif($wcfg->getNested("HubTP.Type") === "server"){
                    if($server->getNested("HubTP.Type") === "transfer"){
                        $tplayer = str_replace(["{p}"], [$sender->getName()], $server->getNested("HubTP.transfer.reason"));
                        $sender->transfer($server->getNested("HubTP.transfer.ip"), $server->getNested("HubTP.transfer.port"), $tplayer);
                    } elseif($server->getNested("HubTP.Type") === "waterdog"){
                        $this->transferPlayerToHub($sender, $server->getNested("HubTP.waterdog.servername"));
                    }
                }
            } else {
                $prefix = $wcfg->getNested("HubTP.msg.Prefix");
                $prefixColor = str_replace(["&"], ["§"], $prefix);
                $permMsg = $perm->getNested("Permission.MSG");
                $tpMsg = str_replace(["&", "{prefix}", "{servername}"], ["§", $prefixColor, $permMsg], $perm->getNested("Permission.MSG"));

                $sender->sendMessage($tpMsg);
            }

        }
    }

    public function transferPlayerToHub(Player $player, string $servername){
        $pk = new TransferPacket();
        $pk->address = $servername;
        $pk->port = 0;
        $player->getNetworkSession()->sendDataPacket($pk);
        $server = new Config($this->plugin->getDataFolder() . "server.yml", 2);
        $wcfg = new Config($this->plugin->getDataFolder() . "world.yml", 2);
        $prefix = $wcfg->getNested("HubTP.msg.Prefix");
        $prefixColor = str_replace(["&"], ["§"], $prefix);
        $tpMsg = str_replace(["&", "{prefix}", "{servername}"], ["§", $prefixColor, $servername], $server->getNested("HubTP.waterdog.message"));
        $player->sendMessage($tpMsg);
    }

}
