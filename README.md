<p align="center">
    <a href="https://github.com/Vecnavium/Slapper"><img src="https://github.com/Vecnavium/Slapper/blob/stable/icon.png"></img></a><br>
    <b>The new home of Slapper, the NPC plugin for PocketMine-MP.</b>
</p>
<p align="center">
Currently maintained by: KressentHosting  

<p align="center">Website: https://kressenthosting.com
	

<p align="center">
    <img alt="GitHubrelease" src="https://img.shields.io/github/v/release/Vecnavium/Slapper?label=release&sort=semver">
      <img alt="Stars" src= "https://img.shields.io/github/stars/Vecnavium/Slapper?style=for-the-badge">
    <img href="https://discord.gg/6M9tGyWPjr"><img src="https://img.shields.io/discord/837701868649709568?label=discord&color=7289DA&logo=discord" alt="Discord" /></a>
</p>

## Addons

Official addons:
- [SlapperRotation](https://github.com/Vecnavium/SlapperRotation)
- [SlapperCooldown](https://github.com/Vecnavium/SlapperCooldown)
- [SlapBack](https://github.com/vecnavium-pm-pl/SlapBack)
- [SlapperCache](https://github.com/Vecnavium/SlapperCache)
- [SlapperPlus](https://github.com/Vecnavium/SlapperPlus)


# Basic documentation

## Commands:

- /slapper [args...]
- /rca <player> <command> - Run command as another player! This can be used to only run the command if the player has permission.

## Main level commands:
- help: /slapper help
- spawn: /slapper spawn <type> [name]
- edit: /slapper edit [id] [args...]
- id: /slapper id
- remove: /slapper remove [id]
- version: /slapper version
- cancel: /slapper cancel
- updateall: /slapper updateall

### Edit args:
- helmet: /slapper edit <eid> helmet <id>
- chestplate: /slapper edit <eid> <id>
- leggings: /slapper edit <eid> leggings <id>
- boots: /slapper edit <eid> boots <id>
- skin: /slapper edit <eid> skin
- name: /slapper edit <eid> name <name>
- addcommand: /slapper edit <eid> addcommand <command>
- delcommand: /slapper edit <eid> delcommand <command>
- listcommands: /slapper edit <eid> listcommands
- update: /slapper edit <eid> update
- block: /slapper edit <eid> block <id>
- tphere: /slapper edit <eid> tphere
- tpto: /slapper edit <eid> tpto
- menuname: /slapper edit <eid> menuname <name/remove>
	
	
### Aliases for edit args
helmet: helm, helmet, head, hat, cap
chestplate: chest, shirt, chestplate
leggings: pants, legs, leggings
boots: feet, boots, shoes
item: hand, item, holding, arm, held
skin: setskin, changeskin, editskin, skin
name: name, customname
menuname: listname, nameonlist, menuname
namevisible: namevisible, customnamevisible, tagvisible, name_visible, custom_name_visible, tag_visible
addcommand: addc, adduced, add command
delcommand: delc, delcmd, delcommand, remove command
listcommands: listcommands, listcmds, listcs
fix: update, fix, migrate
block: block, tile, blockid, tileid
tphere: teleporthere, tphere, movehere, bringer
tpto: teleportto, tpto, goto, teleport, tp
