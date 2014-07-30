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
        if($obj){?>
            [<span class="link">dblclick</span>]
        <?php
        }
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
        };
        $loop($obj);?>
    </div>
<?php   if($stop) die('<hr><div><b>stopped</b></div>');
}

function commonDebugOld($file, $line, $object=NULL, $stop=false, $collapsed=true){
    $test=true;
    if(!$test) return false;
    if($file) echo "<div><b>file:</b> ".$file;
	if($line) echo "<br>
    line: <span style='color:green'>".$line."</span></div>";
    ?>
	<div class='test_content_box test-box'>
	    <?php
    if (is_object($object))
        echo "<h4>Object " . get_class($object) . ":</h4>";
    else
        echo "<h4>Array:</h4>";
    if($collapsed===1){
        echo '<pre>';
        var_dump($object);
        echo '</pre>';
    }else {
        $loop = function ($object=NULL) use (&$loop, $collapsed) {
            if($object){
                if (is_object($object) || is_array($object)) {
                    ?>
                    <div class='test-box'"<?php if ($collapsed) {?> style="display:none; text-align:left;"<?php }?>>
				<?php
					if (!empty($object)) {

                        foreach ($object as $key => $val) {
                            echo $key;?> => <?php
                            $loop($val);
                        }
                    } else
                        echo "<div style='color:brown;'>Объект пуст...</div>";?>
	</div>
	<?php		} else {
                    ?>
                    <span style="color:green;"><?= $object ?></span>
                <?php
                }
            }else{
                ?><div style='color:red;'>Объект не получен</div>
            <?php
            }
        };
        $loop($object);
    }?>
    </div>
    <?php
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