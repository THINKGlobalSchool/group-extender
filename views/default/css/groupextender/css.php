<?php
/**
 * Group-Extender css
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>

#group-navigator-select {
	width: 200px;
}

#group-dashboard-groups-container {
	column-count: 2;
	-moz-column-count: 2;
	-webkit-column-count: 2;
}

#group-dashboard-group-select-form-container {
	margin-top: 5px;
	margin-bottom: 8px;
}

.group-dashboard-module {
	display: inline-block;
	margin-right: 10px;
}

/* Fix alignment for 'image blocks' without an image */
.group-extender-tab-content-container .elgg-body:only-child {
	margin-left: 10px;	
}

#group-extender-tab-refresh-submit {
	float: right;
	font-size: 90%;
}

/** Group Categories on 'All' page **/
#groups-all-categories-ajaxmodule {
	float: left;
	width: 30%;
}

#groups-all-categories-ajaxmodule li.elgg-item:hover {
	cursor: pointer;
	background: #eee;
}

#groups-all-categories-ajaxmodule li.elgg-item {
	border-bottom: 0;
	margin: 0;
	padding-left: 4px;
}

#groups-all-categories-ajaxmodule li.elgg-item .elgg-subtext {
	margin-bottom: 0;
}

#groups-all-categories-ajaxmodule .elgg-list {
	border-top: 0;
}

#groups-all-categories-ajaxmodule .tgstheme-entity-menu {
	display: none;
}

#groups-all-group-list {
	float: right;
	width: 69%;
}

.category-state-selected, .category-state-selected:hover {
	background: #ccc !important;
}



