### HubTP
[![logo](https://github.com/InvalidNoah/HubTP/blob/main/assets/logos/HubTP.gif?raw=false)

a Hub Teleport Plugin made out of my head for PocketMine API 4

### CMDPerms
you can change that in the common files **command.yml** and **perms.yml** see the [Configs](https://poggit.pmmp.io/p/HubTP/1#rdesc-section-configs) Tab :D


### Configs
*world.yml*
```yaml
# Config for the HubTP World!

HubTP:
  World: world # set the worldy ou want!
  Type: "world"
  msg:
    title: "yes" # to showup a title if 'no' no title only a chat message
    Prefix: '&c&lHub&f&lTP&r &8» &7' # Prefix
    Teleported: '&7You have been Teleported!' # the Message at Teleport
```
*server.yml*

```yaml
# Config for /hub in a server

HubTP:
  Type: "transfer" # 2 different types are available ['waterdog'|'transfer']
  transfer:
    ip: "geo.hivebedrock.network"
    port: 19132
    reason: 'Transfered by doing /hub'
  waterdog:
    servername: "lobby"
    message: "You where transfered to {servername} from HubTP :D"
  ```
  
*command.yml*

```yaml
Command:
  Desc: Teleports you to the Mainhub from the Server! # description while type /hub
  Name: hub # command name
  Alias: lobby # alias of command
```

*perms.yml*

```yaml
Permission:
  MSG: '{prefix}&cYou dont have the correct permission!' # if player has no permission to use /hub
  Perms: hubtp.use # the Permission
  Desc: Allows the player to use /hub # Permission description
```

### about my Skills
I'm new to programming and I hope my plugins will do the job ^^

### Contact me
You can contact me via Discord **EyNoah#0683**
or visit my [Website](https://eynoah.de)
