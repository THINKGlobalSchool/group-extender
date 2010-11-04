<?php
	/**
	 * Group-Extender navigation
	 * 
	 * @package Group-Extender
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */

	$elgg_echo_url = $CONFIG->wwwroot . "mod/group-extender/ajax_actions/get_elgg_echo.php";
	$tab = get_input('tab', 'activity');
?>
<script type="text/javascript">
	
	function hideGroupDivs() {
		$("li#group_nav_list").removeClass('selected');
		$.each($('#group_tools_latest').children(), function() {
			$(this).hide();
		});
	}

	$(document).ready(function () {
		$.each($('#group_tools_latest').children(), function() {			
				// Get rid of any clearfloat
				$(this).removeClass('clearfloat');
				$(this).removeClass('clearfix');
				
				// Get child H3 title
				var title = $('h3', this).html();
				var id = '';
				
				// Split list of classes
				var classes = $(this).attr('class').split(/\s+/);
				
				// Check through each class and add a new list item for each discovered widget
				$.each(classes, function(index, item) {
					if (item != 'group_tool_widget') {					
						$("#group_extender_nav").append('<li id="group_nav_list" class="group_' + item + '"><a class="group_extend_link" id="group_' + item + '" href="#">' + title + '</a></li>');	
						id = 'div_group_' + item; 
					}
				});
				
				$(this).addClass(id);
		});	
		
		$(".group_extend_link").click(
			function() {
				hideGroupDivs();
				$(".div_" + $(this).attr('id')).show();
				$("li." + $(this).attr('id')).addClass('selected');
				return false;
			}
		);
		
		// Show activity as selected.. we can assume this is here
		$("li.group_activity").addClass('selected');
		$("div.div_group_activity").show();
		
	});
</script>	 
<div id="group_nav" class="elgg_horizontal_tabbed_nav">
	<ul id='group_extender_nav'>
	</ul>
</div>
<br />