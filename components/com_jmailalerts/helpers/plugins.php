<?php
/*
 * @package J!MailALerts
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link    http://www.techjoomla.com
 */
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );


/*
 * class will contain function to format alerts email alert
*/
class pluginHelper 
{
   function pluginHelper()
   {
      // parent::__construct();  
   }
   
    /**
     * Gets the parsed layout file
     *
     * @param string $layout The name of  the layout file
     * @param object $vars Variables to assign to
     * @param string $plugin The name of the plugin
     * @param string $group The plugin's group
     * @return string
     */
    function getLayout($layout,$vars=false,$plugin_params,$plugin='',$group='emailalerts')
    {   
		$plugin=$layout;
		ob_start();
		$layout=$this->getLayoutPath($plugin,$group,$layout,$plugin_params );
		include($layout); 
		$html = ob_get_contents(); 
		ob_end_clean(); 
		return $html;
	}
    /**
     * Get the path to a layout file
     *
     * @param   string  $plugin The name of the plugin file
     * @param   string  $group The plugin's group
     * @param   string  $layout The name of the plugin layout file
     * @return  string  The path to the plugin layout file
     */
    function getLayoutPath($plugin, $group, $layout = 'default')
    {
        $app = JFactory::getApplication();
        //get the template and default paths for the layout
        $templatePath=JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'plugins'.DS.$group.DS.$plugin.DS.$layout.'.php';
        if(JVERSION >= '1.6.0')
	       $defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.$plugin.DS.'tmpl'.DS.$layout.'.php';
        else
    	   $defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.'tmpl'.DS.$layout.'.php';

        //if the site template has a layout override, use it
        jimport('joomla.filesystem.file');
        if(JFile::exists( $templatePath )){
			return $templatePath;
        }else{        	
            return $defaultPath;
        }
    }

    /*
     Function to get css file path
    */
    function getCSSLayoutPath($layout='default',$plugin_params)
    {
        $app = JFactory::getApplication();
        $plugin = & $layout;
        $group = 'emailalerts';

        // get the template and default paths for the layout
        $templatePath = JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'plugins'.DS.$group.DS.$plugin.DS.$layout.'.css';
        if(JVERSION >= '1.6.0')
	        $defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.$plugin.DS.'tmpl'.DS.$layout.'.css';
        else
    	    $defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.'tmpl'.DS.$layout.'.css';
        //if the site template has a layout override, use it
        jimport('joomla.filesystem.file');
        if(JFile::exists($templatePath)){
             return $templatePath;
        }else{
             return $defaultPath;
        }
    }
    
    //get item id
    function getItemId($link)
    {
        $db	= & JFactory::getDBO();
        $query = "SELECT id FROM ".$db->nameQuote('#__menu')." WHERE link LIKE '".$link."%' AND published =1 ORDER BY ordering";
        $db->setQuery( $query );
        $itemid=$db->loadResult();
        return $itemid;
    }
    
    //function to sort a multidimentional array as per given column
    function multi_d_sort($array,$column,$order)
    {
        foreach ($array as $key=>$row){
            $orderby[$key]=$row->$column;
        }
        if($order){
            array_multisort($orderby,SORT_DESC,$array);
        }else{
            array_multisort($orderby,SORT_ASC,$array);
        }
        return $array;
    }
}
