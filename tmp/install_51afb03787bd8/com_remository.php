<?php
/**
 * Remository SEF extension for Joomla!
 *
 * @author      ARTIO s.r.o.
 * @copyright   Copyright 2011, ARTIO s.r.o., http://www.artio.net
 * @package     JoomSEF
 * @license     http://www.artio.net/joomsef/licence-ext
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_remository extends SefExt
{
    var $params;
	
    function getNonSefVars(&$uri)
    {
        $this->_createNonSefVars($uri);
        
        return array($this->nonSefVars, $this->ignoreVars);
    }
    
    function _createNonSefVars(&$uri)
    {
        if (isset($this->nonSefVars) && isset($this->ignoreVars))
            return;
            
        $this->nonSefVars = array();
        $this->ignoreVars = array();
        
        if (!is_null($uri->getVar('limit')))
            $this->nonSefVars['limit'] = $uri->getVar('limit');
        if (!is_null($uri->getVar('limitstart')))
            $this->nonSefVars['limitstart'] = $uri->getVar('limitstart');
        if (!is_null($uri->getVar('chk')))
            $this->nonSefVars['chk'] = $uri->getVar('chk');
        
        if (!is_null($uri->getVar('id'))) {
            if ($this->params->get('nonsef_id', '0') != '0') {
                if ($uri->getVar('func', '') == 'addfile') {
                    $this->nonSefVars['id'] = $uri->getVar('id');
                }
            }
        }
    }
    
    function AddNamePart(&$name, $object, $part)
    {
        if (isset($object->$part)) {
            $name[] = html_entity_decode($object->$part);
        }
    }
    
    function BuildName($object, $fieldname, $defaultText)
    {
        $name = array();
        $object->text = $this->params->get($fieldname.'text', $defaultText);
        $this->AddNamePart($name, $object, $this->params->get($fieldname.'1', 'none'));
        $this->AddNamePart($name, $object, $this->params->get($fieldname.'2', 'title'));
        $this->AddNamePart($name, $object, $this->params->get($fieldname.'3', 'none'));
        
        return implode('-', $name);
    }

	function getCategoryTitle($id, $addFirst) {
		$cats = $this->params->get('container_inc', '2');
		$categories = array();
		$database =& JFactory::getDBO();
		
		if ($cats == '0' && !$addFirst) {
            $id = 0; // No cat to add
        }
		while ($id > 0) {
	        $database->setQuery("SELECT `id`, `name` AS `title`, `alias`, `parentid` FROM `#__downloads_containers` WHERE `id` = '{$id}'");
	        $cat = $database->loadObject();
            
            if (is_null($cat)) {
                return null;
            }
			
	        $name = $this->BuildName($cat, 'catname', 'Container');
			array_unshift($categories, $name);
			
			$id = $cat->parentid;
			if ($cats != '2') {
                break; //  Only last cat
            }
        }
        
		return $categories;
    }
	
	function getFileTitle($id) {
        $database =& JFactory::getDBO();
		
        $database->setQuery("SELECT `id`, `filetitle` AS `title`, `subtitle` AS `alias`, `containerid` FROM `#__downloads_files` WHERE `id` = '{$id}'");
        $file = $database->loadObject();
        
        if (is_null($file)) {
            return null;
        }
		
		$category = $this->getCategoryTitle($file->containerid, false);
        if (is_null($category)) {
            return null;
        }
		
		$name = $this->BuildName($file, 'filename', 'File');
		array_push($category, $name);
		
		return $category;
    }

    function create(&$uri) {
        // Extract variables
        $vars = $uri->getQuery(true);
        extract($vars);
        $title = array();

        $this->params = SEFTools::getExtParams('com_remository');

        $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);

		if(isset($id)) {
			if($func == 'select' || $func == 'rss' || $func == 'addfile') {
			    if (($this->params->get('nonsef_id', '0') == '0') || ($func != 'addfile')) {
                    $categories = $this->getCategoryTitle($id, true);
                    if (is_null($categories)) {
                        return $uri;
                    }
				    $title = array_merge($title, $categories);
			    }
			} else {
                $file = $this->getFileTitle($id);
                if (is_null($file)) {
                    return $uri;
                }
				$title = array_merge($title, $file);
			}
			unset($id);
		}
		
        if( isset($func) ) {
            switch($func) {
				case 'select':
                    unset($func);
                    break;
				case 'fileinfo':
                    unset($func);
                    break;
				case 'addfile':
				case 'startdown':
				case 'download':
				case 'thumbupdate':
				case 'userupdate':
				case 'userdelete':
				case 'search':
				case 'addmanyfiles':
				    if ($this->params->get('always_english', '0') == '0') {
    					$tasks = array('addfile' => _SUBMIT_FILE_BUTTON,
    					'startdown' => 'Start Download',
    					'download' => _DOWNLOAD,
    					'thumbupdate' => _DOWN_UPDATE_THUMBNAILS,
    					'userupdate' => _DOWN_UPDATE_SUB,
    					'userdelete' => _DOWN_DEL_SUB_BUTTON,
    					'search' => _DOWN_SEARCH,
    					'addmanyfiles' => _DOWN_ADD_NUMBER_FILES);
				    }
				    else {
    					$tasks = array('addfile' => 'Submit File',
    					'startdown' => 'Start Download',
    					'download' => 'Download',
    					'thumbupdate' => 'Update thumbnails',
    					'userupdate' => 'Update Submission',
    					'userdelete' => 'Delete Submission',
    					'search' => 'Search Repository',
    					'addmanyfiles' => 'Add a number of files');
				    }
					$title[] = $tasks[$func];
                    unset($func);
					break;
				default:
					$title[] = $func;
                    unset($func);
            }
        }
		
		if(isset($page))
			$title[] = JText::_('Page').' '.$page;
		
		if(isset($orderby)) {
			if(in_array("/", $title))
				$key = array_search("/", $title);
			switch($orderby) {
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 7:
				    if ($this->params->get('always_english', '0') == '0') {
    					$tasks = array(1 => _DOWN_ORDER_BY.'-ID',
    					2 => _DOWN_ORDER_BY.' '._DOWN_FILE_TITLE,
    					3 => _DOWN_ORDER_BY.' '._DOWN_DOWNLOADS_SORT,
    					4 => _DOWN_ORDER_BY.' '._DOWN_SUB_DATE_SORT,
    					5 => _DOWN_ORDER_BY.' '._DOWN_SUB_ID_SORT,
    					6 => _DOWN_ORDER_BY.' '._DOWN_AUTHOR_ABOUT,
    					7 => _DOWN_ORDER_BY.' '._DOWN_RATING);
				    }
				    else {
    					$tasks = array(1 => 'Order By ID',
    					2 => 'Order By File Title',
    					3 => 'Order By Downloads',
    					4 => 'Order By Submit Date',
    					5 => 'Order By Submitter',
    					6 => 'Order By Author',
    					7 => 'Order By Rating');
				    }
					$title[] = $tasks[$orderby];
                    unset($orderby);
					break;
				default:
					$title[] = $orderby;
                    unset($orderby);
			}
		}
		
        $newUri = $uri;
        if (count($title) > 0) {
            $this->_createNonSefVars($uri);
            
            $newUri = JoomSEF::_sefGetLocation($uri, $title, @$task, @$limit, @$limitstart, @$lang, $this->nonSefVars);
        }

        return $newUri;
    }
}
?>