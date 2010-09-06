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

	function get_elgg_echo(value) {
		var url = '<?php echo $elgg_echo_url; ?>';	
		var result = "Blah";
		$.get(url, { value: value },
		   function(data){
				result = data;
				$("#group_extend_nav_" + value).append(result);
		});
	}

	$(document).ready(function () {
		$.each($('#group_tools_latest').children(), function() {			
				$(this).removeClass('clearfloat');
				var classes = $(this).attr('class').split(/\s+/);
				
				// Check through each class and add a new list item for each discovered widget
				$.each(classes, function(index, item) {
					if (item != 'group_tool_widget') {					
						$("#group_extender_nav").append('<li id=' + item + '><a id="group_extend_nav_' + item + '" href="?tab=' + item + '"></a></li>');
						get_elgg_echo(item);	
					}
				});
				
				// Determine which tab we're on, and select/show content
				if ($(this).hasClass("<?php echo $tab; ?>")) {
					$("li#<?php echo $tab; ?>").addClass('selected');
					$(this).toggle();
				}
		});	
		
		$("div#group_nav").toggle();
	});
	
	
</script>	 
<div id="group_nav" class="elgg_horizontal_tabbed_nav" style="display: none;">
	<ul id='group_extender_nav'>
	</ul>
</div>
<br />
