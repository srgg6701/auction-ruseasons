<!--
1. Добавить директиву поделючения этого файла на тестируемую стр. перед тестируемым блоком
include_once '[path]/dev.php'; -->
<!--
2. Подключить jQuery, jQueryUI
-->
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<?php
$section = $_GET['section'];
if(!$section) $section='default';
/**
 * Настройки подложки: */
// Идентификатор главного тестируемого блока:
$main_block = "'#page'";
// Путь извлечения изображений:
$substrate_path = 'templates/auction/pixel-perfect/';
// Изображения для страниц:
$substrates = array(    // класс => имя файла изображения
                        'default'=>'substrate.png'
                    );
// Тени
$box_shadow = '0 4px 8px rgba(0, 0, 0, 0.5), 0 -14px 20px 2px rgba(0, 0, 0, 0.1) inset';
?>
<style>
    #controls {
        background-color: white;
        -webkit-box-shadow: <?php echo $box_shadow;?>;
        -moz-box-shadow: <?php echo $box_shadow;?>;
        box-shadow: <?php echo $box_shadow;?>;
        padding: 2px 4px;
        position: fixed;
        right: 50%;
        padding: 10px;
        padding-top: 0;
        -webkit-transform: translateX(50%);
        -moz-transform: translateX(50%);
        -ms-transform: translateX(50%);
        -o-transform: translateX(50%);
        transform: translateX(50%);
        white-space: nowrap;
        z-index: 2;
    }
    #opacity-range {
        margin-left: 20px;
        width: 80px;
    }
    .sbstr {
        background-repeat: no-repeat;
        height: 568px;
        margin: -36px;
        opacity: 0.5;
        position: absolute;
        top: 31px;
        width: 100%;
        z-index: -1
    }
    #substrate-wrapper {
        bottom: 0;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 0;
    <?php   // установить ширину блока с подложкой
            foreach($substrates as $class=>$substrate):
                $sPath = $substrate_path . $substrate;
                if($class==$section) {
                    $dimensions = getimagesize($sPath);
                    $dimensions = str_replace("=\"", ":", $dimensions['3']);
                    $dimensions = str_replace("\"", "px;", $dimensions);
                    $css=explode(" ",$dimensions);
                    echo "\t".$css[0]."\n";
            }
?>
        z-index: -1;
    }
    #substrate {
        height: 2000px;
        margin: 0 auto;
        max-width: 1366px;
    }
    #substrate.<?php echo $class;?> {
        background: url(<?php echo $sPath;?>) no-repeat;
    }
<?php   endforeach;
?>
</style>
<div id="controls">
<?php   $show_substrate=true; // не показывать подложку

        if($_GET['sbstr']=='false')
            $show_substrate=false;

        elseif(!$show_substrate&&isset($_GET['sbstr']))
            $show_substrate=true;

        if($show_substrate):?>
    <label>
        <input type="checkbox" id="sbstr"<?php
        $opacity=(isset($_GET['opa']))? $_GET['opa']:0;
        if($opacity>0):?> checked="checked" <?php endif;?> />Подложка
    </label>
    <label title="Прозрачность подложки">
        <input type="range" id="opacity-range" min="0"  max="100" value="<?php echo $opacity*100;?>" />
    </label>
    &nbsp;
    <label title="Прозрачность контента">
        <input type="range" id="opacity-range-content" min="0"  max="100" value="100" />
    </label>
    &nbsp;
<?php   endif;?>
    <a href="?section=auto-profile">Профайл авто</a>
    &nbsp;
    <a href="?section=search-result">Результат поиска</a>
</div>
<?php if($show_substrate):?>
<div id="substrate-wrapper">
    <div style="opacity: <?php echo $opacity;?>" id="substrate" class="<?php echo $section;?>"></div>
</div>
<?php endif;?>
<script>
    jQuery(function(){
        var $=jQuery,
            checkbox =$('#sbstr'),
            substrate = $('#substrate'),
            range = $('#opacity-range'),
            tested_content = $('#opacity-range-content');
        var setRange = function(){
                if($(substrate).is(':visible'))
                    $(range).val(parseInt($(substrate).css('opacity'))*100);
                else
                    $(range).val('0');
            },
            changeOpacity = function(input){
                return parseInt(input.value)/100
            };
        $(checkbox).on('click', function(){
            // если подложка скрыта
            if($(substrate).css('opacity')==0){
                $(substrate).css('opacity',1);
                $(tested_content).val('50').trigger('input');
                setRange();
            }else{
                $(substrate).fadeToggle(200,setRange);
            }
        });
        $(range).on('input', function(){
            var cbox=$(checkbox)[0], opa = changeOpacity(this);
            if(opa>0) {
                if(!cbox.checked) cbox.checked=true;
            }else
                cbox.checked=false;
            $(substrate).css({
                display:'block',
                opacity:opa
            });
        });
        $(tested_content).on('input', function(){
            $(<?php echo $main_block;?>).css('opacity', changeOpacity(this));
        });
        $('#controls').draggable();
    });
</script>