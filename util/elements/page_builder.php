<?php

class PageBuilder {
    
    private $elements;
    
    public function __construct() {
        $this->elements = array();
    }
    
    public function add($elem) {
        array_push($this->elements, $elem);
    }
    
    public function show() {
        foreach ($this->elements as $e) {
            $e->show();
        }
    }
    
}