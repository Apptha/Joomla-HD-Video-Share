<?php
/**
 * Site settings template file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */

/** Include component helper  */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Get site settings value */
$editsitesettings = $showsitesettings = $this->sitesettings;
/** Unseiralize thumbview settings */
$thumbview        = unserialize ( $editsitesettings->thumbview );
/** Unseiralize display settings */
$dispenable       = unserialize ( $editsitesettings->dispenable );
/** Unseiralize homepage thumbimage settings */
$homethumbview    = unserialize ( $editsitesettings->homethumbview );
/** Unseiralize module thumbimages settings */
$sidethumbview    = unserialize ( $editsitesettings->sidethumbview );

/** Import tooltip library */
JHTML::_ ( 'behavior.tooltip' );

/** Check Joomla version and based on that include styles */ 
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
<?php /** Include internal css style for version > 3.0.0 */ ?>
    <style type="text/css">
    fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
    .order_pos select { width: 35px; } 
    </style>
<?php } else { ?>
<?php /** Include internal css style */ ?>
<style type="text/css">
    fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
    .order_pos select { width: 50px; }
    table.adminlist input[type="checkbox"],table.adminlist input[type="radio"] { vertical-align: top; }
    table.adminlist input[type="radio"] { margin-right: 5px; }
    table.adminlist .radio_algin { padding-top: 28px; }
   /* For Ellipsis */
    #text_ellipse option{text-overflow: ellipsis;white-space:nowrap;overflow: hidden; }
</style>
<?php  } ?>
<script language="JavaScript" type="text/javascript">
<?php /** Check joomla version. Based on that call script function */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) { ?>
  Joomla.submitbutton = function(pressbutton) {
}
<?php } else { ?> 
  function submitbutton(pressbutton) {
<?php  } ?> 
if (pressbutton) {
  <?php /** Get limitvideo value. */?>  
   var limitvideo = document.getElementById('limitvideo').value;
   <?php /** Validate limit video is integer and not empty */?> 
   if (!limitvideo.match(/^[0-9]+$/) && limitvideo != "" ) {
    <?php /** Prompt users to enter only numbers to limit the video */?> 
      alert('Enter only numbers');
      <?php /** Highlight limitvideo field. */?> 
      document.getElementById('limitvideo').focus(); 
      return false; 
   }
   for (var i = 0; i < document.adminForm.elements.length; i++) { 
      if (document.adminForm.elements[i].type == "text" && document.adminForm.elements[i].style.display != 'none') {
    	  <?php /** Check for all the fields are entered. */?>  
         if (document.adminForm.elements[i].value == "" || document.adminForm.elements[i].value == 0) {
        	 <?php /** Prompt users to ensure that all fields are entered. */?> 
            alert('Please make sure all fields are entered'); 
            return false; 
         }
      }
      <?php /** call function SubmitForm */?>
      submitform(pressbutton); 
      return; 
   } 
 }
}
</script>
<?php /** Sitesettings form start */ ?>
<form action="index.php?option=com_contushdvideoshare&layout=sitesettings" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" style="position: relative;"> 
  <?php /** Fieldset for sitessettings starts */ ?>
  <fieldset class="adminform"> <?php /** compare version to display the page title*/
  if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
    <legend>Site settings</legend> <?php } else { ?> 
    <h2>Site settings</h2> 
    <?php } ?>
    <?php /** sitessettings table starts */ ?>
    <table <?php /** compare version to assign calls to the table */ 
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
        echo 'class="adminlist table table-striped"'; 
      } else { 
        echo 'class="admintable"'; 
      } ?>>
      <?php /** Display comment option */?>
        <tr>
        <td width="300px;">
        <?php echo JHTML::tooltip ( 'Select the commenting system to be displayed in player page', '', '', 'Commenting System' ); ?>
        </td>
        <?php /** Select comment option */
        /** Call 'enablefbapi' function onchange */?>  
        <td colspan="4"> <select name="comment" onchange="enablefbapi(this.value)" style="float: left;">
        <?php /** Default value Comment */?>  
          <option value="0" <?php if ($dispenable ['comment'] == 0) { 
            echo "selected=selected"; 
          } ?>>None</option>
        <?php /** Default Comment */?> 
          <option value="2" <?php if ($dispenable ['comment'] == 2) { 
            echo "selected=selected"; 
          } ?>>Default</option> 
          <?php /** Facebook Comment */?>   
          <option value="1" <?php if ($dispenable ['comment'] == 1) { 
            echo "selected=selected"; 
          } ?>>FaceBookComment</option> 
          <?php /** JComment option */?>
          <?php $jomselected = "";
          /** check if Jcomment option is selected. */ 
          if ($this->jomcomment) { 
            if ($dispenable ['comment'] == 3) { 
              $jomselected = "selected=selected"; 
            }
            echo "<option value='3'" . $jomselected . " >Jom Comment</option>"; 
          } 
          /** declare $jcselected varaible */ 
          $jcselected = "";           
          if ($this->jcomment) { 
            if ($dispenable ['comment'] == 4) { 
              $jcselected = "selected=selected"; 
            }
            echo "<option value='4'" . $jcselected . " >JComment</option>"; 
          } ?> 
           <?php /** Disqus option */?>
          <option value="5" <?php if ($dispenable ['comment'] == 5) { 
            echo "selected=selected"; 
          } ?>>Disqus Comment</option> 
          </select>
           <?php /** Jommal comment describe text. */?>
          <p style="float: left; width: 50%; margin-left: 10px;">If you want to have Jom Comment or JComment as your commenting system for videos, please install them and activate it from here.</p>
        </td>
        </tr>
        <?php /** Display youtube api key field for fb comment option */?>
        <tr id="facebook_api" style="display: none;"> 
         <?php /** Facebook API comment label */?>
          <td> <?php echo JHTML::tooltip ( 'Enter API key for commenting system', 'Facebook API', '', 'Facebook API' ); ?></td> 
          <?php /** Facebook API text field */?>
          <td colspan="4"><input type="text" name="facebookapi" id="facebookapi" style="display: none;" maxlength="100" 
          value=" <?php echo ($dispenable['facebookapi'] && $dispenable['comment'] == '1') ? $dispenable['facebookapi'] : ''; ?>">
          </td>
        </tr> 
        <?php /** Display field to enter disqus name for disqus comment option */?>
        <tr id="disqus_api" style="display: none;">
        <?php /** disqus API label */?> 
          <td> <?php echo JHTML::tooltip ( 'Enter Short name for Disqus commenting system', 'Disqus short name', '', 'Disqus short name' ); ?></td> 
          <?php /** disqus API field */?> 
          <td colspan="4"><input type="text" name="disqusapi" id="disqusapi" style="display: none;" maxlength="100" 
          value=" <?php echo ($dispenable ['disqusapi'] && $dispenable ['comment'] == '5') ? $dispenable ['disqusapi'] : ''; ?>"></td> 
        </tr>
        <?php /** Display row, column for featured videos */?>
        <tr> 
        <?php /** Featured videos label */?>
          <td width="300px;"> <?php echo JHTML::tooltip ( 'Enter row and column for featured videos', '', '', 'Featured Videos' ); ?></td> 
          <?php /** Featured videos Row field */?>
          <td width="200px;">Row : <input type="text" name="featurrow" id="featurrow" maxlength="100" value="<?php echo $thumbview['featurrow']; ?>"> </td> 
           <?php /** Featured videos column field */?>
          <td>Column : <input type="text" id="featurcol" name="featurcol" maxlength="100" value="<?php echo $thumbview['featurcol']; ?>"> </td> 
           <?php /** Featured videos gutterwidth field */?>
          <td>Gutter Width : <input type="text" id="featurwidth" name="featurwidth" maxlength="100" value="<?php echo $thumbview['featurwidth']; ?>"> </td> 
          <?php /** version compare for adding space after feature videos */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
            <td>&nbsp;</td> 
          <?php } ?> 
        </tr>
        <tr>
			<td width="300px;">
			<?php echo JHTML::tooltip('Enter row and column for watch later', 'Watch Later', '', 'Watch Later');
			?></td>
			<td width="200px;">Row : <input type="text" name="watchlaterrow" id="watchlaterrow"
												maxlength="100" value="<?php echo $thumbview['featurrow']; ?>">
				</td><td>Column : <input type="text"  name="watchlatercol" id="watchlatercol"
										 maxlength="100" value="<?php echo $thumbview['featurcol']; ?>">
				</td>
				<td>Gutter Width : <input type="text"  name="watchlaterwidth" id="watchlaterwidth"
										  maxlength="100" value="<?php echo $thumbview['watchlaterwidth']; ?>">
				</td>
<?php
if (version_compare(JVERSION, '3.0.0', 'ge'))
{
?>
					<td>&nbsp;</td>
<?php
}
?>
			</tr>
        <?php /** Display row, column for recent videos */?>
        <tr>
         <?php /** Recent videos label */?>
        <td> <?php echo JHTML::tooltip ( 'Enter row and column for recent videos', '', '', 'Recent Videos' ); ?>
        </td>
         <?php /** Recent videos Row field */?>
        <td>Row : <input type="text" name="recentrow" maxlength="100" id="recentrow"  value="<?php echo $thumbview['recentrow']; ?>"> </td> 
           <?php /** Recent videos column field */?>
          <td>Column : <input type="text" name="recentcol" maxlength="100" id="recentcol"  value="<?php echo $thumbview['recentcol']; ?>"> </td> 
          <?php /** Recent videos gutterspace field */?>
          <td>Gutter Width : <input name="recentwidth" type="text"  id="recentwidth" maxlength="100" value="<?php echo $thumbview['recentwidth']; ?>"> 
          </td>
           <?php /** version compare for adding space after recent videos */
           if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> 
          <?php } ?></tr> <tr>  
<?php 
/** Display row, column for popular videos
 *  popular videos label */?>
        <td> <?php echo JHTML::tooltip ( 'Enter row and column for popular videos', '', '', 'Popular Videos' ); ?></td> 
          <?php /** popular videos row field */?>
          <td>Row : <input type="text" name="popularrow" id="popularrow" maxlength="100" value="<?php echo $thumbview['popularrow']; ?>"> </td> 
           <?php /** popular videos column field */?>
          <td>Column : <input name="popularcol" type="text" id="popularycol" maxlength="100" value="<?php echo $thumbview['popularcol']; ?>"> </td> 
           <?php /** popular videos gutter width field*/?>
          <td>Gutter Width : <input name="popularwidth" type="text" id="popularwidth" maxlength="100" value="<?php echo $thumbview['popularwidth']; ?>">
          <?php /** version compare for adding space after popular videos */?>
          </td>  <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>  <td>&nbsp;</td> 
          <?php } ?> 
        </tr>
        <tr>  
<?php 
/** Display row, column for watch history videos
 *  watch history videos label */?>
        <td> <?php echo JHTML::tooltip ( 'Enter row and column for history videos', '', '', 'History Videos' ); ?></td> 
          <?php /** watch history videos row field */?>
          <td>Row : <input type="text" name="historyrow" id="historyrow" maxlength="100" value="<?php echo $thumbview['historyrow']; ?>"> </td> 
           <?php /** watch history videos column field */?>
          <td>Column : <input name="historycol" type="text" id="historycol" maxlength="100" value="<?php echo $thumbview['historycol']; ?>"> </td> 
           <?php /** watch history popular videos gutter width field*/?>
          <td>Gutter Width : <input name="historywidth" type="text" id="historywidth" maxlength="100" value="<?php echo $thumbview['historywidth']; ?>">
          <?php /** version compare for adding space after watch history videos */?>
          </td>  <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>  <td>&nbsp;</td> 
          <?php } ?> 
        </tr>
        <?php /** Display row, column for category videos */?>
        <tr><?php /** category videos label */?> <td> <?php echo JHTML::tooltip ( 'Enter row and column for category view', '', '', 'Category View' ); ?></td> 
          <?php /** category videos Row field */?> <td>Row : <input type="text" id="categoryrow" name="categoryrow" maxlength="100" 
          value="<?php echo $thumbview['categoryrow']; ?>"> </td> 
          <?php /** category videos column field */?>
          <td>Column : <input type="text" name="categorycol" maxlength="100" id="categorycol" value="<?php echo $thumbview['categorycol']; ?>"> </td> 
          <?php /** category videos gutterwidth field */?>
          <td>Gutter Width : <input  id="categorywidth" type="text" name="categorywidth" maxlength="100" value="<?php echo $thumbview['categorywidth']; ?>"> </td> 
          <?php /** version compare for adding space after category videos */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> 
          <?php } ?> 
        </tr>
         <?php /** Display row, column for playlist videos */?>
        <tr><?php /** playlist videos label */?> <td> <?php echo JHTML::tooltip ( 'Enter row and column for playlist view', '', '', 'Playlist View' ); ?></td> 
          <?php /** playlist videos Row field */?> <td>Row : <input type="text" id="playlistrow" name="playlistrow" maxlength="100" 
          value="<?php echo $thumbview['playlistrow']; ?>"> </td> 
          <?php /** playlist videos column field */?>
          <td>Column : <input type="text" name="playlistcol" maxlength="100" id="playlistcol" value="<?php echo $thumbview['playlistcol']; ?>"> </td> 
          <?php /** category videos gutterwidth field */?>
          <td>Gutter Width : <input  id="playlistwidth" type="text" name="playlistwidth" maxlength="100" value="<?php echo $thumbview['playlistwidth']; ?>"> </td> 
          <?php /** version compare for adding space after category videos */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> 
          <?php } ?> 
        </tr>
        <?php /** Display row, column for search result videos */?>
        <tr> <?php /** searchresult videos label */?><td><?php echo JHTML::tooltip ( 'Enter row and column for search result videos', '', '', 'Search View' ); ?></td> 
          <?php /** searchresult videos row field */?>
          <td>Row : <input type="text" name="searchrow" id="searchrow" maxlength="100" value="<?php echo $thumbview['searchrow']; ?>"> </td> 
           <?php /** searchresult column field */?>
          <td>Column : <input type="text" name="searchcol" id="searchcol" maxlength="100" value="<?php echo $thumbview['searchcol']; ?>"> </td> 
           <?php /** searchresult gutterwidth field */?>
          <td>Gutter Width : <input type="text" name="searchwidth" id="searchwidth" maxlength="100" value="<?php echo $thumbview['searchwidth']; ?>"> </td> 
           <?php /** version compare for adding space after search videos */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
            <td>&nbsp;</td>  <?php } ?> 
        </tr> 
        <?php /** Display row, column for related videos */?>
        <tr> <?php /** related videos label */?><td> <?php echo JHTML::tooltip ( 'Enter row and column for Related videos', '', '', 'Related Videos' ); ?></td> 
          <?php /** relatedvideos row field */?>
          <td>Row : <input id="relatedrow"  type="text" name="relatedrow" maxlength="100" value="<?php echo $thumbview['relatedrow']; ?>"> </td> 
          <?php /** relatedvideos column field */?>
          <td>Column : <input name="relatedcol" type="text" id="relatedcol" value="<?php echo $thumbview['relatedcol']; ?>" maxlength="100"> </td> 
          <?php /** relatedvideos gutterwidth field */?>
          <td>Gutter Width : <input type="text" name="relatedwidth"  maxlength="100" id="relatedwidth" value="<?php echo $thumbview['relatedwidth']; ?>"> </td> 
          <?php /** version compare for adding space after related videos */ ?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>  <td>&nbsp;</td> 
          <?php  } ?> </tr>
          <?php /** Display row, column for myvideos */?>
        <tr> 
        <?php /** myvideos label */?>
          <td> <?php echo JHTML::tooltip ( 'Enter row and column for my videos display', '', '', 'My Videos' ); ?></td> 
          <?php /** myvideos row field */?>
          <td>Row : <input type="text" name="myvideorow"  value="<?php echo $thumbview['myvideorow']; ?>" id="myvideorow" maxlength="100"> </td> 
          <?php /** myvideos column field */?>
          <td>Column : <input type="text" maxlength="100" name="myvideocol" id="myvideocol" value="<?php echo $thumbview['myvideocol']; ?>"> </td> 
          <?php /** myvideos gutterwidth field */?>
          <td>Gutter Width : <input id="myvideowidth" type="text" name="myvideowidth" maxlength="100" value="<?php echo $thumbview['myvideowidth']; ?>"> </td> <td>&nbsp;</td> 
        </tr> 
        <tr> 
        <?php /** Display row, column for member videos */?>
          <?php /** member videos label*/?>
          <td> <?php  echo JHTML::tooltip ( 'Enter row and column for member videos display', '', '', 'Member Page View' ); ?></td> 
          <?php /** myvideos row field */?>
          <td>Row : <input type="text" name="memberpagerow" id="memberpagerow" maxlength="100" value="<?php echo $thumbview['memberpagerow']; ?>"> </td> 
          <?php /** myvideos column field */?>
          <td>Column : <input type="text" name="memberpagecol" id="memberpagecol" maxlength="100" value="<?php echo $thumbview['memberpagecol']; ?>"> </td> 
          <?php /** myvideos gutterwidth field */?>
          <td>Gutter Width : <input type="text" name="memberpagewidth" id="memberpagewidth" maxlength="100" value="<?php echo $thumbview['memberpagewidth']; ?>"> </td> 
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> <td>&nbsp;</td> <?php 
          } ?>  </tr> 
          <?php /** Display row, column for popular videos module */?>
        <tr><?php /** popular videos module label */?> <td> <?php echo JHTML::tooltip ( 'Enter row and column for popular videos module', '', '', 'Side Popular Videos' ); ?></td>
          <?php /** set row field for popular videos module */?>
          <td>Row : <input type="text" name="sidepopularvideorow" id="sidepopularvideorow" maxlength="100" value="<?php echo $sidethumbview['sidepopularvideorow']; ?>"> </td> 
            <?php /** compare version to set the attribute colspan for popular video module */?>
            <?php /** set column field for popular videos module */?>
          <td <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : <input type="text" name="sidepopularvideocol" id="sidepopularvideocol" maxlength="100" 
          value="<?php echo $sidethumbview['sidepopularvideocol']; ?>"> 
          </td>
           <?php /** version compare for adding space after popular videos module */ ?> 
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> <td>&nbsp;</td> <td>&nbsp;</td> 
          <?php } ?> 
        </tr> 
        <tr>
			<td>
			<?php echo JHTML::tooltip('Enter row and column for watch later module', 'Side watch later', '', 'Side watch later');
			?></td>
			<td>Row : <input type="text" name="sidewatchlaterrow" id="sidewatchlaterrow"
								 maxlength="100"
								 value="<?php echo $sidethumbview['sidewatchlaterrow']; ?>">
			</td>
			<td
					<?php
					if (!version_compare(JVERSION, '3.0.0', 'ge'))
					{
						echo 'colspan="3"';
					}
				?>
					>Column : <input type="text" name="sidewatchlatercol"
				id="sidewatchlatercol" maxlength="100"
				value="<?php echo $sidethumbview['sidewatchlatercol']; ?>">
				</td>
					<?php
					if (version_compare(JVERSION, '3.0.0', 'ge'))
					{
						?>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
					<?php
					}
				?>
			</tr>
			<?php /** Display row, column for watch history videos side module */?>
        <tr><?php /** watch history videos module label */?> <td> <?php echo JHTML::tooltip ( 'Enter row and column for history videos module', '', '', 'Side History Videos' ); ?></td> 
          <?php /** set row field for watch history videos side module */?> 
          <td>Row : <input  maxlength="100" type="text" name="sidehistoryvideorow" id="sidehistoryvideorow" value="<?php echo $sidethumbview['sidehistoryvideorow']; ?>"> </td> 
           <?php /** set column field for watch history videos module */?>
            <?php /** compare version to assign the colspan attribute for watch history module */?>  
          <td <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : 
          <input type="text" name="sidehistoryvideocol" maxlength="100" id="sidehistoryvideocol" value="<?php echo $sidethumbview['sidehistoryvideocol']; ?>"> 
          </td>  <?php /** compare version to add space after watch history module */?>  <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> <td>&nbsp;</td> 
        <?php } ?> </tr>
        <?php /** Display row, column for featured videos module */?>
        <tr><?php /** featured videos module label */?> <td> <?php echo JHTML::tooltip ( 'Enter row and column for featured videos module', '', '', 'Side Featured Videos' ); ?></td> 
          <?php /** set row field for featured videos module */?> 
          <td>Row : <input  maxlength="100" type="text" name="sidefeaturedvideorow" id="sidefeaturedvideorow" value="<?php echo $sidethumbview['sidefeaturedvideorow']; ?>"> </td> 
           <?php /** set column field for featured videos module */?>
            <?php /** compare version to assign the colspan attribute for featured module */?>  
          <td <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : 
          <input type="text" name="sidefeaturedvideocol" maxlength="100" id="sidefeaturedvideocol" value="<?php echo $sidethumbview['sidefeaturedvideocol']; ?>"> 
          </td>  <?php /** compare version to add space after featured module */?>  <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> <td>&nbsp;</td> 
        <?php } ?> </tr> 
        <tr> <?php /** Related videos module label */?>  <td> <?php echo JHTML::tooltip ( 'Enter row and column for related videos module', '', '', 'Side Related Videos' ); ?></td> 
          <?php /** set row for related video module. */?> 
          <td>Row : <input type="text" name="siderelatedvideorow" id="siderelatedvideorow" maxlength="100" value="<?php echo $sidethumbview['siderelatedvideorow']; ?>"> </td> 
            <?php /** Compare version to assign colspan to related videos module */?>
           <?php /** set column for related video module. */?>
          <td <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : <input type="text" name="siderelatedvideocol" id="siderelatedvideocol" maxlength="100" 
          value="<?php echo $sidethumbview['siderelatedvideocol']; ?>"> </td> 
           <?php /** Compare version to add space after related videos module. */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> <td>&nbsp;</td> <?php 
          } 
          ?> </tr> 
          <?php /** Display row, column for recent videos module */?>
        <tr><?php /** recent video module label. */?> <td> <?php echo JHTML::tooltip ( 'Enter row and column for recent videos module', '', '', 'Side Recent Videos' ); ?></td> 
          <?php /** set row for recent video module. */?>
          <td>Row : <input type="text" name="siderecentvideorow" id="siderecentvideorow" maxlength="100" value="<?php echo $sidethumbview['siderecentvideorow']; ?>"> </td> 
          <?php /** set column for recent video module. */?>
          <?php /** compare version to assign colspan attribute to recent videos module. */?>
          <td <?php  if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : <input type="text" name="siderecentvideocol" id="siderecentvideocol" maxlength="100" value="<?php echo $sidethumbview['siderecentvideocol']; ?>"> </td> 
          <?php /** compare verison to add space after recent video module. */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> <td>&nbsp;</td> 
          <?php } ?> 
        </tr> 
        <?php /** Display row, column for random videos module */?>
        <?php /** Random video module label */?>
        <tr> <td> <?php echo JHTML::tooltip ( 'Enter row and column for random videos module', '', '', 'Side Random Videos' ); ?></td> 
        <?php /** set row field for random videos module */?>
          <td>Row : <input name="siderandomvideorow" type="text" id="siderandomvideorow" maxlength="100" value="<?php if (isset ( $sidethumbview ['siderandomvideorow'] )) { 
            echo $sidethumbview ['siderandomvideorow']; 
          } ?>"> </td>
          <?php /** compare version to assing colspan attirbute to random video module.  */?>
          <?php /** set column field for random videos module*/?> 
          <td <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : <input type="text" id="siderandomvideocol" maxlength="100" name="siderandomvideocol"  value="<?php if (isset ( $sidethumbview ['siderandomvideocol'] )) { 
          echo $sidethumbview ['siderandomvideocol']; 
          } ?>"> </td> 
          <?php /** Compare version to add space after random video */?>
          <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td> <td>&nbsp;</td> 
          <?php  } ?> </tr>  
          <?php /** Display row, column for category videos module */?>
           <?php /** Category video module label */?>
          <tr> <td> <?php  echo JHTML::tooltip ( 'Enter row and column for category videos module', '', '', 'Side Category Videos' ); ?></td> 
          <?php /** set row for category video module. */?>
          <td>Row : <input type="text" name="sidecategoryvideorow" id="sidecategoryvideorow" maxlength="100" value="<?php if (isset ( $sidethumbview ['sidecategoryvideorow'] )) { 
            echo $sidethumbview ['sidecategoryvideorow']; 
          } ?>"> </td>
          <?php /** compare version to assign colspan attribute */?>
          <?php /** set column for category video module. */?> 
          <td <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'colspan="3"'; 
          } ?>>Column : <input type="text" name="sidecategoryvideocol" id="sidecategoryvideocol" maxlength="100" 
          value="<?php if (isset ( $sidethumbview ['sidecategoryvideocol'] )) { 
            echo $sidethumbview ['sidecategoryvideocol']; 
          } ?>"> </td>
          <?php /** compare version to add space after category video module. */?>
           <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          <td>&nbsp;</td>  <td>&nbsp;</td> 
          <?php } ?> 
          </tr> 
          <?php /** Display row, column for popular videos in home page */?>
          <tr> <td> <?php  echo JHTML::tooltip ( 'Enter row and column for popular videos in home page', '', '', 'Home Page Popular Videos' ); ?></td> 
           <?php /** Set row for hoempage popular videos. */?>
          <td>Row : <input type="text" name="homepopularvideorow" id="homepopularvideorow" maxlength="100" value="<?php echo $homethumbview['homepopularvideorow']; ?>"> 
        <?php /** Set column for hoempage popular videos. */?>
        <?php /** compare version to assign colspan attr.*/?>
          <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
          </td> <td> <?php } ?> Column : <input type="text" name="homepopularvideocol" id="homepopularvideocol" maxlength="100" value="<?php echo $homethumbview['homepopularvideocol']; ?>"> 
          <?php /** Set gutterwidth field for hoempage popular videos. */?>
          </td> <td>Gutter Width : <input type="text" name="homepopularvideowidth" id="homepopularvideowidth" maxlength="100" value="<?php echo $homethumbview['homepopularvideowidth']; ?>"> </td> 
          <?php /** compare version  to add space after hoempage popular videos */?>
          <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'class="radio_algin"'; 
          } ?>>
          <?php /** Display enable / disbale popular videos in home page */?>
          <?php /** Popular videos enable option */?>
          <input type="radio" name="homepopularvideo" <?php if ($homethumbview ['homepopularvideo'] == 1) { 
            echo 'checked="checked" '; 
          } ?> value="1" />Enable
          <?php /** Popular videos disable option */?> 
          <input type="radio" name="homepopularvideo" <?php if ($homethumbview ['homepopularvideo'] == 0) { 
            echo 'checked="checked" '; 
          } 
          ?> value="0" />Disable</td>
          <?php /** Select option to order popular video in the homepage */?> 
          <td class="order_pos">Order : <select name="homepopularvideoorder"> 
          <option value="1" <?php if ($homethumbview ['homepopularvideoorder'] == 1) { 
            echo "selected=selected"; 
          } ?>>1</option> 
          <option value="2" <?php if ($homethumbview ['homepopularvideoorder'] == 2) { 
            echo "selected=selected"; 
          } 
          ?>>2</option> 
          <option value="3" <?php if ($homethumbview ['homepopularvideoorder'] == 3) { 
            echo "selected=selected"; 
          } ?>>3</option> 
          </select> </td> </tr> 
          <?php /** Display row, column for featured videos in home page */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Enter row and column for featured videos in home page', '', '', 'Home Page Featured Videos' ); ?></td> 
          <?php /** set row for hoepage featured video */?>
          <td>Row : <input type="text" name="homefeaturedvideorow" id="homefeaturedvideorow" maxlength="100" value="<?php echo $homethumbview['homefeaturedvideorow']; ?>"> 
          <?php /** Compare version to  assign colspan attribute to hoempage feature video */?>
          <?php /** set column for homepage featured video module. */?>
          <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          </td> <td> <?php 
          } ?> Column : <input type="text" name="homefeaturedvideocol" id="homefeaturedvideocol" maxlength="100" value="<?php echo $homethumbview['homefeaturedvideocol']; ?>"> 
          </td>
          <?php /** set gutter width for homepage featured video */?>
          <td>Gutter Width : <input type="text" name="homefeaturedvideowidth" id="homefeaturedvideowidth" maxlength="100" value="<?php echo $homethumbview['homefeaturedvideowidth']; ?>"> </td> 
          <?php /** Display enable / disbale featured videos in home page */?>
          <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'class="radio_algin"'; 
          } ?>><?php /** featured video enable option */?><input type="radio" name="homefeaturedvideo" <?php if ($homethumbview ['homefeaturedvideo'] == 1) { 
            echo 'checked="checked" '; 
          } ?> value="1" />Enable 
          <?php /** featured videos disable option */?>
          <input type="radio" name="homefeaturedvideo" <?php if ($homethumbview ['homefeaturedvideo'] == 0) { 
            echo 'checked="checked" '; 
          } ?> value="0" />Disable</td>
          <?php /** feature videos ordering option */?>
           <td class="order_pos">Order : <select name="homefeaturedvideoorder"> 
          <option value="1" <?php if ($homethumbview ['homefeaturedvideoorder'] == 1) { 
            echo "selected=selected"; 
          } ?>>1</option> 
          <option <?php if ($homethumbview ['homefeaturedvideoorder'] == 2) { 
            echo "selected=selected"; 
          }  ?> value="2">2</option> 
          <option value="3" <?php if ($homethumbview ['homefeaturedvideoorder'] == 3) { 
            echo "selected=selected"; 
          } ?>>3</option></select> 
          </td> </tr> 
          <?php /** Display row, column for recent videos in home page */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Enter row and column for recent videos in home page', '', '', 'Home Page Recent Videos' ); ?></td> 
          <?php /** set row recent videos in home page */?>
          <td>Row : <input type="text" name="homerecentvideorow" id="homerecentvideorow" maxlength="100" value="<?php echo $homethumbview['homerecentvideorow']; ?>"> 
         <?php /** compare version to assign colspan attribute to the homepage recent videos */?>
          <?php /** set column recent videos in home page */?>
          <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
          </td> <td> <?php } ?> Column : <input type="text" name="homerecentvideocol" id="homerecentvideocol" maxlength="100" value="<?php echo $homethumbview['homerecentvideocol']; ?>"> 
          </td>
          <?php /** set gutterwidth recent videos in home page */?>
           <td>Gutter Width : <input type="text" name="homerecentvideowidth" id="homerecentvideowidth" maxlength="100" value="<?php echo $homethumbview['homerecentvideowidth']; ?>"> 
          </td> <?php /** Display enable / disbale recent videos in home page */?> 
          <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'class="radio_algin"'; 
          } ?>><input type="radio" name="homerecentvideo" <?php if ($homethumbview ['homerecentvideo'] == 1) { 
              echo 'checked="checked" '; 
          } ?> value="1" />Enable <input type="radio" name="homerecentvideo" <?php if ($homethumbview ['homerecentvideo'] == 0) { 
            echo 'checked="checked" '; 
          } ?> value="0" />Disable</td> 
          <td class="order_pos">Order : <select name="homerecentvideoorder"> 
          <option value="1" <?php if ($homethumbview ['homerecentvideoorder'] == 1) { 
            echo "selected=selected"; 
          } ?>>1</option> 
          <option value="2" <?php if ($homethumbview ['homerecentvideoorder'] == 2) { 
            echo "selected=selected"; 
          } ?>>2</option> 
          <option value="3"  <?php if ($homethumbview ['homerecentvideoorder'] == 3) { 
            echo "selected=selected"; 
          } ?>>3</option> </select> </td> 
          </tr> 
          <?php /** Display enable / disbale video upload option */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable video upload option to members', '', '', 'Video Upload Option to Members' ); ?></td> 
            <td><input type="radio" name="allowupload" id="allowupload" <?php if ($dispenable ['allowupload'] == '1' || $dispenable ['allowupload'] == '') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> 
            <td colspan="3"><input type="radio" name="allowupload" id="allowupload" <?php if ($dispenable ['allowupload'] == '0') { 
              echo 'checked="checked" '; 
            } ?> value="0" />No</td> </tr>
            <!----------- Site playlist Create User  ----------->
			<tr>
					<td>
						<?php echo JHTML::tooltip('Enter the limit for create max playlist in front end user',
								'Limit playlist for front End user', '', 'Limit playlist for front End user'); ?></td>
					<td colspan="3"><input name="playlist_limit" id="playlist_limit" class="inputbox" maxlength="6" value="<?php
						if( isset($dispenable['playlist_limit'] ) )  { echo $dispenable['playlist_limit']; } else { echo '5';} 
						?>">
					</td>
		    </tr>
            <?php /** Display enable / disbale admin approve option */?> 
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable admin approval from member videos', '', '', 'Admin approval for Member videos' ); ?> 
            </td> <td><input type="radio" name="adminapprove" id="adminapprove" 
            <?php if (isset ( $dispenable ['adminapprove'] ) && ($dispenable ['adminapprove'] == '0' || $dispenable ['adminapprove'] == '')) { 
              echo 'checked="checked" '; 
            } ?> value="0" />Yes</td> 
            <td colspan="3"><input type="radio" name="adminapprove" id="adminapprove" <?php if (isset ( $dispenable ['adminapprove'] ) && $dispenable ['adminapprove'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />No</td> </tr> 
            <?php /** Display enable / disbale login register option */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable member Login/Register', '', '', 'Members Login/Register' ); ?></td> 
            <td><input type="radio" name="user_login" id="allowupload" <?php if ($dispenable ['user_login'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> 
            <td colspan="3"><input type="radio" name="user_login" id="allowupload" <?php if ($dispenable ['user_login'] == '0') { 
              echo 'checked="checked" '; 
            } ?> 
            value="0" />No</td> </tr> 
            <?php /** Display enable / disbale video ratings option */?>
          <tr> <td> <?php  echo JHTML::tooltip ( 'Option to enable/disable display ratings', '', '', 'Display Ratings' ); ?></td> 
            <td><input type="radio" name="ratingscontrol" id="ratingscontrol" <?php if ($dispenable ['ratingscontrol'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> <td colspan="3"><input type="radio" name="ratingscontrol" id="ratingscontrol" 
            <?php if ($dispenable ['ratingscontrol'] == '0') { 
              echo 'checked="checked" '; 
            } ?> value="0" />No</td> </tr> 
            <?php /** Display enable / disbale video views option */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable views display', '', '', 'Display Viewed' ); ?> 
            </td> <td><input type="radio" name="viewedconrtol" id="viewedconrtol" <?php if ($dispenable ['viewedconrtol'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> <td colspan="3">
            <input type="radio" name="viewedconrtol" id="viewedconrtol" <?php if ($dispenable ['viewedconrtol'] == '0') { 
              echo 'checked="checked" '; 
              } ?> value="0" />No</td> 
          </tr> 
          <?php /** Display enable / disbale video report option */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable Report video', '', '', 'Report Video' ); ?> </td> 
            <td><input type="radio" name="reportvideo" id="reportvideo" <?php 
            if (isset ( $dispenable ['reportvideo'] ) && $dispenable ['reportvideo'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> <td colspan="3"><input type="radio" name="reportvideo" id="reportvideo" <?php 
            if (isset ( $dispenable ['reportvideo'] ) && $dispenable ['reportvideo'] == '0') { 
              echo 'checked="checked" '; 
            } ?> value="0" />No</td> </tr> 
            <?php /** Display enable / disbale video player option on home page */?>
          <tr> <td>  <?php echo JHTML::tooltip ( 'Option to enable/disable player on video home page', '', '', 'Display player on video home page' ); ?> 
          </td> <td><input type="radio" name="homeplayer" id="homeplayer" <?php 
          if (isset ( $dispenable ['homeplayer'] ) && $dispenable ['homeplayer'] == '1') { 
            echo 'checked="checked" '; 
          } ?> value="1" />Yes</td> <td colspan="3"><input type="radio" name="homeplayer" id="homeplayer" 
          <?php if (isset ( $dispenable ['homeplayer'] ) && $dispenable ['homeplayer'] == '0') { 
            echo 'checked="checked" '; 
          } ?> value="0" />No</td> </tr> 
          <?php /** Display enable / disbale video player option on category page */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable player on category page', '', '', 'Display player on category page' ); ?> </td> 
            <td><input type="radio" name="categoryplayer" id="categoryplayer" <?php if (isset ( $dispenable ['categoryplayer'] ) && $dispenable ['categoryplayer'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> 
            <td colspan="3"><input type="radio" name="categoryplayer" id="categoryplayer" <?php if (isset ( $dispenable ['categoryplayer'] ) && $dispenable ['categoryplayer'] == '0') { 
              echo 'checked="checked" '; 
            } ?> value="0" />No</td> 
          </tr> 
          <?php /** Display field to limit related videos inside the player */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to limit related videos inside the player', '', '', 'Limit related videos inside the player' ); ?> 
            </td> <td colspan="4"><input type="text" name="limitvideo" id="limitvideo" 
            value="<?php if (isset ( $dispenable ['limitvideo'] )) { 
              echo $dispenable ['limitvideo']; 
            } ?>" /></td> </tr> 
          <?php /** Display field to limit related videos outside the player */?>  
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to limit related videos inside the player', '', '', 'Youtube API' ); ?> </td> 
            <td colspan="4"><input type="text" name="youtubeapi" id="youtubeapi" value="<?php if (isset ( $dispenable ['youtubeapi'] )) { 
              echo $dispenable ['youtubeapi']; 
            } ?>" /></td> </tr> 
            <?php /** Display field to display social icons */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to enable/disable social icons display', '', '', 'Display Social Links' ); ?></td> 
            <td><input type="radio" name="facebooklike" id="facebooklike" <?php if ($dispenable ['facebooklike'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> 
            <?php /** Display field for fb like option */?>
            <td colspan="3"><input type="radio" name="facebooklike" id="facebooklike" 
            <?php if ($dispenable ['facebooklike'] == '0') { 
              echo 'checked="checked" '; 
            } ?> value="0" />No</td> 
          </tr> 
          <?php /** Display enable / disbale rss icon */?>
          <tr>  <td> <?php echo JHTML::tooltip ( 'Option to enable/disable RSS icons display', '', '', 'Display RSS Icon' ); ?></td> 
            <td><input type="radio" name="rssfeedicon" id="rssfeedicon" <?php if (isset ( $dispenable ['rssfeedicon'] ) && $dispenable ['rssfeedicon'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> 
            <td colspan="3"><input value="0" type="radio" name="rssfeedicon" id="rssfeedicon" 
            <?php if (isset ( $dispenable ['rssfeedicon'] ) && $dispenable ['rssfeedicon'] == '0') { 
              echo 'checked="checked" '; 
            } ?> />No</td> </tr> 
            <?php /** Display enable / disbale to store videos in s3 bucket */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to store videos in Amazon S3 bucket', '', '', 'Store videos in Amazon S3 bucket' ); 
            ?></td> <td><input type="radio" name="amazons3" id="amazons3" onclick="dispenable(1);" 
            <?php if (isset ( $dispenable ['amazons3'] ) && $dispenable ['amazons3'] == '1') { 
              echo 'checked="checked" '; 
            } ?> value="1" />Yes</td> 
            <td colspan="3"><input type="radio" name="amazons3" onclick="dispenable(0);" id="amazons3" <?php if (isset ( $dispenable ['amazons3'] ) && $dispenable ['amazons3'] == '0') { 
              echo 'checked="checked" '; 
            } ?> value="0" />No</td> </tr> 
          <tr id="amazons3name_area" style="display: none;"> 
          <?php /** Display field to get bucket name */?>
            <td> <?php echo JHTML::tooltip ( 'Enter Amazon S3 bucket name', '', '', 'Enter Amazon S3 bucket name' ); 
            ?></td> <td colspan="4"><input type="text" name="amazons3name" style="display: none;" id="amazons3name" maxlength="100" value="<?php if (isset ( $dispenable ['amazons3name'] )) { 
              echo $dispenable ['amazons3name'];
            } ?>"></td> </tr>
            <?php /** Display field to get bucket link */?> 
          <tr id="amazons3link_area" style="display: none;"> <td> <?php echo JHTML::tooltip ( 'Enter Amazon S3 bucket link', '', '', 'Enter Amazon S3 bucket link' ); 
            ?></td> <td colspan="4"><input type="text" name="amazons3link" style="display: none;" id="amazons3link" maxlength="100" 
            value="<?php if (isset ( $dispenable ['amazons3link'] )) { 
              echo $dispenable ['amazons3link']; 
            } ?>"></td> </tr>
          <tr id="amazons3accesskey_area" style="display: none;">
          <?php /** Display field to get bucket access key */?> 
            <td> <?php echo JHTML::tooltip ( 'Enter Amazon S3 bucket access key', '', '', 'Enter Amazon S3 bucket access key' ); 
            ?></td> <td colspan="4"><input type="text" name="amazons3accesskey" style="display: none;" id="amazons3accesskey" maxlength="100" 
            value="<?php if (isset ( $dispenable ['amazons3accesskey'] )) { 
              echo $dispenable ['amazons3accesskey']; 
            } ?>"></td> 
          </tr> 
          <tr id="amazons3accesssecretkey" style="display: none;"> 
          <?php /** Display field to get bucket secret key */?>
            <td> <?php echo JHTML::tooltip ( 'Enter Amazon S3 bucket access secret key', '', '', 'Enter Amazon S3 bucket access secret key' ); 
            ?></td> <td colspan="4"><input type="text" name="amazons3accesssecretkey_area" style="display: none;" id="amazons3accesssecretkey_area" maxlength="100" 
            value="<?php if (isset ( $dispenable ['amazons3accesssecretkey_area'] )) { 
              echo $dispenable ['amazons3accesssecretkey_area']; 
            } ?>"></td> </tr> 
            <?php /** Display enable disable option for seo url */?>
          <tr> <td> <?php  echo JHTML::tooltip ( 'Option to enable/disable search engine friendly url', '', '', 'Search Engine Friendly URLs' ); 
            ?></td> <td><input type="radio" name="seo_option" <?php if ($dispenable ['seo_option'] == 1) { 
              echo 'checked="checked" '; 
            } ?> value="1" />Enable</td> <td colspan="3"><input type="radio" name="seo_option" <?php  if ($dispenable ['seo_option'] == 0) { 
            echo 'checked="checked" '; 
            } ?> value="0" />Disable</td> </tr> 
            <?php /** Display field to limit upload method for users */?>
          <tr> <td> <?php echo JHTML::tooltip ( 'Option to limit upload method for front end users', '', '', 'Select upload method(s) for users' ); ?></td> 
          <td colspan="4"> <?php $separate_values = explode ( ',', $dispenable ['upload_methods'] ); 
          for($i = 0; $i < count ( $separate_values ); $i ++) { 
            $upload_methods [$separate_values [$i]] = $separate_values [$i]; 
          } ?> 
          <select id="text_ellipse" multiple name="upload_methods[]"> <option value="Upload" <?php if (isset ( $upload_methods ['Upload'] )) { 
            echo 'selected'; 
          } ?>>Upload</option> <option value="Youtube" <?php if (isset ( $upload_methods ['Youtube'] )) { 
            echo 'selected'; 
          } ?>>Youtube/Vimeo/Viddler/Dailymotion</option> <option value="URL" <?php if (isset ( $upload_methods ['URL'] )) { 
            echo 'selected'; 
          } ?>>URL</option> 
          <option value="RTMP" <?php if (isset ( $upload_methods ['RTMP'] )) { 
            echo 'selected'; 
          } ?>>RTMP</option> 
          <option value="FFMPEG" <?php if (isset ( $upload_methods ['FFMPEG'] )) { 
            echo 'selected'; 
          } ?>>FFMPEG</option> <option value="Embed" <?php if (isset ( $upload_methods ['Embed'] )) { 
            echo 'selected'; 
          } ?>>Embed Code</option> </select> </td> 
          </tr> </table>
          <?php /** sitessettings table ends */ ?> 
        </fieldset> 
        <?php /** Fieldset for sitessettings ends */ ?>
        <input type="hidden" name="id" value="<?php echo $editsitesettings->id; ?>" /> 
        <input type="hidden" name="published" id="published" value="1" /> 
        <input type="hidden" name="task" value="" /> <input type="hidden" name="submitted" value="true" id="submitted">
</form>
<?php /** Sitesettings form end */ ?>
<script> if (<?php echo $dispenable['comment']; ?> == 1)  { 
   enablefbapi('1'); 
   } else if (<?php echo $dispenable['comment']; ?> == 5) { 
      enablefbapi('5');
   } 
<?php if (isset ( $dispenable ['amazons3'] ) && $dispenable ['amazons3'] == '1') { ?> 
    dispenable(1);
<?php } else { ?> 
    dispenable(0);
<?php } ?> 
<?php /** Function to disply comments section based on the option selected */?>
function enablefbapi(val) { 
  <?php /** check if Facebook API option is selected. */?>
   if (val == 1) { 
  <?php /** display Facebook API field */?>
      document.getElementById('facebook_api').style.display = '';
      <?php /** display Facebook API Label */?> 
      document.getElementById('facebookapi').style.display = ''; 
      <?php /** Hide Disqus API field */?>
      document.getElementById('disqus_api').style.display = 'none';
      <?php /** Hide Disqus API label */?> 
      document.getElementById('disqusapi').style.display = 'none'; 
   } else if (val == 5) { 
    <?php /** Display Disqus API field, if comment option 'Disqus' is set */?>
      document.getElementById('disqus_api').style.display = '';
      <?php /** Display Disqus API label, if comment option 'Disqus' is set */?>  
      document.getElementById('disqusapi').style.display = '';
      <?php /** hide Facebook API field, if comment option 'Disqus' is set */?> 
      document.getElementById('facebook_api').style.display = 'none';
      <?php /** hide Facebook API label, if comment option 'Disqus' is set */?> 
      document.getElementById('facebookapi').style.display = 'none'; 
   } else {
      <?php /** hide Facebook API field, if comment option 'Default' is set */?>  
      document.getElementById('facebook_api').style.display = 'none'; 
      <?php /** hide Facebook API label, if comment option 'Default' is set */?> 
      document.getElementById('facebookapi').style.display = 'none';
      <?php /** Hide Disqus API field, if comment option 'Default' is set */?> 
      document.getElementById('disqus_api').style.display = 'none';
      <?php /** Hide Disqus API label, if comment option 'Default' is set */?>  
      document.getElementById('disqusapi').style.display = 'none'; 
   }
}
function dispenable(status) { 
   if (status == 1)  { 
      document.getElementById("amazons3name_area").style.display = ''; 
      document.getElementById("amazons3link_area").style.display = ''; 
      document.getElementById("amazons3name").style.display = ''; 
      document.getElementById("amazons3link").style.display = ''; 
      document.getElementById("amazons3accesskey_area").style.display = ''; 
      document.getElementById("amazons3accesskey").style.display = ''; 
      document.getElementById("amazons3accesssecretkey").style.display = ''; 
      document.getElementById("amazons3accesssecretkey_area").style.display = ''; 
    } else { 
       document.getElementById("amazons3name_area").style.display = "none"; 
       document.getElementById("amazons3link_area").style.display = "none"; 
       document.getElementById("amazons3name").style.display = "none"; 
       document.getElementById("amazons3link").style.display = "none"; 
       document.getElementById("amazons3accesskey_area").style.display = "none"; 
       document.getElementById("amazons3accesskey").style.display = "none"; 
       document.getElementById("amazons3accesssecretkey").style.display = "none"; 
       document.getElementById("amazons3accesssecretkey_area").style.display = "none"; 
     } 
} 
</script>
