<?php 
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/

/*The functions in this file are also required by the plugins*/
defined( '_JEXEC' ) or die( 'Restricted Access' );


function array_trim($arr) {
	return array_map('trim', $arr);
}//array_trim() ends

/*
	This function is used by the jsdocs plugin
*/

function getUserInfontwrk($userId= null,$what=null){

	$db =JFactory::getDBO();

	$data = array();

	// Return with empty data

	if($userId == null || $userId == ''){
		//return false;
	}

	$user =& JFactory::getUser($userId);
	if($user->id == null){
		//return false;
	}

	$data['id'] = $user->id;

	$data['name'] = $user->name;

	$data['email'] = $user->email;

	// Attach custom fields into the user object

	$strSQL = 'SELECT value.value '

	. 'FROM ' . $db->nameQuote('#__community_fields') . ' AS field '

	. 'LEFT JOIN ' . $db->nameQuote('#__community_fields_values') . ' AS value '

	. 'ON field.id=value.field_id AND value.user_id=' . $db->Quote($userId) . ' '

	. 'WHERE field.published=' . $db->Quote('1') . ' AND '

	. 'field.visible=' . $db->Quote('1') . ' AND '
	. 'field.id IN ('.$what . ') '
	. 'ORDER BY field.ordering';


	$db->setQuery( $strSQL );
	$result = $db->loadResultArray();

	if($db->getErrorNum()){
		JError::raiseError( 500, $db->stderr());
	}

	$result=array_filter($result);
	$s="";
	$c="";
	$c = implode (',' , $result);
	$c = explode (',',$c);
	
	return $c;
}//getUserInfontwrk() ends



/*
		This function is used by the jsntwrk suggest plugin
*/
 function getUserInfo($userId= null,$what=null){

$db =JFactory::getDBO();

$data = array();



// Return with empty data

if($userId == null || $userId == ''){

//return false;

}



$user =& JFactory::getUser($userId);



if($user->id == null){

//return false;

}



$data['id'] = $user->id;

$data['name'] = $user->name;

$data['email'] = $user->email;



// Attach custom fields into the user object

$strSQL = 'SELECT value.value '

. 'FROM ' . $db->nameQuote('#__community_fields') . ' AS field '

. 'LEFT JOIN ' . $db->nameQuote('#__community_fields_values') . ' AS value '

. 'ON field.id=value.field_id AND value.user_id=' . $db->Quote($userId) . ' '

. 'WHERE field.published=' . $db->Quote('1') . ' AND '

. 'field.visible=' . $db->Quote('1') . ' AND '
. 'field.id IN ('.$what . ') '
. 'ORDER BY field.ordering';


$db->setQuery( $strSQL );



$result = $db->loadResultArray();
if($db->getErrorNum()){

JError::raiseError( 500, $db->stderr());

}
$result=array_filter($result);
$s="";
$c="";
$c = implode (',' , $result);
$c = explode (',',$c);
$s .= implode ('" "' , $c);
$s = ' "' . $s . '" ';

return $s;

}


/* End Functions required by the module */
?>
