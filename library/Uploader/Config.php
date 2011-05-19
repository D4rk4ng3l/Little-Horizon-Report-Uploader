<?php
class Uploader_Config implements Iterator, Countable
{
    private $_data = array();
    
    private $_allowModify = false;
    
    private $_index = 0;
    
    function __construct(array $data, $allowModify = false)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->_data[$key] = new self($value, $allowModify);
            } else {
                $this->_data[$key] = $value;
            }
        }
        $this->_allowModify = $allowModify;
    }
    
    public function __get($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        
        return false;
    }
    
    public function __set($key, $value)
    {
        if (!$this->_allowModify) {
            require_once 'Uploader/Config/Exception.php';
            throw new Uploader_Config_Exception(
                'Modifications are not allowed'
            );
        }
        
        if (is_array($value)) {
            $this->_data[$key] = new self($value, $this->_allowModify);
        } else {
            $this->_data[$key] = $value;
        }
    }
    
    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }
    
    public function __unset($key)
    {
        unset($this->_data[$key]);
    }
    
    public function toArray()
    {
        $array = array();
        foreach ($this->_data as $key => $value) {
            if ($value instanceof Uploader_Config) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }
    
    public function count()
    {
        return count($this->_data);
    }
    
    public function current()
    {
        return current($this->_data);
    }
    
    public function next()
    {
        $this->_index++;
        return next($this->_data);
    }
    
    public function key()
    {
        return key($this->_data);
    }
    
    public function rewind()
    {
        $this->_index = 0;
        return reset($this->_data);
    }
    
    public function valid()
    {
        return $this->_index < count($this->_data);
    }
}
