<?php
/* Selects between two different panels to swap in for a timeline, depending
 * on whether the user is logged in an able to edit the item on which he or she
 * has clicked. Also defines the elements that should be displayed or edited.
 */

$element_names = array("Title", "Description", "Date");

if ( has_permission('Items', 'edit')){
	echo $this->partial("widgets/editpanel.phtml",array("item"=>$item,"element_names"=>$element_names));
}
else {
	echo $this->partial("widgets/viewpanel.phtml",array("item"=>$item,"element_names"=>$element_names));
}

?>