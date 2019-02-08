/**
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 * @Creation Date   March 2010
 * @Modified Date   September 2015
 * */

function addQueue(whichForm)
{
    uploadqueue.push(whichForm);
    if (uploadqueue.length == 1)
        processQueue();
    else
        holdQueue();
}
