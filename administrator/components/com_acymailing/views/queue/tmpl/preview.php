<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>	<h1><?php echo $this->mail->subject ?></h1>
	<div class="newsletter_body" >
	<?php echo $this->mail->sendHTML ? $this->mail->body : nl2br($this->mail->altbody); ?>
	</div>
