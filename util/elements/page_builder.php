<?php

class PageBuilder {
    
    private $elements;
    
    public function __construct() {
        $this->elements = array();
    }
    
    public function add($elem) {
        if ($elem == null) {
            echo "<br>ERROR: NULL<br>";
            return;
        }
        if ($elem instanceof HtmlElement) {
            $elem = $elem->open;
        }
        array_push($this->elements, $elem);
    }
    
    public function show() {
        foreach ($this->elements as $e) {
            $e->show();
        }
    }
    
}

class HtmlBuilder {
    private $elements;
    
    public function __construct() {
        $this->elements = array();
    }
    
     public function add($elem) {
        if ($elem == null) {
            echo "<br>ERROR: NULL<br>";
            return;
        }
        if ($elem instanceof HtmlElement) {
            $elem = $elem->open;
        }
        array_push($this->elements, $elem);
    }
    
    public function show() {
        foreach ($this->elements as $e) {
            $e->show();
        }
    }

}