<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
/**
 * test link function
 */
$test=false;
require_once JPATH_BASE.'/tests.php';

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

//$session = JFactory::getSession();
//$session->clear('section_links');

$session_links=AuctionStuff::handleSessionCategoriesData();
//commonDebug(__FILE__,__LINE__,$session_links); 

/**
    Сохранение ссылок в сессии необходимо для того, чтобы не 
 */
/*
if (!$session->get('section_links') && !$test) :
    ?>
    <script>location.reload();</script>
    <?php 
endif;*/
// Использовать только во время тестирования:
//$session->clear('section_links');
//var_dump($section_links); die();
// get categories:
//$lots = modVlotscatsHelper::getCategoriesData(true);
$router = $app->getRouter();
if ($SefMode = $router->getMode()) {
    $menu = JFactory::getApplication()->getMenu();
    $menus = $menu->getMenu();
    // Не получим virtuemart_category_id в режиме ЧПУ при загрузке профайла предмета. Используем другой способ извлечения...
    if (!$loaded_category_id = JRequest::getVar('virtuemart_category_id')) {
        /* array
          'Itemid' => string '115' (length=3)
          'option' => string 'com_virtuemart' (length=14)
          'limitstart' => int 0
          'limit' => string 'int' (length=3)
          'view' => string 'productdetails' (length=14)
          'virtuemart_product_id' => string '551' (length=3)
          'virtuemart_category_id' => int 0 */
        $loaded_category_id = AuctionStuff::getCategoryIdByProductId(JRequest::getVar('virtuemart_product_id')); // 9
    }
    // если загружена какая либо категория внутри основной:
    $top_layout = $menus[JRequest::getVar('Itemid')]->query['layout'];
}
foreach ($session_links as $layout => $data) :
    if ($test) { ?><div title="<?php 
        echo $top_category_link; ?>"><b><a style="color:blue;" href="javascript:void(0)" onclick="alert('<?php 
        echo $top_category_link; ?>');">Ссылка раздела<br>hover, click</a></b></div><?php         
    }?>
    <h3>
        <a href="<?php echo JRoute::_($data['parent_link']);?>"><?php 
    echo $data['category_name']; ?></a>
            <span class="lots_count">(<?php echo $data['product_count']; ?>)</span>
    </h3>
    <ul>
        <?php   
    //commonDebug(__FILE__, __LINE__, $data['child_links']);
    foreach ($data['child_links'] as $category_id => $category_data):?>
        <li><a <?php   
            if ($loaded_category_id && $loaded_category_id == $category_data['virtuemart_category_id']):
                ?> style="color:brown;"<?php
            endif;
            ?> href="<?php 
            // ссылка
            echo ($SefMode)? 
                    JRoute::_($category_data['sef'])
                    : JRoute::_($category_data['link']);?>"><?php 
            // имя категории
            echo $category_data['category_name'];
                ?></a>(<?php
            // количество опубликованных предметов
            echo $category_data['product_count'];
                ?>)</li>
<?php        
    endforeach;
    ?>
    </ul>
<?php
endforeach; ?>
<br/>
<?php
$old=false;
if($old):
    $lots = modVlotscatsHelper::getCategoriesData(true);
    // получить ассоциативный массив разделов ТОП-категорий вида [id]=>alias
    //*
    $top_cats_menu_ids = AuctionStuff::getTopCatsMenuItemIds('main');
    /**
      "online", "fulltime", "shop" */
    //*
    $common_link_segment = AuctionStuff::$common_link_segment;
    $section_links = array();
    //
    $show_online = true; // TODO: УБРАТЬ это доп. условие после окончания работ
    foreach ($lots as $top_cat_id => $array) {
        $top_category_layout = $array['top_category_layout'];
        $section_links[$top_category_layout] = array();
        $top_cat_count = 0;
        // HTML-шаблон:
        //*
        $andLayout =  AuctionStuff::$andLayout. $top_category_layout;
        $sub_cats_html = '
        <ul>';
        $test = true;
        // top cat layout (online, fulltime, shop)
        //commonDebug(__FILE__,__LINE__);
        foreach ($array as $key => $array_data):
            /**
                Вложенные категории: */
            if ($key == 'children'):
                foreach ($array_data as $i => $category_data):                
                    $parentItemId = $top_cats_menu_ids[$top_category_layout];        
                    //commonDebug(__FILE__,__LINE__,$category_data);                
                    $product_count = (int) $category_data['product_count'];
                    $top_cat_count+=$product_count;
                    //*
                    $child_category_link = $common_link_segment . $category_data['virtuemart_category_id'];
                    //testLinks($child_category_link,__LINE__);
                    //*
                    $child_category_link.='&Itemid=' . $parentItemId;
                    //testLinks($child_category_link,__LINE__);

                    //*
                    if ($top_category_layout == 'shop'){  
                        // добавить ссылку на HTML-шаблон магазина:
                        $child_category_link.=$andLayout;
                        //testLinks($child_category_link,__LINE__, true);
                    }

                    /*if ($SefMode) {
                        $child_category_link = JUri::base();
                        if ($parentItemId != 'shop') {
                            $child_category_link.= $menus[$menus[$parentItemId]->parent_id]->alias . '/';
                            testLinks($child_category_link,__LINE__);                                    
                        }
                        $child_category_link.= $menus[$parentItemId]->alias . '/' . $category_data['alias'];
                        testLinks($menus[$parentItemId]->alias,__LINE__); 
                        testLinks($category_data['alias'],__LINE__);
                        testLinks($child_category_link,__LINE__);                
                    }*/
                    //testLinks($child_category_link,__LINE__); 

                    $sub_cats_html.='
        <li><a ';
                    if ($loaded_category_id == $category_data['virtuemart_category_id'])
                        $sub_cats_html.=' style="color:brown;" ';

                    $sub_cats_html.='href="';

                    $sub_cats_html.=JRoute::_($child_category_link);
                    $section_links[$top_category_layout][$category_data['virtuemart_category_id']] = $child_category_link;
                    //            [online, fulltime, shop][55] = 
                    testLinks($child_category_link, __LINE__);
                    $sub_cats_html.='">' . $category_data['category_name'] . '</a> (' . $product_count . ')<br>
        </li>';
                endforeach;
            endif;
        endforeach;
        $sub_cats_html.='
        </ul>';

        /**
          ПЕРЕОПРЕДЕЛИТЬ ссылку для ТОП-категории
          AHTUNG!!!
          Если ЧПУ отключены, добавить к ссылке layout!
         */
        $top_category_link = $common_link_segment . '0';
        if (!$SefMode)
            $top_category_link.=$andLayout;

        $top_category_link.='&Itemid=' . $parentItemId;

        if ( !$top_layout // Загружен раздел ТОП-категории
             || !key_exists($top_layout, $top_cats_menu_ids)
             //|| !in_array($top_layout, $top_cats_aliases 
           ) {
            if ($test) { ?><div title="<?php 
                echo $top_category_link; ?>"><b><a style="color:blue;" href="javascript:void(0)" onclick="alert('<?php 
                echo $top_category_link; ?>');">Ссылка раздела<br>hover, click</a></b></div><?php         
            }
            /**
             ТОП-КАТЕГОРИЯ
             index.php?
             option                 = com_virtuemart
             view                   = category
             virtuemart_category_id = 0
             Itemid=125 */
            ?>
            <h3>
                <a href="<?php 
            //JRoute::_($top_category_link) ?>"><?php 
            //$array['top_category_name'] ?></a>
                    <span class="lots_count">(<?= $top_cat_count ?>)</span>
            </h3>
                <?php
                echo $sub_cats_html;
            }

        }
endif;
// test session links:
if(JRequest::getVar('slinks')){
    echo "<pre>"; 
    var_dump($session_links); 
    echo '</pre>';
    if(JRequest::getVar('stop'))
        die();
}