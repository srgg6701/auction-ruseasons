<?php
/** Для подключения функций добавить в тестируемый файл:
    require_once JPATH_SITE.'/tests.php'; 
//  commonDebug, testSQL
 */
/**
 * Вывести состав объекта. 4-й аргумент - остановка выполнения скрипта
 */
define('dblclck', " title='dblclick' ondblclick='this.getElementsByTagName(\"div\")[0].style.display=(this.getElementsByTagName(\"div\")[0].style.display==\"none\")? \"block\":\"none\"'");
// рекурсивная функция для вывода контента объектов
function loop( $obj,
               $is_obj=false
            ){
	if(is_object($obj)||is_array($obj)){
		if(!$is_obj) // первый вызов
            echo '<span class="link">[dblclick]</span>';
		?>
	<div class='test-box'><?php
		foreach ($obj as $key=>$val) {?>
			<?=$key?> =><?php
			$is_obj=false;
			if(is_object($val)||is_array($val)) {
				?>
				<span class="link">[<?php
					echo gettype($val);
					if (is_object($val)):
						?>]</span> <span><?php
						echo get_class($val);
					else:
						?>]<?php
					endif;
					?>
			</span>
			<?php   $is_obj=true;
			}
			loop($val,$is_obj);?>
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
		
function commonDebug( $file,
                      $line,
                      $obj=NULL,
                      $stop=false,
                      $collapsed=true,
                      $backtrace=false
                    ){
    ?>
    <div class='test-box first'><?php
        if($file):?>
        <div>file: <?=str_replace(JPATH_SITE,'',$file)?></div>
<?php   endif;
        if($line):?>
        <div style="display: inline-block; color:#666">line: <b><?=$line?></b></div>
        <?php
        endif;
        if($obj!==NULL){
            if(empty($obj)):?>
            <div class="warning-text">Объект пуст...</div>
        <?php
            endif;
        }else{?>
            <div class="error-text">Объект не получен...</div>
    <?php
        }
        if($collapsed==2){?>
            <pre><?php var_dump($obj);?></pre><?php
        }elseif(!$key=loop($obj)){?>
            <pre><?php var_dump($obj);?></pre><?php
        }?>
    </div>
<?php
    if($backtrace) commonDebugBacktrace($file,$line);
    if($stop) die('<hr><div><b>stopped</b></div>');
}
/**
 * Вывести стек вызовов
 * @package
 * @subpackage
 */
function commonDebugBacktrace($file,$line,$class='',$stop=false){
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
    if($stop) die('<h4>stopped</h4>');
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
 *
 */
function queryTestByGetVar($query,$file,$line,$stop=false,$class='test',$backtrace=false){
    if(JRequest::getVar('qtest')==$line)
        testSQL($query,$file,$line,$stop,$class,$backtrace);
}
/**
 * Вывести запрос в виде, подходящем для прямого тестирования
 */
function testSQL( $query,
                  $file=false,
                  $line=false,
                  $stop=false,
                  $class='test',
                  $backtrace=false
                ){
    if($file&&$line)
        file_line($file,$line);
    if(is_array($query)&&(is_array($query[0])||is_object($query[0]))){
        ?><div class="header_query">Всего записей: <?php echo (count($query[0]))? count($query[0]):'<span class="error-text">0</span>';?></div><?php
        $query=$query[1];
    }
    if(strstr($query,"#_"))
        $query=str_replace("#_","auc13",$query);
    setBlock($query,"query",$class);
    if($backtrace) commonDebugBacktrace($file,$line,$class);
    if($stop) die();
}
/**
 * Комментарий
 * @package
 * @subpackage
 */
function setBlock($string,$header,$class, $background='rgb(254, 239, 242)'){
    $string=str_ireplace('SELECT ','<b style="color:navy">SELECT</b> ',$string);
    $string=str_ireplace('FROM ',  '<b style="color:navy">FROM</b> ',$string);
    $string=str_ireplace('DELETE ','<b style="color:red">DELETE</b> ',$string);
    $string=str_ireplace('INSERT ','<b style="color:green">INSERT</b> ',$string);
    $string=str_ireplace('UPDATE ','<b style="color:brown">UPDATE</b> ',$string);
    $string=str_ireplace('WHERE ', '<b style="color:navy">WHERE</b> ',$string);
    $string=str_ireplace('AS ',    '<b style="color:navy">AS</b> ',$string);
    $string=str_ireplace('OR ',    '<b style="color:navy">OR</b> ',$string);
    $string=str_ireplace('INNER ', '<b style="color:darkviolet">INNER</b> ',$string);
    $string=str_ireplace('AND ',   '<b style="color:darkviolet">AND</b> ',$string);
    $string=str_ireplace('ON ',    '<b style="color:darkviolet">ON</b> ',$string);
    $string=str_ireplace('LEFT ',  '<b style="color:blue">LEFT</b> ',$string);
    $string=str_ireplace('JOIN ',  '<b style="color:navy">JOIN</b> ',$string);
    $string=str_ireplace('ORDER ', '<b style="color:navy">ORDER</b> ',$string);
    $string=str_ireplace('BY ',    '<b style="color:navy">BY</b> ',$string);
    $string=str_ireplace('DESC ',  '<b style="color:navy">DESC</b> ',$string);
    $string=str_ireplace('ASC ',   '<b style="color:navy">ASC</b> ',$string);
    $string=str_ireplace('LIMIT ', '<b style="color:darkviolet">LIMIT</b> ',$string);
    $string=str_ireplace('DISTINCT ','<b style="color:#666">DISTINCT</b> ',$string);
    echo "<div class='$class' style='padding:10px; border:solid 1px #ccc; border-radius:4px; background-color:$background; margin-bottom:20px; overflow:auto;' ".dblclck.">
            <b style='cursor:default'>".$header.":</b>
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
}?>