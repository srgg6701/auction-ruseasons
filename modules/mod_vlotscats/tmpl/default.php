<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
/**
 * test link function
 */
$test=false;
require_once JPATH_SITE.'/tests.php';

function testLinks($category_link,$line, $shop=false){
    $test=true;
    if(!$test) return false;
    
    if($shop)
        echo "<div style='padding:10px; background:lightgoldenrodyellow; margin-bottom:20px;'><b>shop</b>";
    echo "<div style='margin-bottom:20px;'>
    <b>file:</b> ".__FILE__."<br>
    line: <span style='color:green'>".$line."</span>";
    echo "<div style='background:lightskyblue; padding:10px;'>category_link(line ".$line.") = ".$category_link."</div>";
    echo "<div style='background:lightgreen; padding:10px;'>чпу: ".JRoute::_($category_link)."</div>";                    
    if($shop)
        echo "</div>";
    echo "</div>";
}

$session_links=AuctionStuff::getSessionCategoriesLinks();
$router = $app->getRouter();
$Itemid=JRequest::getVar('Itemid');
$menus = JFactory::getApplication()->getMenu()->getMenu();
$parent_category_id=$menus[$Itemid]->query['virtuemart_category_id'];
$loaded_category_id = JRequest::getVar('virtuemart_category_id');
$SefMode = $router->getMode();
/*
 id =>string: 125
menutype =>string: mainmenu
title =>string: Онлайн торги
alias =>string: онлайн-торги
route =>string: аукцион/онлайн-торги
link =>string: index.php?option=com_virtuemart&view=category&layout=online&virtuemart_category_id=21
level =>string: 2
parent_id =>string: 113
tree => [array]
0 =>string: 113
1 =>string: 125
query => [array]
    option =>string: com_virtuemart
    view =>string: category
    layout =>string: online
    virtuemart_category_id =>string: 21 */
//commonDebug(__FILE__, __LINE__, $session_links);
commonDebug(__FILE__, __LINE__, array($loaded_category_id,$parent_category_id));
foreach ($session_links as $data):
    /**
     * Отобразить все категории, если не выбрана ни одна из них (включая ТОП)
     * или только текущую ТОП-овую. */
    if(!( $loaded_category_id&&$parent_category_id
          && ($loaded_category_id!=$parent_category_id )
        ) || $data['top_category_id']==$parent_category_id
      ):
        /*if ($test):?>
            <div title="<?php echo $top_category_link; ?>">
                <b><a style="color:blue;" href="javascript:void(0)" onclick="alert('<?php 
            echo $top_category_link; ?>');">Ссылка раздела<br>hover, click</a></b>
            </div><?php         
        endif;*/
    ?>
    <h3>
        <a href="<?php echo JRoute::_($data['parent_link']);?>"><?php 
        echo $data['category_name']; ?></a>
            <span class="lots_count">(<?php echo $data['product_count']; ?>)</span>
    </h3>
    <ul>
    <?php   //commonDebug(__FILE__, __LINE__, $data['child_links']);
        foreach ($data['child_links'] as $category_id => $category_data):
            //commonDebug(__FILE__, __LINE__, array('sef'=>$category_data['sef'],'link'=>$category_data['link']));
            /**
                ["category_name"]=> "Русская живопись"
                ["link"]=> "index.php?option=com_virtuemart&view=category&Itemid=125&virtuemart_category_id=31"
                ["sef"]=> "/auction-ruseasons/аукцион/онлайн-торги/живопись-руси"
                ["product_count"]=> "0"
         */?>
        <li><a <?php 
                // родительская категория
                if ($loaded_category_id && $loaded_category_id == $category_id):
                    ?> style="color:brown;"<?php
                endif;
                ?> href="<?php 
                // ссылка
                echo ($SefMode)?
                        $category_data['sef']
                        :   $category_data['link'];?>"><?php
                // имя категории
                echo $category_data['category_name'];
                    ?><?php

                //echo "<div style='font-weight: 100'>category_data:".$category_data['sef'].'</div>';

                ?></a> (<?php
                // количество опубликованных предметов
                echo $category_data['product_count'];
                    ?>)</li>
<?php   endforeach;
    ?>
        </ul>
<?php
    endif;
endforeach; ?>
<br/>
<?php
// test session links:
if(JRequest::getVar('slinks')){
    echo "<pre>"; 
    var_dump($session_links); 
    echo '</pre>';
    if(JRequest::getVar('stop'))
        die();
}