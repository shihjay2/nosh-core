<div id="image_dialog_preview" title="Associated Images for Encounter">
	<div class="pure-g">
		<div class="pure-u-1-3">
			<div class="pure-form pure-form-stacked">
				<label for="image_select">Select an preset image annotate:</label><select id="image_select" class="text"></select> 
			</div>
			<br><br>
			<button type="button" id="add_photo" class="nosh_button_add">Add</button><br><br>
			<button type="button" id="edit_image" class="nosh_button_edit">Edit</button><br><br>
			<button type="button" id="del_image" class="nosh_button_delete">Remove</button><br><br>
			<button type="button" id="prev_image" class="nosh_button_prev">Previous</button> 
			<button type="button" id="next_image" class="nosh_button_next">Next</button>
			<span id="image_status"></span>
		</div>
		<div class="pure-u-2-3" id="image_placeholder"></div>
	</div>
</div>
<div id="image_dialog" title="Annotate Image">
	<input type="hidden" id="image_origin"/>
	<form id="image_form" class="pure-form pure-form-stacked">
		<label for="image_description">Description:</label><textarea name="image_description" id="image_description" rows="3" style="width:400px" class="text"></textarea>
		<input type="hidden" name="eid" id="image_eid"/>
		<input type="hidden" name="pid" id="image_pid"/>
		<input type="hidden" name="image" id="image_data"/>
		<input type="hidden" name="image_id" id="image_id"/>
	</form><br>
	<div style="width:99%; position:relative; overflow:hidden;">
		<div id="wPaint" style="position:relative; width:500px; height:500px; background:#CACACA; margin-top:80px;"></div>
	</div>
</div>

