<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class Builder
{
  use ObjectGetter;
  static private $instance;

  public function __construct()
  {
    self::$instance = $this;
  }

  static public function getInstance()
  {
    return self::$instance;
  }

  /**
   * @param Vector3 $pos1
   * @param Vector3 $pos2
   * @return Position[]
   */
  public function neutralize(Vector3 $pos1, Vector3 $pos2)
  {
    if ($pos1 instanceof Position) {
      $ret[0] = new Position(min($pos1->x, $pos2->x), min($pos1->y, $pos2->y), min($pos1->z, $pos2->z), $pos1->getLevel());
    } else {
      $ret[0] = new Position(min($pos1->x, $pos2->x), min($pos1->y, $pos2->y), min($pos1->z, $pos2->z));
    }
    if ($pos2 instanceof Position) {
      $ret[1] = new Position(max($pos1->x, $pos2->x), max($pos1->y, $pos2->y), max($pos1->z, $pos2->z), $pos2->getLevel());
    } else {
      $ret[1] = new Position(max($pos1->x, $pos2->x), max($pos1->y, $pos2->y), max($pos1->z, $pos2->z));
    }

    return $ret;
  }

  public function count(Vector3 $pos1, Vector3 $pos2)
  {
    $nt = $this->neutralize($pos1, $pos2);
    $pos1 = $nt[0];
    $pos2 = $nt[1];

    return ($pos2->x - $pos1->x + 1) * ($pos2->y - $pos1->y + 1) * ($pos2->z - $pos1->z + 1);
  }

  public function buildBox(Level $level, Position $pos1, Position $pos2, int $id, $meta, $update = false, &$output = null)
  {

    $nt = $this->neutralize($pos1, $pos2);
    $pos1 = $nt[0];
    $pos2 = $nt[1];
    $block = new Block($id, $meta);
    $count = 0;
    $time = microtime(true);
    for ($y = $pos1->y; $y <= $pos2->y; ++$y) {
      if ($y < 0 OR $y > 128) break;
      for ($x = $pos1->x; $x <= $pos2->x; ++$x) {
        for ($z = $pos1->z; $z <= $pos2->z; ++$z) {
          $level->setBlock(new Vector3($x, $y, $z), $block, false, $update);
          $count++;
        }
      }
    }
    $time = round(microtime(true) - $time, 3);
    $output = [
      $count,
      $time
    ];

    return true;
  }
}