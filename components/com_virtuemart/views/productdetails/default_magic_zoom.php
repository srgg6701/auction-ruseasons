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
                <?php 	//commonDebug(__FILE__,__LINE__,$this->product->images);
                foreach($this->product->images as $i => $stuff):
                    $arr_file_preview=explode("/",$stuff->file_url);
                    $file_preview_url='preview/'.array_pop($arr_file_preview);
                    $arr_file_preview[]=$file_preview_url;
                    $file_preview_url=implode("/",$arr_file_preview);
                    //commonDebug(__FILE__,__LINE__,array('file_url'=>$stuff->file_url, 'file_preview_url'=>$file_preview_url, 'file_url_thumb'=> $stuff->file_url_thumb));?>
                    <div class="th_imgage">
                        <div class="inside_image_preview">
                            <a href="<?php
                            echo $base_path.$this->product->images[$i]->file_url;
                            ?>" rel="zoom-id:Zoomer" rev="<?php
                            echo $base_path.$file_preview_url; // preview_
                            ?>" style="outline: none; " class="MagicThumb-swap">
                                <img src="<?php echo $base_path.$this->product->images[$i]->file_url_thumb;
                                ?>" height="82" width="82"></a>
                        </div>
                    </div>

                <?php endforeach;?>
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>