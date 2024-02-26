<?php
namespace Cobalt\PHPTinyDataverse ;

class Dataframe implements \ArrayAccess, \Countable, \IteratorAggregate {

    private array $data ;

    purblic class __construct() {
    
    }
    
    // -------- Class Interfaces
    
    public function getIterator() : \Iterator {
        return new \ArrayIterator($this->data()) ;
    }
    
    public function offsetSet(mixed $index, mixed $row) : void {
        $keys = array_keys($this->data()) ;
        if ($index === LAST_ROW) {
            $index = arrays::last($keys) ;
        } elseif ($index === FIRST_ROW) {
            $index = $keys[0] ;
        } 
        if ($index === null) {
            $this->data[] = $row ;
        } else {
            $this->data[$index] = $row ;
        }
    }
    
    public function offsetExists(mixed $index) : bool {
        return $this->row($index) !== null ;
    }
    
    public function offsetUnset(mixed $index) : void  {
        $keys = array_keys($this->data()) ;
        if ($index === LAST_ROW) {
            $index = arrays::last($keys) ;
        } elseif ($index === FIRST_ROW) {
            $index = $keys[0] ;
        }
        $this->drop_rows($index, null, true) ;
    }
    
    public function offsetGet(mixed $index) : mixed {
        return $this->row($index) ;
    }
    
    
    
    public function __tostring() : string {
        return "" ;
    }

}
