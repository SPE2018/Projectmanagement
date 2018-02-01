<?php

include_once "button_factory.php";

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
       return '
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" value="'.$this->val.'">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 36.4px"><i class="far fa-calendar-alt fa-1x" style="color: white"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $("#datetimepicker1").datetimepicker({
                    icons: {
                        time: "far fa-clock",
                        date: "far fa-calendar-alt",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                        },
                    format: "YYYY-MM-DD HH:mm"  
                });
            });
        </script>
    </div>';
    }

}