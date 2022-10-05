<?php

declare(strict_types=1);

namespace slapper\entities;

use minicore\api\PlayerListAPI;
use minicore\CustomPlayer;
use minicore\MiniCore;
use pocketmine\entity\Human;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use slapper\SlapperTrait;
use slapper\SlapperInterface;


class SlapperHuman extends Human implements SlapperInterface{
    use SlapperTrait;

    protected string $menuName;
	protected string $serverName;
    protected ?TaskHandler $currentTask = null;

    private CompoundTag $namedTagHack;

    public function initEntity(CompoundTag $nbt): void{
		parent::initEntity($nbt);
        $this->namedTagHack = $nbt;
		$this->menuName = $nbt->getString('MenuName', '');
		$this->setServerName($nbt->getString('ServerName', ''));
        if(($commandsTag = $nbt->getTag('Commands')) instanceof ListTag or $commandsTag instanceof CompoundTag){
            /** @var StringTag $stringTag */
            foreach($commandsTag as $stringTag){
                $this->commands[$stringTag->getValue()] = true;
            }
        }
        $this->version = $nbt->getString('SlapperVersion', '');
		$this->setNameTagAlwaysVisible(true);
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt = $nbt->merge($this->namedTagHack);
		$nbt->setString('MenuName', $this->menuName);
		$nbt->setString('ServerName', $this->serverName);
        $commandsTag = new ListTag([], NBT::TAG_String);
        $nbt->setTag('Commands', $commandsTag);
        foreach($this->commands as $command => $bool){
            $commandsTag->push(new StringTag($command));
        }
        $nbt->setString('SlapperVersion', $this->version);
        return $nbt;
    }

	public function setServerName(string $serverName): void{
        if ($serverName !== "" && !isset($this->currentTask)) {
            //update name automatically, this could be probably done better
            $this->currentTask = MiniCore::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(function () {
                $this->sendData($this->getViewers());
            }), 10 * 20, 10 * 20);
        } else if ($serverName === "" && isset($this->currentTask)) {
            $this->currentTask->cancel();
            $this->currentTask = null;
        }

		$this->serverName = $serverName;
	}

    public function setMenuName(string $menuName): void{
        $this->menuName = $menuName;
    }

    public function getNameName(): string{
        return $this->menuName;
    }

    /**
     * @param Player[]|null $targets
     * @param MetadataProperty[] $data
     */
    public function sendData(?array $targets, ?array $data = null): void{
        $targets = $targets ?? $this->hasSpawned;
        $data = $data ?? $this->getAllNetworkData();
        if(!isset($data[EntityMetadataProperties::NAMETAG])){
            parent::sendData($targets, $data);
            return;
        }
        $concat = $this->serverName !== '';

        foreach($targets as $p){
			/** @var CustomPlayer $p */
            $data[EntityMetadataProperties::NAMETAG] = new StringMetadataProperty(
				$this->getDisplayName($p) . ($concat ? "\n".$p->getLang()->ts("lobby.slapperLore", ["count" => PlayerListAPI::getServerCountByName($this->serverName)]) : "")
			);
            $p->getNetworkSession()->syncActorData($this, $data);
        }
    }

    protected function sendSpawnPacket(Player $player): void {
        parent::sendSpawnPacket($player);

        if ($this->menuName !== "") {
            $player->getNetworkSession()->sendDataPacket(PlayerListPacket::add([PlayerListEntry::createAdditionEntry($this->getUniqueId(), $this->getId(), $this->menuName, SkinAdapterSingleton::get()->toSkinData($this->getSkin()), '')]));
        }
    }

    //For backwards-compatibility
    public function __get(string $name) : mixed {
        if($name === 'namedtag') {
            return $this->namedTagHack;
        }
        throw new \ErrorException('Undefined property: ' . get_class($this) . "::\$" . $name);
    }

    //For backwards-compatibility
    public function __set(string $name, mixed $value) : void {
        if($name === 'namedtag') {
            if(!$value instanceof CompoundTag) {
                throw new \TypeError('Typed property ' . get_class($this) . "::\$namedtag must be " . CompoundTag::class . ', ' . gettype($value) . 'used');
            }
            $this->namedTagHack = $value;
        }
        throw new \ErrorException('Undefined property: ' . get_class($this) . "::\$" . $name);
    }

    //For backwards-compatibility
    public function __isset(string $name) : bool {
        return $name === 'namedtag';
    }

}
