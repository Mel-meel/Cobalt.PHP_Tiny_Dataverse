<?php
namespace Cobalt\PHPTinyDataverse ;

class Dataframe implements \ArrayAccess, \Countable, \IteratorAggregate {

    private array $data ;
    /**
     * Formated data are only vertical array like. To obtain an horizontal array like, please the the getHorizontalData() method
     */
    private array $formated_data ;

    public function __construct(?array $data = null, ?array $headers = null, bool $is_vertical = false) {
        if ($data === null) {
            if (! self::empty_frames()) {
                throw new \InvalidArgumentException("Input data can not be null. A valid array must be given.") ;
            } else {
                $data = [] ;
            }
        } elseif (! self::empty_frames() and count($data) == 0 and ($headers === null or count($headers) == 0)) {
            throw new \LengthException('A Dataframe needs a minimum of one row of data.') ;
        }
        
        
        /**
         * The end of this constructor is not mine, it come from the libraiary Sqonk so i didn't touch it and let it as is.
         * @license     MIT see license.txt
         * @copyright   2019 Sqonk Pty Ltd.
         */
        if ($is_vertical) {
            # data has been supplied as a keyed set of columns, translate to rows.
            $headers = array_keys($data) ;
            $rows = [] ;
            foreach (arrays::zip(...array_values($data)) as $values) {
                $rows[] = array_combine($headers, $values) ;
            }
            $data = &$rows ;
        }
        
        $this->data = $data ;
        $this->headers = $headers ;
        if (! $this->headers and count($data) > 0) {
            $indexes = array_keys($this->data) ;
            $this->headers = array_keys($this->data[$indexes[0]]) ;
        }
    }
    
    /**
     * Interfaces's methods implementations
     */
    
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
    
    /**
     * Getters and Setters
     */
    public function getData() : array {
        return $this->data ;
    }
    
    public function getHorizontalData() : array {
        // TODO : everything in this method
        return [] ;
    }
}
