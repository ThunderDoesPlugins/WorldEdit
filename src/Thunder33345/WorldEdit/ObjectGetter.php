<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit;

use pocketmine\Server;
use Thunder33345\WorldEdit\Builder;
use Thunder33345\WorldEdit\Commands\CommandManager;

trait ObjectGetter
{
	private function getServer()
	{
		return Server::getInstance();
	}

	private function getLoader()
	{
		echo "[trait] called getLoader\n";
		return Loader::getInstance();
	}

	private function getCommandManager()
	{
		echo "[trait] called getCommandManager\n";
		return CommandManager::getInstance();
	}

	private function getCommandHandler()
	{
		echo "[trait] called getCommandHandler\n";
		return CommandHandler::getInstance();
	}
	private function getBuilder(){
		echo "[trait] called getBuilder\n";
		return Builder::getInstance();
	}
}