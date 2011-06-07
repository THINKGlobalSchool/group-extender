<?php
/**
 * Typeahead Tags JS
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 */
?>
//<script>
elgg.provide('elgg.groupextender');

elgg.groupextender.init = function() {	

	// Add a click handler for the tabs
	$('a.group-tools-item-link').live('click', elgg.groupextender.switchGroupToolsTab);

	// When ready, hack the divs
	$(function() {
			var groupToolsDiv = $('#groups-tools');
			
			groupToolsDiv.prepend('<ul id="group-tools-tabbed-nav" class="elgg-menu elgg-menu-filter elgg-menu-hz elgg-menu-filter-default"></ul>');
	
			groupToolsDiv.children("div.elgg-module").each(function() {
					// Hide the module
					$(this).hide();
				
					// Clean up extra classes
					$(this).removeClass('odd');

					// Get title
					var title = $('h3', this).html();
					
					// Get a code-friendly id for the modules (lowercase, spaces replaced with -)
					var module_id = title.toLowerCase().replace(' ', '-');
					module_id = module_id.replace(' ', '-');
					
					// Add the nav item	
					$('#group-tools-tabbed-nav').append('<li class="group-tools-item"><a class="group-tools-item-link" href="#group-tools-' + module_id + '">' + title + '</a></li>');
					// Add an id and class to the module
					$(this).attr('id', 'group-tools-' + module_id);
					$(this).addClass('group-tools-module');
			});	
			
			// Fire the click handler for the first nav item
			groupToolsDiv.find("ul#group-tools-tabbed-nav li:first-child a").click();

	});
}

// Click handler for group tools nav items
elgg.groupextender.switchGroupToolsTab = function(event) {
	// Hide all modules
	$('.group-tools-module').hide();
	
	// Remove selected class
	$('#group-tools-tabbed-nav').children("li").each(function() {$(this).removeClass('elgg-state-selected');});
	
	// Add selected class to this item
	$(this).parent().addClass('elgg-state-selected');
	
	// Show the div (passed as href)
	$($(this).attr('href')).show();
	
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.groupextender.init);
//</script>