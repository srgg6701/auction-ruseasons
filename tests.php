<?php
/**
 * Вывести состав объекта. 4-й аргумент - остановка выполнения скрипта
 */
function commonDebug($file, $line, $object=NULL, $stop=false){
    $test=true;
    if(!$test) return false;
    echo "<div><b>file:</b> ".$file."<br>
    line: <span style='color:green'>".$line."</span></div>";
    echo "<div style='display: inline-block;padding:10px; background-color:#eee;margin-bottom:20px;'><pre>";
    if($object){
        if(is_array($object)||is_object($object)){
            if(!empty($object))
                var_dump($object);
            else
                echo "<div style='color:brown;'>Объект пуст...</div>";
        }else
            echo "<div>value: ".$object."</div>";
    }else{
        echo "<div style='color:orange;'>Объект не получен...</div>";
    }
    echo "</pre></div>"; 
    if($stop) die();
}