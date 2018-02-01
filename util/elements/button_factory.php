<?php


abstract class ButtonType {
    const DARK = "btn btn-dark";
    const CLOSE = "close";
    const DISABLED = "btn btn-disabled";
    const DANGER = "btn btn-danger";
    const SUCCESS = "btn btn-success";
    const BASIC = "btn btn-default";
    const PRIMARY = "btn btn-primary";
    
}

class Button extends ElementUnique {
        
    public $text;
    public $isSubmit;
    
    public function __construct($type, $name, $val, $text, $isSubmit) {
        parent::__construct($type, $name, $val);
        $this->text = $text;
        $this->isSubmit = $isSubmit;               
    }
    
    // Overrides parent
    public function get() {
        return "<button type='" 
                . ($this->isSubmit ? "submit" : "button" )
                . "' class='$this->displayType' id='$this->name' name='$this->name' value='$this->val'>$this->text</button>";
    }
    
}

class ButtonFactory {
    
    public static function createButton($type, $text, $isSubmit, $name, $val) {
        return new Button($type, $name, $val, $text, $isSubmit);
    }
    
    
}
