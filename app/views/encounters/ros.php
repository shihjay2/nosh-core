<?php echo HTML::script('js/ros.js'); ?>
<form id="encounters_ros">
	<div class="pure-g">
		<?php if (Session::get('encounter_template') == 'standardmedical') {?>
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
		<?php } if (Session::get('encounter_template') == 'standardpsych') {?>
			<div class="pure-u-1-2">
				<span id="button_ros_psych1_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych1" class="nosh_button ros_menu_button">Depression</button><br><br>
				<span id="button_ros_psych2_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych2" class="nosh_button ros_menu_button">Anxiety</button><br><br>
				<span id="button_ros_psych3_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych3" class="nosh_button ros_menu_button">Bipolar</button><br><br>
				<span id="button_ros_psych4_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych4" class="nosh_button ros_menu_button">Mood Disorders</button><br><br>
				<span id="button_ros_psych5_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych5" class="nosh_button ros_menu_button">ADHD</button><br><br>
				<span id="button_ros_psych6_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych6" class="nosh_button ros_menu_button">PTSD</button><br><br>
			</div>
			<div class="pure-u-1-2">
				<span id="button_ros_psych7_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych7" class="nosh_button ros_menu_button">Substance Related Disorder</button><br><br>
				<span id="button_ros_psych8_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych8" class="nosh_button ros_menu_button">Obsessive Compulsive Disorder</button><br><br>
				<span id="button_ros_psych9_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych9" class="nosh_button ros_menu_button">Social Anxiety Disorder</button><br><br>
				<span id="button_ros_psych10_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych10" class="nosh_button ros_menu_button">Autistic Disorder</button><br><br>
				<span id="button_ros_psych11_status" class="ros_tooltip"></span>
				<button type="button" id="button_ros_psych11" class="nosh_button ros_menu_button">Asperger's Disorder</button><br><br>
			</div>
		<?php }?>
	</div>
</form>
<?php if (Session::get('encounter_template') == 'standardmedical') {?>
	<div id="ros_gen_dialog" title="General" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_gen_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_gen">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_gen" id="ros_gen" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_gen_old"/>
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
					<label for="ros_eye">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_eye" id="ros_eye" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_eye_old"/>
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
					<label for="ros_ent">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_ent" id="ros_ent" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_ent_old"/>
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
					<label for="">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_resp" id="ros_resp" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_resp_old"/>
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
					<label for="">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_cv" id="ros_cv" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_cv_old"/>
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
					<label for="ros_gi">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_gi" id="ros_gi" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_gi_old"/>
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
					<label for="ros_gu">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_gu" id="ros_gu" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_gu_old"/>
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
					<label for="ros_mus">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_mus" id="ros_mus" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_mus_old"/>
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
					<label for="ros_neuro">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_neuro" id="ros_neuro" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_neuro_old"/>
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
					<label for="ros_psych">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych" id="ros_psych" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych_old"/>
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
					<label for="ros_heme">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_heme" id="ros_heme" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_heme_old"/>
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
					<label for="ros_endocrine">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_endocrine" id="ros_endocrine" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_endocrine_old"/>
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
					<label for="ros_skin">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_skin" id="ros_skin" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_skin_old"/>
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
					<label for="ros_wcc">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_wcc" id="ros_wcc" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_wcc_old"/>
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
<?php } if (Session::get('encounter_template') == 'standardpsych') {?>
	<div id="ros_psych1_dialog" title="Depression" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych1_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych1">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych1" id="ros_psych1" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych1_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych1_template">Choose Template:</label><select id="ros_psych1_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych1_normal" class="all_normal nosh_button" value=""><label for="ros_psych1_normal">All Normal</label><button type="button" id="ros_psych1_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych1_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych1_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych1_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych2_dialog" title="Anxiety" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych2_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych2">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych2" id="ros_psych2" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych2_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych2_template">Choose Template:</label><select id="ros_psych2_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych2_normal" class="all_normal nosh_button" value=""><label for="ros_psych2_normal">All Normal</label><button type="button" id="ros_psych2_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych2_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych2_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych2_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych3_dialog" title="Bipolar" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych3_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych3">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych3" id="ros_psych3" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych3_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych3_template">Choose Template:</label><select id="ros_psych3_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych3_normal" class="all_normal nosh_button" value=""><label for="ros_psych3_normal">All Normal</label><button type="button" id="ros_psych3_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych3_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych3_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych3_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych4_dialog" title="Mood Disorders" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych4_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych4">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych4" id="ros_psych4" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych4_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych4_template">Choose Template:</label><select id="ros_psych4_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych4_normal" class="all_normal nosh_button" value=""><label for="ros_psych4_normal">All Normal</label><button type="button" id="ros_psych4_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych4_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych4_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych4_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych5_dialog" title="ADHD" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych5_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych5">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych5" id="ros_psych5" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych5_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych5_template">Choose Template:</label><select id="ros_psych5_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych5_normal" class="all_normal nosh_button" value=""><label for="ros_psych5_normal">All Normal</label><button type="button" id="ros_psych5_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych5_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych5_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych5_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych6_dialog" title="PTSD" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych6_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych6">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych6" id="ros_psych6" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych6_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych6_template">Choose Template:</label><select id="ros_psych6_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych6_normal" class="all_normal nosh_button" value=""><label for="ros_psych6_normal">All Normal</label><button type="button" id="ros_psych6_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych6_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych6_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych6_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych7_dialog" title="Substance Related Disorder" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych7_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych7">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych7" id="ros_psych7" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych7_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych7_template">Choose Template:</label><select id="ros_psych7_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych7_normal" class="all_normal nosh_button" value=""><label for="ros_psych7_normal">All Normal</label><button type="button" id="ros_psych7_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych7_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych7_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych7_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych8_dialog" title="Obsessive Compulsive Disorder" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych8_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych8">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych8" id="ros_psych8" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych8_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych8_template">Choose Template:</label><select id="ros_psych8_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych8_normal" class="all_normal nosh_button" value=""><label for="ros_psych8_normal">All Normal</label><button type="button" id="ros_psych8_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych8_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych8_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych8_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych9_dialog" title="Social Anxiety Disorder" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych9_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych9">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych9" id="ros_psych9" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych9_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych9_template">Choose Template:</label><select id="ros_psych9_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych9_normal" class="all_normal nosh_button" value=""><label for="ros_psych9_normal">All Normal</label><button type="button" id="ros_psych9_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych9_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych9_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych9_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych10_dialog" title="Autistic Disorder" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych10_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych10">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych10" id="ros_psych10" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych10_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych10_template">Choose Template:</label><select id="ros_psych10_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych10_normal" class="all_normal nosh_button" value=""><label for="ros_psych10_normal">All Normal</label><button type="button" id="ros_psych10_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych10_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych10_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych10_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
	<div id="ros_psych11_dialog" title="Asperger's Disorder" style="overflow:hidden;">
		<div class="pure-g">
			<div class="pure-u-13-24">
				<form id="ros_psych11_dialog_form" class="pure-form pure-form-stacked">
					<label for="ros_psych11">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="25" name="ros_psych11" id="ros_psych11" class="ros_entry text ui-widget-content ui-corner-all textdump"></textarea><input type="hidden" id="ros_psych11_old"/>
				</form>
			</div>
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="ros_psych11_template">Choose Template:</label><select id="ros_psych11_template" class="ros_template_choose text ui-widget-content ui-corner-all"></select><br>
				</form>
				<input type="checkbox" id="ros_psych11_normal" class="all_normal nosh_button" value=""><label for="ros_psych11_normal">All Normal</label><button type="button" id="ros_psych11_hpi" class="per_hpi nosh_button">Per HPI</button><button type="button" id="ros_psych11_nc" class="nc nosh_button">Noncontributory</button><button type="button" id="ros_psych11_reset" class="reset nosh_button">Clear</button><br>
				<div class="ros_template_div">
					<br><form id="ros_psych11_form" class="ros_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
<?php }?>
