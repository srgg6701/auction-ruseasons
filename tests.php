<?php
function commonDebug($file, $line, $array=NULL, $stop=false){
    $test=true;
    if(!$test) return false;
    echo "<div><b>file:</b> ".$file."<br>
    line: <span style='color:green'>".$line."</span></div>";
    if($array){
        echo "<div style='display: inline-block;padding:10px; background-color:#eee;margin-bottom:20px;'><pre>";
        var_dump($array);
        echo "</pre></div>"; 
    }
    if($stop) die();
}