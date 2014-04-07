<div id="image_dialog_preview" title="Associated Images for Encounter">
	<div class="pure-form pure-form-stacked">
		<label for="image_select">Select an preset image annotate:</label><select id="image_select" class="text"></select> 
		or 
		<button type="button" id="add_photo" class="nosh_button_add">Add Photo</button>
	</div>
	<div style="width:99%" id="image_placeholder"></div>
	<div style="width:99%">
		<button type="button" id="prev_image" class="nosh_button">Previous</button> 
		<button type="button" id="next_image" class="nosh_button">Next</button>
		<button type="button" id="edit_image" class="nosh_button_edit">Edit</button>
		<span id="image_status"></span>
	</div>
</div>
<div id="image_dialog" title="">
	<input type="hidden" id="image_origin"/>
	<form id="image_form" class="pure-form pure-form-stacked">
		<label for="image_description">Description:</label><textarea name="image_description" id="image_description" rows="5" class="text"></textarea>
		<input type="hidden" name="eid" id="image_eid"/>
		<input type="hidden" name="pid" id="image_pid"/>
		<input type="hidden" name="image" id="image_data"/>
		<input type="hidden" name="image_id" id="image_id"/>
	</form><br>
	<div id="wpaint"></div>
</div>

