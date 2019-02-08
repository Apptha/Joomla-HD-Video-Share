<?php
/**
 * Category list template file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

JHtml::addIncludePath ( JPATH_COMPONENT . '/helpers/html' );
JHtml::_ ( 'behavior.tooltip' );

$function = JRequest::getCmd ( 'function', 'jSelectArticle' );
define ('CHECKED', 'checked');
/** Call helper function to get joomla function for category page */
$version = getJoomlaVersion ();

if (version_compare ( JVERSION, '1.6.0', 'le' )) {
  ?>
<style>
table tr td a img { width: 16px; }
td.center,th.center,.center { text-align: center; float: none; }
</style>
<?php
}
/** Check task is edit or add */  
if (JRequest::getVar ( 'task' ) == 'edit' || JRequest::getVar ( 'task' ) == 'add') {
/** Display new category add form */
  ?>
<form
	action=<?php echo JRoute::_('index.php?option=com_contushdvideoshare&view=category&layout=categorylist&tmpl=component&function=' . $function); ?>
	method="POST" name="adminForm" id="adminForm"
	<?php
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'style="position: relative;"';
  }
  ?>>
	<fieldset class="adminform">
	<?php /** Display category title */ ?>
		<legend>Category</legend>
		<table class="admintable">
			<tr> <?php /** Display category title */ ?>
				<td class="key">Parent Category</td>
			</tr>
			<tr>
				<td class="key">Category</td>
				<?php /** Display input fields to enter category and parent category title */ ?>
				<td><a class="pointer"
					onclick="if (window.parent)
				window.parent.<?php
  echo $this->escape ( $function );
  ?>('<?php
  echo $val->id;
  ?>',
				'<?php echo $this->escape(addslashes($this->categary->category)); ?>',
				'<?php echo $this->escape($val->id); ?>');">
							<?php echo $this->escape($this->categary->category); ?></a></td>
				<td><input type="text" name="category" id="category" size="32"
					maxlength="250" value="<?php echo $this->categary->category; ?>" /></td>
			</tr>
			<tr>
				<td class="key">Order</td>
				<td><input type="text" name="ordering" id="ordering" size="10"
					maxlength="30" value="<?php echo $this->categary->ordering; ?>" /></td>
			</tr>
			<tr>
			<?php /** Display status field to select */ ?>
				<td class="key">Published</td>
					<?php 
  $categoryChecked = CHECKED;
  $categoryListchecked = '';
  
  if ($this->categary->published == '1') {
    $categoryChecked = CHECKED;
    $categoryListchecked = '';
  } elseif ($this->categary->published == '1') {
    $categoryChecked = '';
    $categoryListchecked = CHECKED;
  } else { $categoryListchecked = '';}
  ?>
					<td><input type="radio" name="published" id="published" value="1"
					<?php echo $categoryChecked; ?> />Yes &nbsp;&nbsp;<input
					type="radio" name="published" id="published" value="0"
					<?php echo $categoryListchecked; ?> />No</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="option"
		value="<?php echo JRequest::getVar('option'); ?>" /> <input
		type="hidden" name="id" value="<?php echo $this->categary->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>
<?php
} else {
  $category = $this->category;
  
  if ($version != '1.5') {
    $linkPath = JRoute::_ ( 'index.php?option=com_contushdvideoshare&view=category&layout=categorylist&tmpl=component&function=' . $function );
  } else {
    $linkPath = 'index.php?option=com_contushdvideoshare&view=category&layout=categorylist&amp;tmpl=component&amp;object=catid';
  }
  /** Category page grid section starts */
  ?>
<form action=<?php echo $linkPath; ?> method="POST" name="adminForm"
	id="adminForm">
	<table
		class="adminlist 
			<?php
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'table table-striped';
  }
  ?>">
  <?php /** Display category page headings */ ?>
		<thead>
			<tr>
				<th>Category</th>
				<th>Ordering Position</th>
				<th>Published</th>
				<th width="10">ID</th>
			</tr>
		</thead>
		<tbody>
<?php
  foreach ( $category ['categorylist'] as $i => $item ) {
    ?>
					<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php /** Display category id and title */ 
    if ($version != '1.5') {
      ?>
			<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level)?>
								<a class="pointer"
					onclick="if (window.parent) window.parent.<?php
      echo $this->escape ( $function );
      ?>('<?php
      echo $item->value;
      ?>',
						'<?php echo $this->escape(addslashes($item->text)); ?>',
						'<?php echo $this->escape($item->value); ?>');">
								<?php echo $this->escape($item->text); ?></a>

								<?php
    } else {
      ?>
								<a style="cursor: pointer;"
					onclick="window.parent.jSelectArticle('<?php echo $item->value; ?>',
											'<?php echo str_replace(array("'", "\""), array("\\'", ""), $item->text); ?>',
											'<?php echo JRequest::getVar('object'); ?>');">
								<?php echo $this->escape($item->text); ?></a>
							<?php
    }
    ?>
						</td>
						<?php /** Display cateory ordering */ ?>
				<td align="center" style="width: 20px;"><?php echo $item->ordering; ?></td>
				<?php /** Display cateory status */ ?>
				<td align="center" style="width: 70px;">
							<?php
    if ($item->published == 1) {
      echo "Published";
    } else {
      echo "Unpublished";
    }
    ?></td>
				<td align="center" style="width: 90px;"><?php echo $item->value; ?></td>
			</tr>
<?php
  }
  ?>

			</tbody>
		<tfoot>
		<?php /** Display pagination for cateory page */ ?>
			<td colspan="15"><?php echo $this->category['pageNav']->getListFooter(); ?></td>
		</tfoot>
	</table>
	<input type="hidden" name="task" value="" /> <input type="hidden"
		name="boxchecked" value="0" /> <input type="hidden"
		name="hidemainmenu" value="0" /> <input type="hidden" name="parent_id"
		value="-1" />
</form>
<?php
}
