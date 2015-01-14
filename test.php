<?php // test2 comes here!
function handleSlugString($slug){
    $slug=mb_strtolower($slug, 'UTF-8');
    $arr=explode(" ",$slug);
    $slug='';
    foreach($arr as $word){
        if(strlen($slug)<70){
            if($slug!='')
                $slug.='-';
            $slug.=$word;
        }
    }
    $slug = strtr($slug, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
    $slug = preg_replace("/[^0-9a-z-_\-\.\,]/i", "", $slug); // очищаем строку от недопустимых символов
    return $slug; // возвращаем результат
}
$slug=handleSlugString("Картина Репина «Приплыли!», 1884 г. Такая вот прикольная, оч. прикольная картина...");

echo "<div>".__LINE__.", slug: ".$slug."</div>";