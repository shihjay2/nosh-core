<?php echo HTML::script('js/ros.js'); ?>
<form id="encounters_ros">
	<div class="pure-g">
		<div class="pure-u-1-2">
			<span id="button_ros_gen_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_gen" class="nosh_button ros_menu_button">General</button><br><br>
			<span id="button_ros_eye_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_eye" class="nosh_button ros_menu_button">Eye</button><br><br>
			<span id="button_ros_ent_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_ent" class="nosh_button ros_menu_button">Ear, Nose, and Throat</button><br><br>
			<span id="button_ros_resp_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_resp" class="nosh_button ros_menu_button">Respiratory</button><br><br>
			<span id="button_ros_cv_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_cv" class="nosh_button ros_menu_button">Cardiovascular</button><br><br>
			<span id="button_ros_gi_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_gi" class="nosh_button ros_menu_button">Gastrointestinal</button><br><br>
			<span id="button_ros_gu_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_gu" class="nosh_button ros_menu_button">Genitourinary</button><br><br>
		</div>
		<div class="pure-u-1-2">
			<span id="button_ros_mus_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_mus" class="nosh_button ros_menu_button">Musculoskeletal</button><br><br>
			<span id="button_ros_neuro_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_neuro" class="nosh_button ros_menu_button">Neurological</button><br><br>
			<span id="button_ros_psych_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_psych" class="nosh_button ros_menu_button">Psychological</button><br><br>
			<span id="button_ros_heme_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_heme" class="nosh_button ros_menu_button">Hematological/Lymphatic</button><br><br>
			<span id="button_ros_endocrine_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_endocrine" class="nosh_button ros_menu_button">Endocrine</button><br><br>
			<span id="button_ros_skin_status" class="ros_tooltip"></span>
			<button type="button" id="button_ros_skin" class="nosh_button ros_menu_button">Skin</button><br><br>
			<?php if (Session::get('agealldays') <= 6574.32) {?>
				<span id="button_ros_wcc_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_wcc" class="nosh_button ros_menu_button">Well Child Visit</button>
			<?php }?>
		</div>
	</div>
</form>
<div id="ros_gen_dialog" title="General" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_gen_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_gen">Preview:</label><textarea style="width:95%" rows="25" name="ros_gen" id="ros_gen" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_gen_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_gen_template">Choose Template:</label><select id="ros_gen_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_gen_normal" class="all_normal nosh_button" value=""><label for="ros_gen_normal">All Normal</label><button type="button" id="ros_gen_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_gen_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_gen_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_gen_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_eye_dialog" title="Eye" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_eye_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_eye">Preview:</label><textarea style="width:95%" rows="25" name="ros_eye" id="ros_eye" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_eye_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_eye_template">Choose Template:</label><select id="ros_eye_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_eye_normal" class="all_normal nosh_button" value=""><label for="ros_eye_normal">All Normal</label><button type="button" id="ros_eye_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_eye_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_eye_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_eye_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_ent_dialog" title="Ear, Nose, and Throat" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_ent_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_ent">Preview:</label><textarea style="width:95%" rows="25" name="ros_ent" id="ros_ent" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_ent_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_ent_template">Choose Template:</label><select id="ros_ent_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_ent_normal" class="all_normal nosh_button" value=""><label for="ros_ent_normal">All Normal</label><button type="button" id="ros_ent_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_ent_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_ent_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_ent_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_resp_dialog" title="Respiratory" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_resp_dialog_form" class="pure-form pure-form-stacked">
				<label for="">Preview:</label><textarea style="width:95%" rows="25" name="ros_resp" id="ros_resp" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_resp_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_resp_template">Choose Template:</label><select id="ros_resp_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_resp_normal" class="all_normal nosh_button" value=""><label for="ros_resp_normal">All Normal</label><button type="button" id="ros_resp_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_resp_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_resp_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_resp_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_cv_dialog" title="Cardiovascular" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_cv_dialog_form" class="pure-form pure-form-stacked">
				<label for="">Preview:</label><textarea style="width:95%" rows="25" name="ros_cv" id="ros_cv" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_cv_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_cv_template">Choose Template:</label><select id="ros_cv_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_cv_normal" class="all_normal nosh_button" value=""><label for="ros_cv_normal">All Normal</label><button type="button" id="ros_cv_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_cv_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_cv_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_cv_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
		
	</div>
</div>
<div id="ros_gi_dialog" title="Gastrointestinal" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_gi_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_gi">Preview:</label><textarea style="width:95%" rows="25" name="ros_gi" id="ros_gi" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_gi_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_gi_template">Choose Template:</label><select id="ros_gi_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_gi_normal" class="all_normal nosh_button" value=""><label for="ros_gi_normal">All Normal</label><button type="button" id="ros_gi_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_gi_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_gi_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_gi_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_gu_dialog" title="Genitourinary" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_gu_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_gu">Preview:</label><textarea style="width:95%" rows="25" name="ros_gu" id="ros_gu" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_gu_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_gu_template">Choose Template:</label><select id="ros_gu_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_gu_normal" class="all_normal nosh_button" value=""><label for="ros_gu_normal">All Normal</label><button type="button" id="ros_gu_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_gu_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_gu_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_gu_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_mus_dialog" title="Musculoskeletal" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_mus_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_mus">Preview:</label><textarea style="width:95%" rows="25" name="ros_mus" id="ros_mus" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_mus_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_mus_template">Choose Template:</label><select id="ros_mus_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_mus_normal" class="all_normal nosh_button" value=""><label for="ros_mus_normal">All Normal</label><button type="button" id="ros_mus_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_mus_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_mus_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_mus_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_neuro_dialog" title="Neurological" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_neuro_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_neuro">Preview:</label><textarea style="width:95%" rows="25" name="ros_neuro" id="ros_neuro" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_neuro_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_neuro_template">Choose Template:</label><select id="ros_neuro_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_neuro_normal" class="all_normal nosh_button" value=""><label for="ros_neuro_normal">All Normal</label><button type="button" id="ros_neuro_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_neuro_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_neuro_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_neuro_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_psych_dialog" title="Psychological" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_psych_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_psych">Preview:</label><textarea style="width:95%" rows="25" name="ros_psych" id="ros_psych" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_psych_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_psych_template">Choose Template:</label><select id="ros_psych_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_psych_normal" class="all_normal nosh_button" value=""><label for="ros_psych_normal">All Normal</label><button type="button" id="ros_psych_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_psych_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_heme_dialog" title="Hematological/Lymphatic" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_heme_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_heme">Preview:</label><textarea style="width:95%" rows="25" name="ros_heme" id="ros_heme" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_heme_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_heme_template">Choose Template:</label><select id="ros_heme_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_heme_normal" class="all_normal nosh_button" value=""><label for="ros_heme_normal">All Normal</label><button type="button" id="ros_heme_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_heme_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_heme_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_heme_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_endocrine_dialog" title="Endocrine" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_endocrine_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_endocrine">Preview:</label><textarea style="width:95%" rows="25" name="ros_endocrine" id="ros_endocrine" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_endocrine_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_endocrine_template">Choose Template:</label><select id="ros_endocrine_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_endocrine_normal" class="all_normal nosh_button" value=""><label for="ros_endocrine_normal">All Normal</label><button type="button" id="ros_endocrine_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_endocrine_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_endocrine_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_endocrine_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_skin_dialog" title="Skin" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_skin_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_skin">Preview:</label><textarea style="width:95%" rows="25" name="ros_skin" id="ros_skin" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_skin_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_skin_template">Choose Template:</label><select id="ros_skin_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_skin_normal" class="all_normal nosh_button" value=""><label for="ros_skin_normal">All Normal</label><button type="button" id="ros_skin_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_skin_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_skin_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_skin_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_wcc_dialog" title="Well Child Check" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="ros_wcc_dialog_form" class="pure-form pure-form-stacked">
				<label for="ros_wcc">Preview:</label><textarea style="width:95%" rows="25" name="ros_wcc" id="ros_wcc" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_wcc_old"/>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="ros_wcc_template">Choose Template:</label><select id="ros_wcc_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
			</form>
			<input type="checkbox" id="ros_wcc_normal" class="all_normal nosh_button" value=""><label for="ros_wcc_normal">All Normal</label><button type="button" id="ros_wcc_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_wcc_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_wcc_reset" class="reset nosh_button">Clear</button><br>
			<div class="ros_template_div">
				<br><form id="ros_wcc_form" class="ros_template_form ui-widget pure-form"></form>
				<br><form id="ros_wcc_age_form" class="ros_template_form ui-widget pure-form"></form>
			</div>
		</div>
	</div>
</div>
