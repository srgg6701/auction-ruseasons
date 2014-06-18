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
    echo "<div style='background:lightgreen; padding:10px;'>���: ".JRoute::_($category_link)."</div>";                    
    if($shop)
        echo "</div>";
    echo "</div>";
}

$session_links=AuctionStuff::handleSessionCategoriesData();
//commonDebug(__FILE__,__LINE__,$session_links); 
$router = $app->getRouter();
if ($SefMode = $router->getMode()) {
    $menu = JFactory::getApplication()->getMenu();
    $menus = $menu->getMenu();
    // �� ������� virtuemart_category_id � ������ ��� ��� �������� �������� ��������. ���������� ������ ������ ����������...
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
    // ����� ���-��������� - ���� ����, ������ ������ �����-���� �� ��� (��� �� ���������)
    $top_layout = $menus[JRequest::getVar('Itemid')]->query['layout'];
    //commonDebug(__FILE__, __LINE__, $top_layout);
}   //commonDebug(__FILE__, __LINE__, JRequest::getVar('option'), false);

foreach ($session_links as $layout => $data):
    //commonDebug(__FILE__, __LINE__, $layout);
    //commonDebug(__FILE__, __LINE__, $session_links);
    /** 
     * ���������� ��� ���������, ���� �� ������� �� ���� �� ��� (������� ���)
     * ��� ������ ������� ���-����. */
    if(!$top_layout
       || $top_layout==$layout
       || JRequest::getVar('option')=='com_users'
      ):
        /*if ($test):?>
            <div title="<?php echo $top_category_link; ?>">
                <b><a style="color:blue;" href="javascript:void(0)" onclick="alert('<?php 
            echo $top_category_link; ?>');">������ �������<br>hover, click</a></b>
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
            //commonDebug(__FILE__, __LINE__, $category_data);
            /**
                ["category_name"]=> "������� ��������"
                ["link"]=> "index.php?option=com_virtuemart&view=category&Itemid=125&virtuemart_category_id=31"
                ["sef"]=> "/auction-ruseasons/�������/������-�����/��������-����"
                ["product_count"]=> "0"
         */?>
        <li><a <?php 
            
                if ($loaded_category_id && $loaded_category_id == $category_id):
                    ?> style="color:brown;"<?php
                endif;
                ?> href="<?php 
                // ������
                echo ($SefMode)? 
                        JRoute::_($category_data['sef'])
                        : JRoute::_($category_data['link']);?>"><?php 
                // ��� ���������
                echo $category_data['category_name'];
                    ?></a> (<?php
                // ���������� �������������� ���������
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