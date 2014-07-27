<?php
/** Для подключения функций добавить в тестируемый файл:
    require_once JPATH_SITE.'/tests.php'; 
//  commonDebug, testSQL
 */
/**
 * Вывести состав объекта. 4-й аргумент - остановка выполнения скрипта
 */
define('dblclck', " title='dblclick' ondblclick='this.getElementsByTagName(\"div\")[0].style.display=(this.getElementsByTagName(\"div\")[0].style.display==\"none\")? \"block\":\"none\"'");
function commonDebug($file, $line, $object=NULL, $stop=false, $collapsed=true){
    $test=true;
    if(!$test) return false;
    if($file) echo "<div><b>file:</b> ".$file;
	if($line) echo "<br>
    line: <span style='color:green'>".$line."</span></div>";
    echo "
	<div class='test_content_box' style='display: inline-block; border:solid 1px #ccc; border-radius:4px; padding:10px; background-color:#eee;margin-bottom:20px;'>
		<pre>"; // ".dblclck."
    if($object){
        if(is_array($object)||is_object($object)){
			if(is_object($object)) 
				echo "<h4>Object ".get_class($object).":</h4>";
			else echo "<h4>Array:</h4>";

			if(!empty($object)) {
                ?><div<?php
                if ($collapsed){
                    ?> style="display:none; text-align:left;"<?php
                }?>><?php	
				foreach($object as $key=>$inner_object) {
					echo "<div class='border'>".$key." => ";
					commonDebug(false,false,$inner_object,false,true);
					echo "</div>";
				}
				//var_dump($object);
                ?></div><?php
            }
            else
                echo "<div style='color:brown;'>Объект пуст...</div>";
        }else
            echo "<div class='bottom'>".gettype($object).": ".$object."</div>";
    }else{
        echo "<div class='bottom' style='color:orange;'>Объект не получен...</div>";
    }
    echo "</pre></div>"; 
    if($stop) die();
}
/**
 * Вывести сообщение
 */
function showTestMessage($message,$file,$line,$color=false,$stop=false){
    echo "<div сlass='test_message' style='background-color: #eaebec;
    border: solid 1px #ccc;";
    if ($color) echo 'color:'.$color.';';
    echo "display: inline-block;
    margin: 10px auto;
    margin-bottom: 10px;
    padding: 10px;'>
    $message
    file: <span>"
        .$file."</span>
    <br>line:
    <span style='background-color:#666; color:white; padding: 2px 4px;'>"
        .$line."</span>
    </div>";
    if($stop) die('<div>stopped</div>');
}
/**
 * Вывести запрос в виде, подходящем для прямого тестирования
 */
function testSQL($query,$file=false,$line=false,$stop=false,$class='test'){
    if($file&&$line)
        echo "<div><b>file:</b> ".$file."<br>line: <span style='color:green'>".$line."</span></div>";			
    echo "<div class='$class' style='padding:10px; border:solid 1px #ccc; border-radius:4px; background-color:rgb(254, 239, 242); display: inline-block; margin-bottom:20px;' ".dblclck.">
            <b>query:</b>
            <div style='display:none'>
                <pre>".str_replace("#_","auc13",$query)."</pre>
            </div>
          </div>";
    if($stop) die();
}