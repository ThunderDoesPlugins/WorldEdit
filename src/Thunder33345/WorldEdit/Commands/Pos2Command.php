<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit\Commands;

use pocketmine\command\CommandSender;
use Thunder33345\WorldEdit\Loader;
use Thunder33345\WorldEdit\ObjectGetter;

class Pos2Command extends BaseCommand
{
  use ObjectGetter;

  public function __construct($name, $description, $usageMessage, $aliases)
  {
    parent::__construct($this->getLoader(), $name, $description, $usageMessage, $aliases);
  }

  public function onCommand(CommandSender $sender, $commandLabel, array $args)
  {
    $this->getCommandHandler()->onSetPos(2,$sender, $commandLabel, $args);
  }
}