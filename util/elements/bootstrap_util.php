<?php

include_once "element_factory.php";

class BUtil {
    
    public static function getTextInput($id, $val) {
        return "<input class='form-control' id='$id' type='text' name='$id' value='" . $val . "'>";
    }
    
    public static function getLabel($for, $str) {
        return "<label for='$for'>$str</label>";
    }
    
    public static function getDatepicker($name, $id, $val) {
        return "<div class='container'>
            <div class='row'>
                <div class='form-group'>
                    <div class='input-group date' id='$id'>
                        <input type='text' class='form-control' name='$name' value='$val'/>
                        <span class='input-group-addon'>
                            <span class='glyphicon glyphicon-calendar'></span>
                        </span>
                    </div>
                </div>
                <script type=\"text/javascript\">
                    $(function () {
                        $(\"#$id\").datetimepicker({
                            format: 'YYYY-MM-DD HH:mm'
                        });
                    });
                </script>
            </div>
        </div>";
    }
    
    public static function success($str) {
        return "<div class='alert alert-success'>
                $str
              </div>";
    }
    
}

