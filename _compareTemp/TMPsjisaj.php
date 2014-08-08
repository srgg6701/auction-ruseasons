<?php
/** Для подключения функций добавить в тестируемый файл:
    require_once JPATH_SITE.'/tests.php'; 
//  commonDebug, testSQL
 */
/**
 * Вывести состав объекта. 4-й аргумент - остановка выполнения скрипта
 */
define('dblclck', " title='dblclick' ondblclick='this.getElementsByTagName(\"div\")[0].style.display=(this.getElementsByTagName(\"div\")[0].style.display==\"none\")? \"block\":\"none\"'");
function commonDebug($file, $line, $obj=NULL, $stop=false, $collapsed=true, $backtrace=false){
    ?>
    <div class='test-box first'>
        <div>file: <?=str_replace(JPATH_SITE,'',$file)?></div>
        <div style="display: inline-block; color:#666">line: <b><?=$line?></b></div>
        <?php
        if($obj!==NULL){
            if(empty($obj)):?>
            <div class="warning-text">Объект пуст...</div>
        <?php
            endif;
        }else{?>
            <div class="error-text">Объект не получен...</div>
    <?  }
        $loop = function($obj,$is_obj=false)use(&$loop){
            if(is_object($obj)||is_array($obj)){
                if(!$is_obj) echo '<span class="link">[dblclick]</span>';
                ?>
            <div class='test-box'><?php
                foreach ($obj as $key=>$val) {?>
                    <?=$key?> =><?php
                    $is_obj=false;
                    if(is_object($val)||is_array($val)) {
                        ?>
                        <span class="link">[<?
                            echo gettype($val);
                            if (is_object($val)):
                                ?>]</span> <span><?
                                echo get_class($val);
                            else:
                                ?>]<?php
                            endif;
                            ?>
                    </span>
                    <?php   $is_obj=true;
                    }
                    $loop($val,$is_obj);?>
                <br/>
                <?php
                }?>
            </div>
            <?php
            }else{
                echo gettype($obj).': <span style="color:green;">'.$obj.'</span><br>';
                $key=true;
            }
            return $key;
        };
        if(!$key=$loop($obj)){?>
            <pre><?php var_dump($obj);?></pre><?
        }?>
    </div>
<?php
    if($backtrace) commonDebugBacktrace($file,$line);
    if($stop) die('<hr><div><b>stopped</b></div>');
}
/**
 * Комментарий
 * @package
 * @subpackage
 */
function commonDebugBacktrace($file,$line,$class=''){
    file_line($file,$line);
    ob_start();
    debug_print_backtrace();
    $dbpb=ob_get_contents();
    ob_end_clean();
    $dbpb=str_replace(JPATH_SITE,'',$dbpb);
    $dbpb=str_replace('[',' <span style="color: blue">',$dbpb);
    $dbpb=str_replace(']','</span>',$dbpb);
    $dbpb=str_replace(" called at ","<br/><span style='background-color:#D1E8FF'> called at </span>",$dbpb);
    setBlock($dbpb,'trace',$class, 'lightyellow');
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
        file_line($file,$line);
    setBlock(str_replace("#_","auc13",$query),"query",$class);
    if($stop) die();
}
/**
 * Комментарий
 * @package
 * @subpackage
 */
function setBlock($string,$header,$class, $background='rgb(254, 239, 242)'){
    echo "<div class='$class' style='padding:10px; border:solid 1px #ccc; border-radius:4px; background-color:$background; margin-bottom:20px; overflow:auto;' ".dblclck.">
            <b>".$header.":</b>
            <div style='display:none'>
                <pre>".$string."</pre>
            </div>
          </div>";
}
/**
 * Выаусти файл и строку
 * @param $file
 * @param $line
 */
function file_line($file,$line){
    echo "<div><b>file:</b> ".$file."<br>line: <span style='color:green'>".$line."</span></div>";
}