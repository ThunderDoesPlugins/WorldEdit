<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Thunder33345\WorldEdit\Utilities\Cabinet;

class CommandHandler
{
  use ObjectGetter;
  const prefix = TextFormat::LIGHT_PURPLE . "WorldEdit";
  const prefixInfo = TextFormat::AQUA . "[" . self::prefix . TextFormat::AQUA . "]" . TextFormat::WHITE;
  const prefixNotice = TextFormat::YELLOW . "[" . self::prefix . TextFormat::YELLOW . "]" . TextFormat::WHITE;
  const prefixOk = TextFormat::GREEN . "[" . self::prefix . TextFormat::GREEN . "]" . TextFormat::WHITE;
  const prefixError = TextFormat::RED . "[" . self::prefix . TextFormat::RED . "]" . TextFormat::WHITE;
  const msg_onlyPlayer = "This function can only used by a player.";
  const msg_noPerm = "Insufficient permission to run this command.";
  private static $instance = null;
  private $cabinet;

  public function __construct()
  {
    self::$instance = $this;
    $this->cabinet = new Cabinet();
  }

  static public function getInstance()
  {
    return self::$instance;
  }

  public function onSetPos(int $on, CommandSender $sender, $commandLabel, array $args)
  {

    if ($sender->hasPermission($this->getCommandManager()->getAllCommands()['pos1'][4]) AND !$sender instanceof ConsoleCommandSender) {
      $sender->sendMessage(self::prefixError . self::msg_noPerm);

      return;
    }
    $log = [];
    $useLoc = true;
    if (isset($args[0]) AND isset($args[1]) AND isset($args[2]) AND isset($args[3])) {
      //if (is_numeric($args[0])) {
      $x = $args[0];
      $y = $args[1];
      $z = $args[2];
      $lvn = $args[3];
      //}
      /* fixme buggy and it is possible for the world name to be numeric
      elseif (is_string($args[0]) AND is_numeric($args[3])) {
        $lvn = $args[0];
        $x = $args[1];
        $y = $args[2];
        $z = $args[3];
        $log[] = self::prefixInfo . "(info) using world,z,y,x as parameter.";
      }
      */
      if (isset($lvn) AND isset($x) AND isset($y) AND isset($z)) {
        $lv = $this->getServer()->getLevelByName($lvn);
        if (!$lv instanceof Level) {
          $log[] = self::prefixNotice . "(notice) Trying to load world {$lvn}.";
          if ($this->getServer()->loadLevel($lvn) AND $lv = $this->getServer()->getLevelByName($lvn) AND $lv instanceof Level)
            $log[] = self::prefixNotice . "(notice) loaded world {$lvn}";
          else $log[] = self::prefixError . "(error) Invalid level ($lvn) falling back using your current position.";
        }
        if ($lv instanceof Level) {
          $useLoc = false;
          $loc = new Position($x, $y, $z, $lv);
        }
      } elseif (isset($args[0]) or isset($args[1]) OR isset($args[2])) {
        if (isset($args[0]) AND isset($args[1]) AND isset($args[2]) AND !isset($args[3]))
          $log[] = self::prefixNotice . "(Hint) Please supply the world name.";
        $log[] = self::prefixNotice . "(notice) Incomplete parameter falling back using your current position.";
      }
    }

    if ($useLoc) {
      if (!$sender instanceof Player) $log[] = self::prefixError . "(error) " . self::msg_onlyPlayer;
      if ($sender instanceof Player) {
        $loc = $sender->getPosition();
      }
    }

    if (isset($loc)) {
      $vec = $loc->round();
      $loc->setComponents($vec->x, $vec->y, $vec->z);
      if ($on == 1) {
        $log[] = self::prefixInfo . "(info) Setting your #1 position to X: {$loc->x}, Y: {$loc->y}, Z: {$loc->z} at World : {$loc->getLevel()->getName()}";
        $this->cabinet[$sender->getName()]->setPos1($loc);
      } elseif ($on == 2) {
        $log[] = self::prefixInfo . "(info) Setting your #2 position to X: {$loc->x}, Y: {$loc->y}, Z: {$loc->z} at World : {$loc->getLevel()->getName()}";
        $this->cabinet[$sender->getName()]->setPos2($loc);
      }
    }
    $this->sendMassages($sender, '', $log);
  }

  public function onRequestPos(CommandSender $sender, $commandLabel, array $args)
  {
    if (isset($args[0])) {
      $user = $args[0];
    } else $user = $sender->getName();

    if (isset($this->cabinet[$user])) {
      //todo send pos
    } else {
      //todo send not found
    }
  }

  public function sendMassages(CommandSender $player, $prefix, array$massages)
  {
    if (count($massages) < 0) return;
    foreach ($massages as $massage) {
      $player->sendMessage($prefix . $massage);
    }
  }
}