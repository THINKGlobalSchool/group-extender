<?php
/**
 * Group-Extender extend group tools js
 * 
 * @package Group-Extender
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
<script type='text/javascript'>
	// Click on the 'other options' group_tools tab if its in the hash
	$(document).ready(function() {
		if (window.location.hash) {
			var hash = window.location.hash.replace("#", "");

			if (hash == 'other') {
				console.log(hash);
				$('.group-tools-group-edit-other').closest('li').click();
			}
		}
	});
</script>