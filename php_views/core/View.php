<?php
namespace Core;

class View {
    public static function render($view, $args = []) {
        extract($args, EXTR_SKIP);
        $file = "Views/$view.php";
        if (is_readable($file)) {
            require $file;
        } else {
            echo "View file $file not found";
        }
    }
}
