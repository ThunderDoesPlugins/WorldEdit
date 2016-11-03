<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit\Utilities;

class Cabinet implements \ArrayAccess
{
  /** @var File[] */
  private $drawer = [];

  public function fileExist($name)
  {
    return isset($this->drawer[$name]);
  }

  public function offsetExists($offset)
  {
    return isset($this->drawer[$offset]);
  }

  /**
   * @param string $offset
   * @return File|false
   */
  public function offsetGet($offset)
  {
    if (!empty($offset)) {
      if (!is_string($offset)) return false;
      if (!isset($this->drawer[$offset])) {
        $this->drawer[$offset] = new File();
      }

      return $this->drawer[$offset];
    } else {
      return false;
    }
  }

  public function offsetSet($offset, $value)
  {
    //if ($offset) {$this->storage[$offset] = $value;} else {$this->storage[] = $value;}
    return false;
  }

  public function offsetUnset($offset)
  {
    unset($this->drawer[$offset]);
  }

  public function getCabinet()
  {
    return $this->drawer;
  }
}