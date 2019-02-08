/**
 * Ads layout js file
 * 
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

/**
 * function to hide Preroll/Post Roll onload page
 */

if (document.getElementById('selectadd01').checked == true) {

	document.getElementById('typeofadd').value = 'prepost';

	if (document.getElementById('filepath01').checked == true) {
		adsflashdisable();

	} else if (document.getElementById('filepath02').checked == true) {
		urlenable();
	}

} else if (document.getElementById('selectadd02').checked == true) {
	document.getElementById('typeofadd').value = 'mid';
	adsflashdisable();
}

/**
 * function to hide Preroll/Post Roll
 */
function urlenable() {
	document.getElementById('postrollnf').style.display = 'none';
	document.getElementById('postrollurl').style.display = '';
}

/**
 * function to hide Upload Preroll/Post Roll
 */

function adsflashdisable() {
	document.getElementById('postrollnf').style.display = '';
	document.getElementById('postrollurl').style.display = 'none';
}

/**
 * function to hide Preroll/Post Roll and Upload Preroll/Post Roll on Onclick
 */

function fileads(filepath) {
	if (filepath == "File") {
		adsflashdisable();
		document.getElementById('fileoption').value = 'File';
	}
	if (filepath == "Url") {
		urlenable();
		document.getElementById('fileoption').value = 'Url';
	}
}

/**
 * function to select ad type on Onclick
 */

 function checkadd(recadd)
    { 
        if(recadd=="prepost")
        {
            addsetenable();
            addimasetdisable();
            document.getElementById('typeofadd').value='prepost';
        }
        if(recadd=="mid")
        {
            addsetdisable();
            addimasetdisable();
            document.getElementById('typeofadd').value='mid';
        }
        if(recadd=="ima")
        {
            addsetdisable();
            addimasetenable();
            document.getElementById('typeofadd').value='ima';
        }

    }
 function addsetenable()
    {
        document.getElementById('videodet').style.display='';
        document.getElementById('namead').style.display='';
        document.getElementById('descriptionad').style.display='';
        document.getElementById('urltarget').style.display='';
        document.getElementById('urlclick').style.display='';
        document.getElementById('impress').style.display='';
    }
    function addsetdisable()
    {

        document.getElementById('videodet').style.display='none';
        document.getElementById('videodetima').style.display='none';
    }
    function addimasetdisable()
    {
        document.getElementById('videodetima').style.display='none';
        document.getElementById('descriptionad').style.display='';
        document.getElementById('urltarget').style.display='';
        document.getElementById('urlclick').style.display='';
        document.getElementById('impress').style.display='';
    }
    function addimasetenable()
    {
        document.getElementById('descriptionad').style.display='none';
        document.getElementById('urltarget').style.display='none';
        document.getElementById('urlclick').style.display='none';
        document.getElementById('impress').style.display='none';
        document.getElementById('videodetima').style.display='';
    }
    function imaads(adtype)
    {
        if(adtype=="textad")
        {
            document.getElementById('imavideoad').style.display='none';
            document.getElementById('adimachannels').style.display='';
            document.getElementById('textad').checked=true;
            document.getElementById('adimacontentid').style.display='';
            document.getElementById('adimapublisher').style.display='';
            document.getElementById('imaadoption').value='Text';
        }
        if(adtype=="videoad")
        {
           document.getElementById('imavideoad').style.display='';
           document.getElementById('videoad').checked=true;
           document.getElementById('adimachannels').style.display='none';
            document.getElementById('adimacontentid').style.display='none';
            document.getElementById('adimapublisher').style.display='none';
            document.getElementById('imaadoption').value='Video';
        }

    }
