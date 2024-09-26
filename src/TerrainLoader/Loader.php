<?php

namespace TerrainLoader;

use pocketmine\plugin\PluginBase;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Loader extends PluginBase {
	public function setTile($x, $y, $z, $blockId, $world) {
		$block = Block::get($blockId);
        $position = new Vector3($x, $y, $z);
        $block = $block->getId() === Block::STILL_WATER ? Block::get(Block::WATER) : $block;
        $world->setBlock($position, $block);
    }
	public function Loader($p, $TerrainFile) {
		$world = $p->getLevel();
		$TerrainFileContent = file_get_contents($TerrainFile . ".terra");
		$TerrainLines = explode("\n", $TerrainFileContent);
		$TerrainData = [];

		foreach ($TerrainLines as $line) {
			$lineValues = explode(" ", $line);
			$TerrainData[] = $lineValues;
		}

		$TerrainLength = count($TerrainData);

		for ($i = 0; $i < $TerrainLength; $i++) {
			$this->setTile((int)$TerrainData[$i][1], (int)$TerrainData[$i][2], (int)$TerrainData[$i][3], (int)$TerrainData[$i][0], $world);
		}
	}
	public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
		if ($cmd->getName() == ".load") {
			if (! isset($args[0])) {
				$args[0] = "terrain";
			}
			$this->Loader($p, $args[0]);
		}
	}
}
	
