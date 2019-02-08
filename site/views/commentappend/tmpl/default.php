<?php
/**
 * View file for Append default comment on the player page
 *
 * This file is to Append default comment on the player page
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/** Set video id in hidden field */
?>
<input type="hidden" name="id" id="id" value="<?php echo JRequest::getInt('id'); ?>">
<?php /** Get user id from helper for comment append section */
$userID = getUserID ();
$cat_id = '';
/** Get comment id from request */
$cmdid = JRequest::getInt ( 'cmdid' );
/** Get video and category id from request */
$id = JRequest::getInt ( 'id' );
$catid = JRequest::getInt ( 'catid' );
/** Get page number from request */
$requestpage = JRequest::getInt ( 'page' );
/** Get seo option value from settings */
$seoOption = $this->dispEnable ['seo_option'];

/** Check comment title is exist */
if (isset ( $this->commenttitle )) {
  /** Set comment title based on SEO 
   * Check seo option is enabled*/ 
  if ($seoOption == 1) {
    $commentCategoryVal = "category=" . $this->commenttitle [0]->seo_category;
    $commentVideoVal = "video=" . $this->commenttitle [0]->seotitle;
  } else {
    $commentCategoryVal = "catid=" . $this->commenttitle [0]->playlistid;
    $commentVideoVal = "id=" . $this->commenttitle [0]->id;
  }
  /** Get curent page URL for comment append view */ 
  $currentURL = 'index.php?option=com_contushdvideoshare&view=player&' . $commentCategoryVal . '&' . $commentVideoVal;
}

/** If comment is 4 
 * then include css and js for jcomments */
if ($cmdid == 4) { ?>
  <link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_jcomments/tpl/default/style.css" type="text/css" />
  <script type="text/javascript" src="<?php echo JURI::base(); ?>includes/js/joomla.javascript.js"></script> 
  <script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_jcomments/js/jcomments-v2.1.js"> </script>
  <script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_jcomments/libraries/joomlatune/ajax.js"> </script>
  <?php $comments = JPATH_ROOT . '/components/com_jcomments/jcomments.php';  
    if (file_exists ( $comments )) {
      require_once $comments;
      echo JComments::showComments ( JRequest::getInt ( 'id' ), COMPONENT, $this->commenttitle [0]->title );
    }
}
/** Check comment id is 3 
 * Inlcude files for jom comment */
if ($cmdid == 3) {  
  require_once JPATH_PLUGINS . DS . 'content' . DS . 'jom_comment_bot.php';
  echo jomcomment ( JRequest::getInt ( 'id' ), "com_contushdvideoshare" );
}

/** Check comment id is 2,  
 * Include js, dispaly default comment */
if ($cmdid == 2 && $id) { ?>

<div class="comment_textcolumn">
	<script type="text/javascript"
		src="<?php echo JURI::base (); ?>components/com_contushdvideoshare/js/membervalidator-min.js"></script>
	<?php /** Form starts here for comment section */ ?> 
	<div class="commentstop clearfix">
		<div class="leave floatleft">
			<span class="comment_txt"><?php
    echo JText::_ ( 'HDVS_COMMENTS' );
    ?></span> (<span id="commentcount"><?php echo $this->commenttitle['totalcomment']; ?></span>)
		</div>
<?php /** Check user id is exist */  
if ($userID != '') { 
/** Display post comment link */
?>
					<div class="commentpost floatright">
			<a onclick="comments();" class="utility-link"><?php echo JText::_('HDVS_POST_COMMENT'); ?></a>
		</div>

				<?php
    } else { /** Display login button to comment  */ ?>
      <div class="commentpost floatright"> <a 
       
<?php if (version_compare ( JVERSION, '1.6.0', 'ge' )) { 
$loginURL = JURI::base() . "index.php?option=com_users&amp;view=login&return=" . base64_encode($currentURL);
  ?>
    href="<?php echo $loginURL; ?>"    
<?php } else { 
$loginURL = JURI::base() . "index.php?option=com_user&amp;view=login&return=" . base64_encode($currentURL);
  ?>
		href="<?php echo $loginURL; ?>" 
<?php } ?> 
 class="utility-link"> <?php echo JText::_ ( 'HDVS_LOGIN_TO_COMMENT' ); ?> </a>
</div>
<?php } ?>
			</div>
<?php if ($id && $catid) {
      $id = $id;
      $cat_id = $catid;
    } ?>
<div id="success"></div>
	<div id="commentdisplay">
		<div id="initial"></div>
		<div id="al"></div>
<?php /** Form ends here
       * Comments display starts here */ ?>
<?php $sum = count ( $this->commenttitle1 );
    if ($sum != 0) { ?>
    <div class="underline"></div>
<?php } 
/** First row here for comment section */ ?>
<?php $page = $_SERVER ['REQUEST_URI'];
    $j = 0;
    
    /** Looping through comment titles */ 
    foreach ( $this->commenttitle1 as $row ) {
      if ($row->parentid == 0) { ?>
						<div class="clearfix">
			<div class="subhead changecomment">
			<?php /** Display commenter name */ ?>
				<span class="video_user_info"> <strong><?php echo $row->name; ?></strong>
					<span class="user_says"> <?php echo JText::_('HDVS_SAYS'); ?> </span>
				</span> <span class="video_user_comment"><?php /** Display comment message */ 
				echo $string = nl2br($row->message); ?></span>
				<span class="video_user_info"> <span class="user_says"> <?php echo JText::_('HDVS_POSTED_ON'); ?> <?php 
				/** Display commented date for non login */
        echo date ( "m-d-Y", strtotime ( $row->created ) );
        ?></span></span>
			</div>
<?php /** Check user id is exist */  
if ($userID != '') { 
/** Display reply link for each comment */ ?>
			<div class="reply changecomment1">
				<a class="cursor_pointer"
					onclick="textdisplay(<?php echo $row->id; ?>);
								parentvalue(<?php
          if ($row->parentid != 0) {
            echo $row->parentid;
          } else {
            echo $row->id;
          }
          ?>)" title="Reply for this comment" value="1" id="hh">
          <?php echo JText::_('HDVS_REPLY'); ?></a>
			</div>
<?php } ?>
		</div>
<?php } else {
  /** Display comment message */
        ?>
						<div class="clsreply clearfix">
						<?php /** Display commenter name */ ?>
			<span class="video_user_info"> <strong><?php echo JText::_('HDVS_RE'); ?> 
								<span><?php echo $row->name; ?></span></strong> <span
				class="user_says"> <?php echo JText::_('HDVS_SAYS'); ?> </span>
			</span> <span class="video_user_comment"><?php echo $string = nl2br($row->message); ?></span>
			<span class="video_user_info"> <span class="user_says"> <?php echo JText::_('HDVS_POSTED_ON'); ?> 
			<?php /** Display commented date */
        echo date ( "m-d-Y", strtotime ( $row->created ) );
        ?></span></span>
		</div>
<?php } ?>
	<div id="<?php 
      if ($row->parentid != 0) {
        echo $row->parentid;
      } else {
        echo $row->id;
      }
      ?>"
			class="initial"></div>
 <?php if ($j < $sum - 1 && $this->commenttitle1 [$j + 1]->parentid == 0) { ?>
							<div class="underline"></div>
<?php }      
      $j ++;
    }
    /** Comments display ends here 
     * Comment pagination starts here */
    ?> <br />
		<table cellpadding="0" cellspacing="0" border="0" id="pagination"
			class="floatright">
			<tr align="right">
				<td align="right" class="page_rightspace">
					<table cellpadding="0" cellspacing="0" border="0" align="right">
						<tr>
<?php /** Call function to display pagination in comment section */  
videosharePagination ( $this->commenttitle , $requestpage); ?>
								</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php /** Comment pagination ends here */ ?>

		<input type="hidden" value="" id="divnum">
<?php /** Get current page url for comment section  */
    $page = 'index.php?option=com_contushdvideoshare&view=commentappend&id=' . JRequest::getInt ( 'id' );
    $hidden_page = '';
/** Get current page number for comment section */
    if ($requestpage) {
      $hidden_page = $requestpage;
    } else {
      $hidden_page = '';
    }  
    /** Display comment append pagination form starts */ ?>
				<form name="pagination_page" id="pagination_page"
			action="<?php echo $page; ?>" method="post">
			<input type="hidden" id="page" name="page"
				value="<?php echo $hidden_page ?>" /> 
		</form>
<?php 
/** Display comment append pagination form starts
 *  Display comment form */ ?>
		<div id="txt">
			<form id="form" name="commentsform"
				action="javascript:insert(<?php
    echo JRequest::getInt ( 'id' );
    ?>)"
				method="post" onsubmit="return validation(this);
					hidebox();">
					<?php /** Display name field */ ?>
				<div class="comment_input">
					<span class="label"> <?php echo JText::_('HDVS_NAME'); ?>  : </span>
					<input type="text" name="username" id="username"
						class="newinputbox commenttxtbox" />
				</div>
<?php /** Display default comment seaction starts */ ?>
				<div class="clear"></div>
				<div class="comment_txtarea">
					<span class="label"><?php echo JText::_('HDVS_COMMENT'); ?>   : </span>
					<?php /** Display testarea field to get comment message */ ?>
					<textarea class="messagebox commenttxtarea" name="comment_message"
						id="comment_message"
						onKeyDown="CountLeft(this.form.comment_message, this.form.left, 500);"
						onpaste="e = this; setTimeout(function(){CountLeft(e, e.form.left, 500);}, 4);"
						onKeyUp="CountLeft(this.form.comment_message, this.form.left, 500);"></textarea>
					<div class="post_comments_section">
					<div class="remaining_character">
						<div class="floatleft" style="margin-top: 2px;">
									<?php echo JText::_('HDVS_REMAINING_CHARECHTER'); ?>&nbsp;:&nbsp;</div>
						<div class="commenttxt">
							<input readonly type="text" name="left" size=1 maxlength=8
								value="500" style="border: none; background: none; width: 70px;" />
						</div>
					</div>
					<?php /** Display submit button to post comment */ ?>
				<div class="comment_bottom">
					<input type="hidden" name="videoid"
						value="<?php echo JRequest::getInt('id'); ?>"
						id="videoid" /> <input type="hidden" name="category"
						value="<?php echo $cat_id; ?>" id="category" /> <input
						type="hidden" name="parentid" value="0" id="parent" /> <input
						type="submit" value="<?php echo JText::_('HDVS_SUBMIT'); ?>"
						class="button clsinputnew" /> <input type="hidden"
						name="postcomment" id="postcomment" value="true"> <input
						type="hidden" value="" id="parentvalue" name="parentvalue" />
				</div>
				</div>
				</div>				
				<?php /** Display loading image for comment append section */ ?>
				<div align="center" id="prcimg" style="display: none;">
					<img
						src="<?php echo JURI::base(); ?>components/com_contushdvideoshare/images/commentloading.gif"
						width="100px">
				</div>
			</form>
			<?php /** Display default comment seaction ends */ ?>
			<br />
			<div id="insert_response" class="msgsuccess"></div>
			<script> document.getElementById('prcimg').style.display = "none";</script>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php
}
