<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit\Utilities;

use pocketmine\level\Position;

class File
{
  private $pages = [];

  public function setPos1(Position $position)
  {
    $this->pages['pos1'] = $position;
  }

  public function setPos2(Position $position)
  {
    $this->pages['pos2'] = $position;
  }

  public function getPos1()
  {
    if (isset($this->pages['pos1'])) {
      return $this->pages['pos1'];
    } else return false;
  }

  public function getPos2()
  {
    if (isset($this->pages['pos2'])) {
      return $this->pages['pos2'];
    } else return false;
  }
}