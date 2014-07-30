<?php
/** Для подключения функций добавить в тестируемый файл:
    require_once JPATH_SITE.'/tests.php'; 
//  commonDebug, testSQL
 */
/**
 * Вывести состав объекта. 4-й аргумент - остановка выполнения скрипта
 */
define('dblclck', " title='dblclick' ondblclick='this.getElementsByTagName(\"div\")[0].style.display=(this.getElementsByTagName(\"div\")[0].style.display==\"none\")? \"block\":\"none\"'");
function commonDebug($file, $line, $obj=NULL, $stop=false, $collapsed=true){
    ?>
    <div class='test-box first'>
        <div>file: <?=str_replace(JPATH_SITE,'',$file)?></div>
        <div style="display: inline-block; color:#666">line: <b><?=$line?></b></div>
        <?php
        if($obj){
            if(empty($obj)):?>
            <div class="warning-text">Объект пуст...</div>
            <?php
            else:?>
            [<span class="link">dblclick</span>]
        <?php
            endif;
        }else{?>
            <div class="error-text">Объект не получен...</div>
    <?  }
        $loop = function($obj)use(&$loop){
            foreach ($obj as $key=>$val) {?>
                <div class='test-box'><?=$key?> => <?php
                    if(is_object($val)||is_array($val)){?>
                        <h4>
                    <span class="link"><?
                        if (is_object($obj)):
                            ?>[Object] <? echo '</span> '.get_class($obj);
                        else:
                            ?>[Array]<?php
                        endif;
                        ?></span>
                        </h4>
                        <?php   $loop((array)$val);?>
                    <?php
                    }else{
                        echo '<span style="color:green;">'.$val.'</span>';
                    }?>
                </div>
            <?php
            }
            return $key;
        };
        if(!$key=$loop($obj)){?>
        <div class='test-box first'>
            <pre><?php var_dump($obj);?></pre><?
        }?>
    </div>
<?php   if($stop) die('<hr><div><b>stopped</b></div>');
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