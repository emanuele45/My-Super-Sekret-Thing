<?php
/**********************************************************************************
* remove_settings.php                                                             *
***********************************************************************************
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
*                                                                                 *
* This file is a simplified database installer. It does what it is suppoed to.    *
**********************************************************************************/

// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');

global $modSettings;

// List the modsettings variables to remove array('one',two',three') 
$remove_settings = array(
	'descriptivelinks_enabled',
	'descriptivelinks_title_url',
	'descriptivelinks_title_internal',
	'descriptivelinks_title_url_count',
	'descriptivelinks_title_url_generic',
	'descriptivelinks_title_url_length',
	'descriptivelinks_title_bbcurl',
);

// Remove settings if applicable
if (count($remove_settings) > 0)
{
	// First remove them from memory
	foreach ($remove_settings as $setting)
		if (isset($modSettings[$setting]))
			unset($modSettings[$setting]);

	// And now from sight
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}settings
		WHERE variable IN ({array_string:variables})',
		array(
			'variables' => $remove_settings,
		)
	);

	// And let SMF know we have been mucking about so the cache is reset
	updateSettings(array('settings_updated' => time()));
}

if (SMF == 'SSI')
   echo 'Congratulations! You have successfully removed this mod!';

?>