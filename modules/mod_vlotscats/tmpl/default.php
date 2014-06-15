<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

$session = JFactory::getSession();

if (!$session->get('section_links')) :
    /*?>
    <script>location.reload();</script>
    <?php */
endif;
// Использовать только во время тестирования:
$session->clear('section_links');
//var_dump($section_links); die();
// get categories:
$lots = modVlotscatsHelper::getCategoriesData(true);
$router = $app->getRouter();

if ($SefMode = $router->getMode()) {
    $menu = JFactory::getApplication()->getMenu();
    $menus = $menu->getMenu();
    //var_dump(JRequest::get('get'));
    // var_dump($menus); die();
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
    $top_layout = $menus[JRequest::getVar('Itemid')]->query['layout']; // shop, fulltime
    //echo "<div><b>file:</b> ".__FILE__."<br>line: <span style='color:green'>".__LINE__."</span></div>";
    //echo "<pre>";var_dump($top_layout);echo "</pre>"; die();
}
?>
<br/>
<?php
$top_cats_menu_ids = AuctionStuff::getTopCatsMenuItemIds('main');
// get top categories aliases to substitute them as layouts:
/**
  "online", "fulltime", "shop" */
//$top_cats_aliases = AuctionStuff::getTopCatsLayouts();
//echo "<div><b>file:</b> ".__FILE__."<br>line: <span style='color:green'>".__LINE__."</span></div>";
//echo "<pre>";var_dump($top_cats_menu_ids);echo "</pre>"; // die();
//echo "<pre>";var_dump($top_cats_aliases);echo "</pre>"; // die();
//echo "<pre>";var_dump($lots);echo "</pre>"; die();
// online, fulltime, shop
//$a = 0;
// TODO: extract a whole link from the top cat menu params!
// See data above: $top_cats_menu_ids
$common_link_segment = 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=';
$section_links = array();
//
$show_online = true; // TODO: УБРАТЬ это доп. условие после окончания работ
foreach ($lots as $top_cat_id => $array) {
    $top_category_layout = $array['top_category_layout'];
    //$section_links[$top_cats_aliases[$a]] = array();
    $section_links[$top_category_layot] = array();
    //if($top_cats_aliases[$a]!='online'||$show_online){
    //echo '$array:<pre>'; var_dump($array); echo '</pre>';//die();
    $top_cat_count = 0;
    $andLayout = '&layout=' . $top_category_layout;//$top_cats_aliases[$a];
    $sub_cats = '
	<ul>';
    $test = true;
    // top cat layout (online, fulltime, shop)
    foreach ($array as $key => $array_data):
        if ($key == 'children'):
            foreach ($array_data as $i => $category_data):
                $product_count = (int) $category_data['product_count'];
                $top_cat_count+=$product_count;

                /* if ($test){?>Имя категории--><?php } */

                $category_link = $common_link_segment . $category_data['virtuemart_category_id'];
                $category_link.='&Itemid=' . $top_cats_menu_ids[$top_category_layout];
                //$top_cats_menu_ids[$a];

                //if ($top_cats_aliases[$a] == 'shop') 
                if ($top_category_layout == 'shop')
                    $category_link.=$andLayout;

                // TODO: разобраться-таки с долбанным роутером!!!!!!!!
                // кто косячит - Joomla OR VirtueMart?!
                // КАСТРИРОВАТЬ П******стов!!!!!!
                /*if ($SefMode) {
                    $category_link = JUri::base();
                    if ($top_cats_aliases[$a] != 'shop') {
                        $category_link.= $menus[$menus[$top_cats_menu_ids[$a]]->parent_id]->alias . '/';
                        //echo "<div>menu_id = $top_cats_menu_ids[$a]</div>";
                        //echo "<div>category_link = $category_link</div>";
                    }
                    //echo "<div>next: ".$menus[$top_cats_menu_ids[$a]]->alias.'/'.$category_data['alias']."</div>";
                    $category_link.= $menus[$top_cats_menu_ids[$a]]->alias . '/' . $category_data['alias'];
                }*/

                $sub_cats.='
    <li><a ';

                if ($loaded_category_id == $category_data['virtuemart_category_id'])
                    $sub_cats.=' style="color:brown;" ';

                $sub_cats.='href="';

                $sub_cats.=$category_link;
                $section_links[$top_category_layout/*$top_cats_aliases[$a]*/][$category_data['virtuemart_category_id']] = $category_link;

                $sub_cats.='">' . $category_data['category_name'] . '</a> (' . $product_count . ')<br>
    </li>';
            endforeach;
        endif;
    endforeach;
    $sub_cats.='
	</ul>';

    /* 	AHTUNG!!!
      Если ЧПУ отключены, добавить к ссылке $layout!
     */
    $link = $common_link_segment . '0';
    if (!$SefMode)
        $link.=$andLayout;
    $link.='&Itemid=' . $top_cats_menu_ids[$top_category_layout];
    //echo "<div><b>file:</b> ".__FILE__."<br>line: <span style='color:green'>".__LINE__."</span></div>";
    //echo "<div>top_category_layout = ".$top_category_layout."</div>";
    //$top_cats_menu_ids[$a];
    
    if ( !$top_layout // Загружен раздел ТОП-категории
         || $top_category_layout == $top_layout // 
         || !key_exists($top_layout, $top_cats_menu_ids)
         //|| !in_array($top_layout, $top_cats_aliases 
       ) {
        if ($test) { ?><div title="<?php 
            echo $link; ?>"><b><a style="color:blue;" href="javascript:void(0)" onclick="alert('<?php 
            echo $link; ?>');">Ссылка раздела<br>hover, click</a></b></div><?php         
        }
        /**
         index.php?
         option                 = com_virtuemart
         view                   = category
         virtuemart_category_id = 0
         Itemid=125 */
        ?>
        <h3>
            <a href="<?= JRoute::_($link) ?>"><?= $array['top_category_name'] ?></a>
                <span class="lots_count">(<?= $top_cat_count ?>)</span>
        </h3>
            <?php
            echo $sub_cats;
        }
        $a++;
    }
    $session->set('section_links', $section_links);
    // test session links:
    if(JRequest::getVar('slinks')){
        echo "<pre>"; 
        var_dump($section_links); 
        echo '</pre>';
        if(JRequest::getVar('stop'))
            die();
    }