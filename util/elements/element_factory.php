<?php

include_once "button_factory.php";
include_once "page_builder.php";

class ElementFactory {
    
    public static function createTextInput($name, $val) {
        return new TextInput($name, $val);
    }
    
    public static function createLabel($for, $str) {
        return new Label($str, $for);
    }
    
    public static function createHtml($html, $close = null) {
        return new HtmlElement($html, $close);
    }
    
    public static function createDatepicker($name, $val, $id) {
        $id = $id->format("Y-m-d H:i");
        return new Datepicker($name, $id, $val);
    }
    
    public static function createImg($src) {
        return new Image($src);
    }
    
    public static function createHeader($str, $hType) {
        return new Header($str, $hType);
    }
    
    /**
     * 
     * @param String $str
     * @param ParagraphStyle $style
     * @return \Paragraph
     */
    public static function createP($str, $style) {
        return new Paragraph($str, $style);
    }
    
}

abstract class Element {
    abstract public function get();
    
    public function show() {
        echo $this->get();
    }
}

abstract class ElementUnique extends Element {
    public $displayType;
    public $name;
    public $val;
    
    public function __construct($displayType, $name, $val) {
        $this->displayType = $displayType;
        $this->name = $name;
        $this->val = $val;
    }
    
    
    public function show() {
        echo $this->get();
    }
}

class HtmlElement {
    public $open;
    public $close;
    
    public function __construct($html, $close) {
        $this->open = new Html($html);
        if ($close != null) {
            $this->close = new Html($close);
        }
    }
    
}

class Html extends Element {
    
    private $html;
    
    public function __construct($html) {
        $this->html = $html;
    }
    
    public function get() {
        return $this->html;
    }

}

class TextInput extends ElementUnique {
    
    public $id;    
    
    public function __construct($id, $val) {
        parent::__construct(null, $id, $val);
        $this->id = $id;
    }
    
    public function get() {
        return "<input class='form-control' id='$this->id' type='text' name='$this->id' value='" . $this->val . "'>";
    }
}

class Label extends Element {
    
    public $text;
    public $for;
    
    public function __construct($text, $for) {
        $this->text = $text;
        $this->for = $for;
    }
    
    public function get() {
        return "<label for='$this->for'>$this->text</label>";
    }

}

class Datepicker extends ElementUnique {
    
    public $id;
    
    public function __construct($name, $val, $id) {        
        parent::__construct(null, $name, $val);
        $this->id = $id;
    }
    
    public function get() {
       return "<div class='container'>
            <div class='row'>
                <div class='form-group'>
                    <div class='input-group date' id='$this->id'>
                        <input type='text' class='form-control' name='$this->name' value='$this->val'/>
                        <span class='input-group-addon'>
                            <span class='glyphicon glyphicon-calendar'></span>
                        </span>
                    </div>
                </div>
                <script type=\"text/javascript\">
                    $(function () {
                        $(\"#$this->id\").datetimepicker({
                            format: 'YYYY-MM-DD HH:mm'
                        });
                    });
                </script>
            </div>
        </div>";  
    }

}

class Image extends Element {
    
    public $src;
    
    public function __construct($src) {
        $this->src = $src;
    }
    
    public function get() {
        return "<img src='$this->src' "
                . "style='width:100%; max-width:700px;'>";
    }

}

class Header extends Element {
    
    public $str;
    public $hType;
    
    public function __construct($str, $hType) {
        $this->str = $str;
        $this->hType = $hType;
    }
    
    public function get() {
        return "<$this->hType>$this->str</$this->hType>";
    }
    
}

class ParagraphStyle {
    const NORMAL = "";
    const LEAD = "class='lead'";
}

class Paragraph extends Element {
    
    public $str;
    public $class;
    
    public function __construct($str, $class) {
        $this->str = $str;
        $this->class = $class;
    }
    
    public function get() {
        return "<p $this->class>$this->str</p>";
    }

}