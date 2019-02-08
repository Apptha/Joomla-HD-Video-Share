<?php
/**
 * Un Installation file of HD Video Share
 *
 * This file is to un install component, modules and plugin
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access */
defined('_JEXEC') or die('Restricted access');

/** Import Joomla installer library */
jimport('joomla.installer.installer');

if (!defined('DS')){
  define('DS', DIRECTORY_SEPARATOR);
}

/** Get channel directory path
 * Remvoe the folder while uninstalling */
$path = JPATH_ROOT . DS . 'images' . DS . 'channel';
JFolder::delete( $path );

/** Include component helper */
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_category_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_category` TO `#__hdflv_category_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_comments_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_comments` TO `#__hdflv_comments_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_player_settings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_player_settings` TO `#__hdflv_player_settings_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_site_settings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_site_settings` TO `#__hdflv_site_settings_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_upload_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_upload` TO `#__hdflv_upload_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_video_category_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_video_category` TO `#__hdflv_video_category_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_googlead_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_googlead` TO `#__hdflv_googlead_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_ads_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_ads` TO `#__hdflv_ads_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__hdflv_user_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__hdflv_user` TO `#__hdflv_user_backup`");
$db->query();

$conditions = array( $db->quoteName('menutype') . ' = ' . $db->quote('hiddencategorymenu'));
$query->delete($db->quoteName('#__menu'));
$query->where($conditions);
$db->setQuery($query);
$result = $db->execute();



if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$query->select('extension_id')
			->from($db->nameQuote('#__extensions'))
			->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharecategories'));

	$db->setQuery($query, 1);
}
else
{
	$query->select('id')
			->from($db->nameQuote('#__modules'))
			->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharecategories'));

	$db->setQuery($query, 1);
}

$id = $db->loadResult();

if ($id)
{
	$installer = new JInstaller;
	$installer->uninstall('module', $id);
}


if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$query->clear()
			->select('extension_id')
			->from($db->nameQuote('#__extensions'))
			->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharemodules'));

	$db->setQuery($query, 1);
}
else
{
	$query->clear()
			->select('id')
			->from($db->nameQuote('#__modules'))
			->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharemodules'));
	$db->setQuery($query, 1);
}

$id = $db->loadResult();

if ($id)
{
	$installer = new JInstaller;
	$installer->uninstall('module', $id);
}

if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$query->clear()
			->select('extension_id')
			->from($db->nameQuote('#__extensions'))
			->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharesearch'));

	$db->setQuery($query, 1);
}
else
{
	$query->clear()
			->select('id')
			->from($db->nameQuote('#__modules'))
			->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharesearch'));

	$db->setQuery($query, 1);
}

$id = $db->loadResult();

if ($id)
{
	$installer = new JInstaller;
	$installer->uninstall('module', $id);
}

if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$query->clear()
	->select('extension_id')
	->from($db->nameQuote('#__extensions'))
	->where($db->quoteName('type') . ' = ' . $db->quote('module'))
	->where($db->quoteName('element') . ' = ' . $db->quote('mod_videoshare'));

	$db->setQuery($query, 1);
}
else
{
	$query->clear()
	->select('id')
	->from($db->nameQuote('#__modules'))
	->where($db->quoteName('module') . ' = ' . $db->quote('mod_videoshare'));

	$db->setQuery($query, 1);
}

$id = $db->loadResult();

if ($id)
{
	$installer = new JInstaller;
	$installer->uninstall('module', $id);
}

if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$query->clear()
	->select('extension_id')
	->from($db->nameQuote('#__extensions'))
	->where($db->quoteName('type') . ' = ' . $db->quote('module'))
	->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharerss'));

	$db->setQuery($query, 1);
}
else
{
	$query->clear()
	->select('id')
	->from($db->nameQuote('#__modules'))
	->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharerss'));

	$db->setQuery($query, 1);
}

$id = $db->loadResult();

if ($id)
{
	$installer = new JInstaller;
	$installer->uninstall('module', $id);
}

if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$query->clear()
			->select('extension_id')
			->from($db->nameQuote('#__extensions'))
			->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
			->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'))
			->where($db->quoteName('folder') . ' = ' . $db->quote('content'));

	$db->setQuery($query, 1);
}
else
{
	$query->clear()
			->select('id')
			->from($db->nameQuote('#__plugins'))
			->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'));
	$db->setQuery($query, 1);
}

$id = $db->loadResult();

if ($id)
{
	$installer = new JInstaller;
	$installer->uninstall('plugin', $id);
}
?>
<h2 align="center">HDVideo Share UnInstallation Status</h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>        
		<tr class="row0">
			<td class="key" colspan="2"><?php echo JText::_('HD Video Share - Component'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed components
				$query->clear()
						->select('id')
						->from($db->nameQuote('#__hdflv_player_settings_backup'));
				$db->setQuery($query);
				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>        
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'HD Video Share Categories - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed modules
				if (version_compare(JVERSION, '1.6.0', 'ge'))
				{
					$query->clear()
							->select('extension_id')
							->from($db->nameQuote('#__extensions'))
							->where($db->quoteName('type') . ' = ' . $db->quote('module'))
							->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharecategories'));

					$db->setQuery($query, 1);
				}
				else
				{
					$query->clear()
							->select('id')
							->from($db->nameQuote('#__modules'))
							->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharecategories'));

					$db->setQuery($query, 1);
				}

				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>

		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'HD Video Share Moduels - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed modules
				if (version_compare(JVERSION, '1.6.0', 'ge'))
				{
					$query->clear()
							->select('extension_id')
							->from($db->nameQuote('#__extensions'))
							->where($db->quoteName('type') . ' = ' . $db->quote('module'))
							->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharemodules'));

					$db->setQuery($query, 1);
				}
				else
				{
					$query->clear()
							->select('id')
							->from($db->nameQuote('#__modules'))
							->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharemodules'));
					$db->setQuery($query, 1);
				}

				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>

		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share Search - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed modules
				if (version_compare(JVERSION, '1.6.0', 'ge'))
				{
					$query->clear()
							->select('extension_id')
							->from($db->nameQuote('#__extensions'))
							->where($db->quoteName('type') . ' = ' . $db->quote('module'))
							->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharesearch'));
					$db->setQuery($query, 1);
				}
				else
				{
					$query->clear()
							->select('id')
							->from($db->nameQuote('#__modules'))
							->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharesearch'));
					$db->setQuery($query, 1);
				}

				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>
		
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Video Share Player - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed modules
				if (version_compare(JVERSION, '1.6.0', 'ge'))
				{
					$query->clear()
							->select('extension_id')
							->from($db->nameQuote('#__extensions'))
							->where($db->quoteName('type') . ' = ' . $db->quote('module'))
							->where($db->quoteName('element') . ' = ' . $db->quote('mod_videoshare'));
					$db->setQuery($query, 1);
				}
				else
				{
					$query->clear()
							->select('id')
							->from($db->nameQuote('#__modules'))
							->where($db->quoteName('module') . ' = ' . $db->quote('mod_videoshare'));
					$db->setQuery($query, 1);
				}

				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>
		
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share RSS - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed modules
				if (version_compare(JVERSION, '1.6.0', 'ge'))
				{
					$query->clear()
							->select('extension_id')
							->from($db->nameQuote('#__extensions'))
							->where($db->quoteName('type') . ' = ' . $db->quote('module'))
							->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharerss'));
					$db->setQuery($query, 1);
				}
				else
				{
					$query->clear()
							->select('id')
							->from($db->nameQuote('#__modules'))
							->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharerss'));
					$db->setQuery($query, 1);
				}

				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>
		
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HVS Article Plugin - ' . JText::_('Plugin'); ?></td>
			<td style="text-align: center;">
				<?php
				// Check installed modules
				if (version_compare(JVERSION, '1.6.0', 'ge'))
				{
					$query->clear()
							->select('extension_id')
							->from($db->nameQuote('#__extensions'))
							->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
							->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'))
							->where($db->quoteName('folder') . ' = ' . $db->quote('content'));
					$db->setQuery($query, 1);
				}
				else
				{
					$query->clear()
							->select('id')
							->from($db->nameQuote('#__plugins'))
							->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'));
					$db->setQuery($query, 1);
				}

				$id = $db->loadResult();

				if (!$id)
				{
					echo "<strong>" . JText::_('Uninstalled successfully') . "</strong>";
				}
				else
				{
					echo "<strong>" . JText::_('Remove Manually') . "</strong>";
				}
				?>
			</td>
		</tr>
	</tbody>
</table>
