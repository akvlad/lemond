<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
if(! class_exists('VirtueMartModelCategory'))
{
  require_once(JPATH_ADMINISTRATOR. '/components/com_virtuemart/models/category.php');
}
$categoryID=JRequest::getVar('virtuemart_category_id',0);
if($categoryID!=0)
{
  $categoryModel=new VirtueMartModelCategory();
  $category=$categoryModel->getCategory($categoryID);
  $output=$category->category_description;
}
else
  $output='';
print($output);
?>

