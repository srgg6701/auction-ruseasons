<?php defined('_JEXEC') or die('Restricted access');?>
<div class="gallery_lot">
    <div id="galleryContainer"<?php //style="clear: both;"?>>
        <div class="main_im_lot">
            <div id="galleryBigImage">
                <div id="bigLeadImage">
                    <a href="<?=$base_path.$this->product->images[0]->file_url;?>" class="MagicZoomPlus" id="Zoomer" rel="zoom-width:370px; zoom-border:2px; zoom-height:400px; zoom-distance:25">
                        <img src="<?=$base_path.$this->product->images[0]->file_url;?>" width="334" style="opacity:1;">
                        <div class="MagicZoomBigImageCont">
                            <div class="MagicZoomHeader"> </div>
                        </div>
                        <div class="MagicZoomPup"> </div>
                    </a>
                </div>
            </div>
        </div>
        <div id="galleryThumbs">
            <div>
                <?php
                if(JRequest::getVar('imgs')) showTestMessage('См. картинки ниже',__FILE__,__LINE__);
                    //commonDebug(__FILE__,__LINE__,$this->product->images);
                foreach($this->product->images as $i => $stuff):
                    // получить сегменты пути к файлу
                    $arr_file_preview=explode("/",$stuff->file_url);
                    // получить последний сегмент (имя файла)
                    $filename=array_pop($arr_file_preview);
                    // $arr_file_preview ─ сегменты пути БЕЗ имени файла

                    // сохранить сегменты пути к дир. preview/resized/ - понадобится позже
                    $arr_path_to_preview = $arr_file_preview;
                    // конвертировать сегменты в строку, дописав путь к дир для средних миниатюр
                    $path_to_resized=implode('/',$arr_path_to_preview) . '/resized/';

                    // добавить имя директории и файла к массиву сегментов пути к файлу
                    $arr_file_preview[]='preview/'.$filename;
                    // преобразовать сегменты в строку
                    $file_preview_url=implode("/",$arr_file_preview);

                    // Для проверки наличия файла в дир resized:
                    // разделить на имя и расшерение
                    // имя
                    $file_name_raw = explode('.',$filename);
                    // расширение
                    $file_name_ext = array_pop($file_name_raw);
                    // дописать суяяикс
                    $file_name_thumb = implode('.',$file_name_raw) . '_226x226.' . $file_name_ext;
                    // если миниатюры по указанному адресу нет, создать её
                    $resized_file_path=$path_to_resized . $file_name_thumb;
                    // проверить наличие файлов
                    // preview
                    $preview_file=file_exists($file_preview_url);
                    // resized
                    $resized_file=file_exists($resized_file_path);

                    if(JRequest::getVar('imgs')) {
                       showTestMessage('<b>resized_file_path</b>: ' . $resized_file_path . '<br/><b>exists:</b> ' . $resized_file . '<hr/>',__FILE__,__LINE__);
                    }

                    // нет файла в дир. resized
                    if(!$resized_file){
                        showTestMessage('<b style="color:red">no file:</b> ' . $resized_file_path,__FILE__,__LINE__);
                        // переместить файл из preview в resized
                        if($preview_file){
                            if(!(rename($file_preview_url,$resized_file_path))){
                                echo "<div>Ошибка: не удалось переместить файл в директорию <b>rezied</b></div>";
                            }
                        }else{ // если в preview его нет, то создать копию из оригинала максимального размера
                            Media::copyImageResizedSimple($stuff->file_url, $resized_file_path, 334, 334, 80);
                        }
                    }
                    // todo: НЕ ЗАБЫТЬ!!!
                    // удалить файл из дир. preview
                    //if($preview_file) unlink($file_preview_url);

                    /**
                    array(3) {
                        ["file_url"]=>
                        string(45) "images/stories/virtuemart/product/2732619.jpg"
                        ["file_preview_url"]=>
                        string(53) "images/stories/virtuemart/product/preview/2732619.jpg"
                        ["file_url_thumb"]=>
                        string(63) "images/stories/virtuemart/product/resized/2732619.jpg_90x90.jpg"
                    }   */
                    //commonDebug(__FILE__,__LINE__,array('file_url'=>$stuff->file_url, 'file_preview_url'=>$file_preview_url, 'file_url_thumb'=> $stuff->file_url_thumb));?>
                    <div class="th_image">
                        <?php   //point: preview иллюстрации, см. схему в   ?>
                        <div class="inside_image_preview">
                            <a href="<?php
                            /**
                        [img_root] = [site_root]/images/stories/virtuemart/product
                        <a  href="[img_root]/9882630.jpg" rel="zoom-id:Zoomer"
                            rev="[img_root]/preview/9882630.jpg"
                            class="MagicThumb-swap"
                            style..., id...>
                                <img    src="[img_root]/9882630.jpg"
                                        height="82"
                                        width="82">
                        </a>   */
                            echo $base_path.$this->product->images[$i]->file_url;
                            ?>" rel="zoom-id:Zoomer" rev="<?php
                            echo $base_path.$resized_file_path; // resized
                            ?>" style="outline: none; " class="MagicThumb-swap">
                                <img src="<?php echo $base_path.$resized_file_path;
                                ?>" height="82" width="82"></a>
                        </div>
                    </div>

                <?php endforeach;?>
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>