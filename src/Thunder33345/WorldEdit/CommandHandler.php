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
  const prefix = TextFormat::LIGHT_PURPLE . "W" . TextFormat::DARK_GREEN . "E";
  const prefixOk = TextFormat::GREEN . self::prefix . TextFormat::GREEN . ">>" . TextFormat::WHITE;
  const prefixInfo = TextFormat::AQUA . "(I)" . self::prefix . TextFormat::AQUA . ">>" . TextFormat::WHITE;
  const prefixNotice = TextFormat::YELLOW . "(N)" . self::prefix . TextFormat::YELLOW . ">>" . TextFormat::WHITE;
  const prefixError = TextFormat::RED . "(E)" . self::prefix . TextFormat::RED . ">>" . TextFormat::WHITE;
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
          $log[] = self::prefixNotice . " Trying to load world {$lvn}.";
          if ($this->getServer()->loadLevel($lvn) AND $lv = $this->getServer()->getLevelByName($lvn) AND $lv instanceof Level)
            $log[] = self::prefixNotice . " loaded world {$lvn}";
          elseif ($sender instanceof Player) $log[] = self::prefixError . " Invalid level ($lvn) falling back using your current position.";
          else {
            $log[] = self::prefixError . " Invalid level ($lvn).";
            $this->sendMassages($sender, '', $log);

            return;
          }
        }
        if ($lv instanceof Level) {
          $useLoc = false;
          $loc = new Position($x, $y, $z, $lv);
        }
      }
    } elseif (isset($args[0]) OR $sender instanceof ConsoleCommandSender) {
      $log[] = self::prefixNotice . " Usage /pos <x> <y> <x> <world>";
      if ($sender instanceof ConsoleCommandSender) {
        $this->sendMassages($sender, '', $log);

        return;
      } else $log[] = self::prefixNotice . " Incomplete parameter falling back using your current position.";
    }

    if ($useLoc AND $sender instanceof Player) {
      if ($sender instanceof Player) {
        $loc = $sender->getPosition();
      }
    }

    if (isset($loc)) {
      $vec = $loc->round();
      $loc->setComponents($vec->x, $vec->y, $vec->z);
      $name = $sender->getName();
      if ($on == 1) {
        $log[] = self::prefixInfo . " Setting your #1 position to X: {$loc->x}, Y: {$loc->y}, Z: {$loc->z} at World : {$loc->getLevel()->getName()}";
        $this->cabinet[$name]->setPos1($loc);
      } elseif ($on == 2) {
        $log[] = self::prefixInfo . " Setting your #2 position to X: {$loc->x}, Y: {$loc->y}, Z: {$loc->z} at World : {$loc->getLevel()->getName()}";
        $this->cabinet[$name]->setPos2($loc);
      }
      if (
        $this->cabinet[$name]->getPos1() instanceof Position AND $this->cabinet[$name]->getPos2() instanceof Position
      ) {
        $log[] = self::prefixInfo . " Selected " .
          $this->getBuilder()->count($this->cabinet[$name]->getPos1(), $this->cabinet[$name]->getPos2()) . " Blocks.";
      }
    }
    $this->sendMassages($sender, '', $log);
  }

  public function onRequestPos(CommandSender $sender, $commandLabel, array $args)
  {
    if (!$sender->hasPermission($this->getCommandManager()->getAllCommands()['pos'][4][0])) {
      $sender->sendMessage(self::prefixError . self::msg_noPerm);

      return;
    }
    if (isset($args[0])) {
      if (!$sender->hasPermission($this->getCommandManager()->getAllCommands()['pos'][4][1])) {
        $sender->sendMessage(self::prefixError . self::msg_noPerm);

        return;
      }
      $user = $args[0];
    } else $user = $sender->getName();

    if (isset($this->cabinet[$user])) {
      $userC = $this->cabinet[$user];
      $msg = self::prefixOk . "Position for user $user:\n";
      if ($userC->getPos1() instanceof Position) {
        $loc = $userC->getPos1();
        $msg .= self::prefixInfo . "Pos1: X:" . $loc->getX() . " Y:" . $loc->y . " Z:" . $loc->z . " World:" . $loc->level->getName() . "\n";
      } else $msg .= self::prefixInfo . "Pos1: [Not Set]\n";
      if ($userC->getPos2() instanceof Position) {
        $loc = $userC->getPos2();
        $msg .= self::prefixInfo . "Pos2: X:" . $loc->getX() . " Y:" . $loc->y . " Z:" . $loc->z . " World:" . $loc->level->getName() . "\n";
      } else $msg .= self::prefixInfo . "Pos2: [Not Set]\n";
      $sender->sendMessage($msg);
    } else {
      $sender->sendMessage(self::prefixInfo . " The specified user have not set any pos.");
    }
  }

  public function onSet(CommandSender $sender, $commandLabel, array $args)
  {
    if (!$sender->hasPermission($this->getCommandManager()->getAllCommands()['set'][4])) {
      $sender->sendMessage(self::prefixError . self::msg_noPerm);

      return;
    }
    $name = $sender->getName();
    $rt = [];
    if (
      !$this->cabinet->fileExist($name) AND
      !$this->cabinet[$name]->getPos1() instanceof Position AND !$this->cabinet[$name]->getPos2() instanceof Position
    ) $rt[] = (self::prefixInfo . " Please make a selection first.");
    if ($this->cabinet[$name]->getPos1() instanceof Position AND $this->cabinet[$name]->getPos1()->getLevel()->getName() !== $this->cabinet[$name]->getPos2()->getLevel()->getName())
      $rt[] = (self::prefixInfo . " Please make the selection in the same world.");

    if (isset($args[0]))
      $id = $args[0];
    else {
      $rt[] = (self::prefixError . " Please provide ID.");
    }

    if (count($rt) > 0) {
      $this->sendMassages($sender, '', $rt);

      return;
    }

    if (isset($args[1]))
      $meta = $args[1];
    else $meta = null;

    if (isset($args[2]))
      $update = $args[2];
    else $update = false;
    $this->getBuilder()->buildBox(
      $this->cabinet[$name]->getPos1()->getLevel(),
      $this->cabinet[$name]->getPos1(), $this->cabinet[$name]->getPos2(),
      $id, $meta, $update, $ret
    );
    $sender->sendMessage(self::prefixOk . " Took $ret[1]s to set $ret[0] blocks to " . $id);
  }

  public function sendMassages(CommandSender $player, $prefix, array$massages)
  {
    if (count($massages) < 0) return;
    foreach ($massages as $massage) {
      $player->sendMessage($prefix . $massage);
    }
  }
}