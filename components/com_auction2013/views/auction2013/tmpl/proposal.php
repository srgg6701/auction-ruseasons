<?
defined('_JEXEC') or die('Restricted access');
if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php 	
endif;

// выведем статью "Предложить предмет":
$article=AuctionStuff::getArticleContent(13);	
// var_dump($article);	
echo $article['introtext'];?>
<form method="POST" id="contactform" name="contactform" action="index.php?a=28&amp;b=136" enctype="multipart/form-data">  

<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">* Город  </label>
    <input type="text" name="city" id="city" title="" size="30" maxlength="150" class="cf_inputbox required validate-alpha">
    <label for="city" style="width:190px;float: right;" class="cf_label"><em></em></label>

 </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">* Ваше имя </label>
    <input type="text" name="name" id="name" title="" size="30" maxlength="150" class="cf_inputbox required validate-alpha">
    <label for="name" style="width: 190px;float: right;" class="cf_label"><em></em> </label>

 </div>
  <div class="cfclear">&nbsp;</div>
</div>



<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">* Ваш e-mail</label>
    <input type="text" name="posta" id="posta" title="" size="30" maxlength="150" class="cf_inputbox required">
      <label for="posta" style="width: 190px;float: right;" class="cf_label"><em></em> </label>

  </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">Телефон</label>
    <input type="text" name="phone" id="phone" title="" size="30" maxlength="150" class="cf_inputbox">
  
  </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_textarea">
    <label style="width: 150px;" class="cf_label">* Краткое описание (материал, наличие подписи, марки или клейма, размер и др.) </label>
    <textarea name="tikstik" cols="30" title="" id="tikstik" rows="3" class="cf_inputbox required"></textarea>
    <label for="tikstik" style="width: 190px;float: right;" class="cf_label"> <em></em> </label>

  </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_textarea">
    <label style="width: 150px;" class="cf_label">Пожелания по цене</label>
    <textarea name="coast" cols="30" title="" id="coast" rows="3" class="cf_inputbox"></textarea>
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>


<div class="form_item">
  <div class="form_element cf_textbox">
* Фото предмета в нескольких ракурсах (если имеется, фото подписи, марки или клейма)
  <div class="cfclear">&nbsp;</div>
  </div>
</div>


<div class="form_item">
  <div class="form_element cf_fileupload">
    <label style="width: 150px;" class="cf_label">Изображение 1</label>
    <input type="file" name="mail_file" id="mail_file" size="20" title="" class="cf_fileinput cf_inputbox">
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_fileupload">
    <label style="width: 150px;" class="cf_label">Изображение 2</label>
    <input type="file" name="mail_file2" id="mail_file2" size="20" title="" class="cf_fileinput cf_inputbox">
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_fileupload">
    <label style="width: 150px;" class="cf_label">Изображение 3</label>
    <input type="file" name="mail_file3" id="mail_file3" size="20" title="" class="cf_fileinput cf_inputbox">
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>

<div class="form_item">
  <div class="form_element cf_textbox">
* графы, обязательные для заполнения
  <div class="cfclear">&nbsp;</div></div>
</div>

<div class="form_item">
  <div class="form_element cf_button">
    <input type="submit" name="button_13" value="Отправить">
  </div>
  <div class="cfclear">&nbsp;</div>
</div>
           </form>