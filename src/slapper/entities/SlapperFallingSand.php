<?php

declare(strict_types=1);

namespace slapper\entities;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\object\FallingBlock;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;

class SlapperFallingSand extends SlapperEntity {

    const TYPE_ID = EntityLegacyIds::FALLING_BLOCK;
    const HEIGHT = 0.98;

    protected Block $block;

    public function initEntity(CompoundTag $nbt): void{
        parent::initEntity($nbt);

        $blockFactory = BlockFactory::getInstance();
        if(($blockIdTag = $nbt->getTag("TileID")) === null){
        	$nbt->setTag("TileID", new IntTag(BlockLegacyIds::SAND));
            $this->block = FallingBlock::parseBlockNBT($blockFactory, $nbt);
        }else{
            $this->block = $blockFactory->get($blockIdTag->getValue(), 0);
        }
    }

    protected function syncNetworkData(EntityMetadataCollection $properties) : void{
        parent::syncNetworkData($properties);

        $properties->setInt(EntityMetadataProperties::VARIANT, RuntimeBlockMapping::getInstance()->toRuntimeId($this->block->getFullId()));
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setByte("TileID", $this->block->getId());
        $nbt->setByte("Data", $this->block->getMeta());

        return $nbt;
    }

    public function setBlock(Block $block): void{
        $this->block = $block;
        $this->networkPropertiesDirty = true;
    }
}
