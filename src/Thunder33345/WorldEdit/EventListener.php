<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit;
use pocketmine\event\Listener;

class EventListener implements Listener{
	use ObjectGetter;
	public function __construct()
	{
		$this->getServer()->getPluginManager()->registerEvents($this,$this->getLoader());
	}

}