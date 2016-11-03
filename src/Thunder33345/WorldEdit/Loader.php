<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit;

use pocketmine\plugin\PluginBase;
use Thunder33345\WorldEdit\Commands\CommandManager;

class Loader extends PluginBase
{
  //use ObjectGetter;
  /** @var Loader */
  static private $instance;

  private function __clone()
  {
  }

  public function onLoad()
  {
    self::$instance = $this;
  }

  public function onEnable()
  {
    echo "[main] Loader Enabled\n";
    new CommandManager();
    new CommandHandler();
    new Builder();
  }

  public function onDisable()
  {

  }

  static public function getInstance()
  {
    echo "[loader] called getInstance\n";

    return static::$instance;
  }
}