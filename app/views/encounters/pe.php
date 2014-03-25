<?php echo HTML::script('js/pe.js'); ?>
<form id="encounters_pe">
	<div class="pure-g">
		<div class="pure-u-1-2">
			<span id="button_pe_gen_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_gen" value="1"/><button type="button" id="button_pe_gen" class="nosh_button pe_menu_button">General</button><br><br>
			<span id="button_pe_eye_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_eye" value="3"/><button type="button" id="button_pe_eye" class="nosh_button pe_menu_button">Eye</button><br><br>
			<span id="button_pe_ent_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_ent" value="6"/><button type="button" id="button_pe_ent" class="nosh_button pe_menu_button">Ear, Nose, and Throat</button><br><br>
			<span id="button_pe_neck_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_neck" value="2"/><button type="button" id="button_pe_neck" class="nosh_button pe_menu_button">Neck</button><br><br>
			<span id="button_pe_resp_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_resp" value="4"/><button type="button" id="button_pe_resp" class="nosh_button pe_menu_button">Respiratory</button><br><br>
			<span id="button_pe_cv_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_cv" value="6"/><button type="button" id="button_pe_cv" class="nosh_button pe_menu_button">Cardiovascular</button><br><br>
			<span id="button_pe_ch_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_ch" value="2"/><button type="button" id="button_pe_ch" class="nosh_button pe_menu_button">Chest</button><br><br>
		</div>
		<div class="pure-u-1-2">
			<span id="button_pe_gi_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_gi" value="4"/><button type="button" id="button_pe_gi" class="nosh_button pe_menu_button">Gastrointestinal</button><br><br>
			<span id="button_pe_gu_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_gu" value="9"/><button type="button" id="button_pe_gu" class="nosh_button pe_menu_button">Genitourinary</button><br><br>
			<span id="button_pe_lymph_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_lymph" value="3"/><button type="button" id="button_pe_lymph" class="nosh_button pe_menu_button">Lymphatic</button><br><br>
			<span id="button_pe_ms_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_ms" value="12"/><button type="button" id="button_pe_ms" class="nosh_button pe_menu_button">Musculoskeletal</button><br><br>
			<span id="button_pe_neuro_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_neuro" value="3"/><button type="button" id="button_pe_neuro" class="nosh_button pe_menu_button">Neurological</button><br><br>
			<span id="button_pe_psych_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_psych" value="4"/><button type="button" id="button_pe_psych" class="nosh_button pe_menu_button">Psychological</button><br><br>
			<span id="button_pe_skin_status" class="pe_tooltip"></span>
			<input type="hidden" id="num_pe_skin" value="2"/><button type="button" id="button_pe_skin" class="nosh_button pe_menu_button">Skin</button><br><br>
		</div>
	</div>
</form>
<div id="pe_gen_dialog" title="General">
	<input type="checkbox" id="pe_gen_normal" class="all_normal1 nosh_button" value=""><label for="pe_gen_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_gen_accordion">
		<h3><a href="#"><span id="pe_gen1_h"></span>General</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gen1">Preview:</label><textarea style="width:95%" rows="20" name="pe_gen1" id="pe_gen1" class="pe_entry text"></textarea><input type="hidden" id="pe_gen1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gen1_template">Choose Template:</label><select id="pe_gen1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_gen1_normal" class="all_normal nosh_button" value=""><label for="pe_gen1_normal">All Normal</label><button type="button" id="pe_gen1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_gen1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_eye_dialog" title="Eye" >
	<input type="checkbox" id="pe_eye_normal" class="all_normal1 nosh_button" value=""><label for="pe_eye_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_eye_accordion">
		<h3><a href="#"><span id="pe_eye1_h"></span>Conjunctiva and Lids</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_eye1">Preview:</label><textarea style="width:95%" rows="20" name="pe_eye1" id="pe_eye1" class="pe_entry text"></textarea><input type="hidden" id="pe_eye1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_eye1_template">Choose Template:</label><select id="pe_eye1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_eye1_normal" class="all_normal nosh_button" value=""><label for="pe_eye1_normal">All Normal</label><button type="button" id="pe_eye1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_eye1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_eye2_h"></span>Pupil and Iris</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_eye2">Preview:</label><textarea style="width:95%" rows="20" name="pe_eye2" id="pe_eye2" class="pe_entry text"></textarea><input type="hidden" id="pe_eye2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_eye2_template">Choose Template:</label><select id="pe_eye2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_eye2_normal" class="all_normal nosh_button" value=""><label for="pe_eye2_normal">All Normal</label><button type="button" id="pe_eye2_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_eye2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_eye3_h"></span>Fundoscopic</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_eye3">Preview:</label><textarea style="width:95%" rows="20" name="pe_eye3" id="pe_eye3" class="pe_entry text ui-widget"></textarea><input type="hidden" id="pe_eye3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_eye3_template">Choose Template:</label><select id="pe_eye3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_eye3_normal" class="all_normal nosh_button" value=""><label for="pe_eye3_normal">All Normal</label><button type="button" id="pe_eye3_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_eye3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_ent_dialog" title="Ears, Nose, Throat">
	<input type="checkbox" id="pe_ent_normal" class="all_normal1 nosh_button" value=""><label for="pe_ent_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_ent_accordion">
		<h3><a href="#"><span id="pe_ent1_h"></span>External Ear and Nose</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent1">Preview:</label><textarea style="width:95%" rows="20" name="pe_ent1" id="pe_ent1" class="pe_entry text"></textarea><input type="hidden" id="pe_ent1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent1_template">Choose Template:</label><select id="pe_ent1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ent1_normal" class="all_normal nosh_button" value=""><label for="pe_ent1_normal">All Normal</label><button type="button" id="pe_ent1_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent2_h"></span>Canals and Tympanic Membrane</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent2">Preview:</label><textarea style="width:95%" rows="20" name="pe_ent2" id="pe_ent2" class="pe_entry text"></textarea><input type="hidden" id="pe_ent2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent2_template">Choose Template:</label><select id="pe_ent2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ent2_normal" class="all_normal nosh_button" value=""><label for="pe_ent2_normal">All Normal</label><button type="button" id="pe_ent2_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent3_h"></span>Hearing Assessment</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent3">Preview:</label><textarea style="width:95%" rows="20" name="pe_ent3" id="pe_ent3" class="pe_entry text"></textarea><input type="hidden" id="pe_ent3_old"/><br>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent3_template">Choose Template:</label><select id="pe_ent3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ent3_normal" class="all_normal nosh_button" value=""><label for="pe_ent3_normal">All Normal</label><button type="button" id="pe_ent3_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent4_h"></span>Sinuses, Mucosa, Septum, and Turbinates</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent4">Preview:</label><textarea style="width:95%" rows="20" name="pe_ent4" id="pe_ent4" class="pe_entry text"></textarea><input type="hidden" id="pe_ent4_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent4_template">Choose Template:</label><select id="pe_ent4_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ent4_normal" class="all_normal nosh_button" value=""><label for="pe_ent4_normal">All Normal</label><button type="button" id="pe_ent4_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ent4_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent5_h"></span>Lips, Teeth, and Gums</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent5">Preview:</label><textarea style="width:95%" rows="20" name="pe_ent5" id="pe_ent5" class="pe_entry text"></textarea><input type="hidden" id="pe_ent5_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent5_template">Choose Template:</label><select id="pe_ent5_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ent5_normal" class="all_normal nosh_button" value=""><label for="pe_ent5_normal">All Normal</label><button type="button" id="pe_ent5_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent5_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent6_h"></span>Oropharynx</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent6">Preview:</label><textarea style="width:95%" rows="20" name="pe_ent6" id="pe_ent6" class="pe_entry text"></textarea><input type="hidden" id="pe_ent6_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ent6_template">Choose Template:</label><select id="pe_ent6_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ent6_normal" class="all_normal nosh_button" value=""><label for="pe_ent6_normal">All Normal</label><button type="button" id="pe_ent6_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent6_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_neck_dialog" title="Neck">
	<input type="checkbox" id="pe_neck_normal" class="all_normal1 nosh_button" value=""><label for="pe_neck_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_neck_accordion">
		<h3><a href="#"><span id="pe_neck1_h"></span>General</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neck1">Preview:</label><textarea style="width:95%" rows="20" name="pe_neck1" id="pe_neck1" class="pe_entry text"></textarea><input type="hidden" id="pe_neck1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neck1_template">Choose Template:</label><select id="pe_neck1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_neck1_normal" class="all_normal nosh_button" value=""><label for="pe_neck1_normal">All Normal</label><button type="button" id="pe_neck1_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neck1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_neck2_h"></span>Thyroid</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neck2">Preview:</label><textarea style="width:95%" rows="20" name="pe_neck2" id="pe_neck2" class="pe_entry text"></textarea><input type="hidden" id="pe_neck2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neck2_template">Choose Template:</label><select id="pe_neck2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_neck2_normal" class="all_normal nosh_button" value=""><label for="pe_neck2_normal">All Normal</label><button type="button" id="pe_neck2_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neck2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_resp_dialog" title="Respiratory">
	<input type="checkbox" id="pe_resp_normal" class="all_normal1 nosh_button" value=""><label for="pe_resp_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_resp_accordion">
		<h3><a href="#"><span id="pe_resp1_h"></span>Effort</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp1">Preview:</label><textarea style="width:95%" rows="20" name="pe_resp1" id="pe_resp1" class="pe_entry text"></textarea><input type="hidden" id="pe_resp1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp1_template">Choose Template:</label><select id="pe_resp1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_resp1_normal" class="all_normal nosh_button" value=""><label for="pe_resp1_normal">All Normal</label><button type="button" id="pe_resp1_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_resp2_h"></span>Percussion</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp2">Preview:</label><textarea style="width:95%" rows="20" name="pe_resp2" id="pe_resp2" class="pe_entry text"></textarea><input type="hidden" id="pe_resp2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp2_template">Choose Template:</label><select id="pe_resp2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_resp2_normal" class="all_normal nosh_button" value=""><label for="pe_resp2_normal">All Normal</label><button type="button" id="pe_resp2_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_resp3_h"></span>Palpation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp3">Preview:</label><textarea style="width:95%" rows="20" name="pe_resp3" id="pe_resp3" class="pe_entry text"></textarea><input type="hidden" id="pe_resp3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp3_template">Choose Template:</label><select id="pe_resp3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_resp3_normal" class="all_normal nosh_button" value=""><label for="pe_resp3_normal">All Normal</label><button type="button" id="pe_resp3_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_resp4_h"></span>Auscultation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp4">Preview:</label><textarea style="width:95%" rows="20" name="pe_resp4" id="pe_resp4" class="pe_entry text"></textarea><input type="hidden" id="pe_resp4_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_resp4_template">Choose Template:</label><select id="pe_resp4_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_resp4_normal" class="all_normal nosh_button" value=""><label for="pe_resp4_normal">All Normal</label><button type="button" id="pe_resp4_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp4_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_cv_dialog" title="Cardiovascular">
	<input type="checkbox" id="pe_cv_normal" class="all_normal1 nosh_button" value=""><label for="pe_cv_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_cv_accordion">
		<h3><a href="#"><span id="pe_cv1_h"></span>Palpation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv1">Preview:</label><textarea style="width:95%" rows="20" name="pe_cv1" id="pe_cv1" class="pe_entry text"></textarea><input type="hidden" id="pe_cv1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv1_template">Choose Template:</label><select id="pe_cv1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_cv1_normal" class="all_normal nosh_button" value=""><label for="pe_cv1_normal">All Normal</label><button type="button" id="pe_cv1_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv2_h"></span>Auscultation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv2">Preview:</label><textarea style="width:95%" rows="20" name="pe_cv2" id="pe_cv2" class="pe_entry text"></textarea><input type="hidden" id="pe_cv2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv2_template">Choose Template:</label><select id="pe_cv2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_cv2_normal" class="all_normal nosh_button" value=""><label for="pe_cv2_normal">All Normal</label><button type="button" id="pe_cv2_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv3_h"></span>Carotid Arteries</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv3">Preview:</label><textarea style="width:95%" rows="20" name="pe_cv3" id="pe_cv3" class="pe_entry text"></textarea><input type="hidden" id="pe_cv3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv3_template">Choose Template:</label><select id="pe_cv3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_cv3_normal" class="all_normal nosh_button" value=""><label for="pe_cv3_normal">All Normal</label><button type="button" id="pe_cv3_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv4_h"></span>Abdominal Aorta</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv4">Preview:</label><textarea style="width:95%" rows="20" name="pe_cv4" id="pe_cv4" class="pe_entry text"></textarea><input type="hidden" id="pe_cv4_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv4_template">Choose Template:</label><select id="pe_cv4_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_cv4_normal" class="all_normal nosh_button" value=""><label for="pe_cv4_normal">All Normal</label><button type="button" id="pe_cv4_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv4_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv5_h"></span>Femoral Arteries</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv5">Preview:</label><textarea style="width:95%" rows="20" name="pe_cv5" id="pe_cv5" class="pe_entry text"></textarea><input type="hidden" id="pe_cv5_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv5_template">Choose Template:</label><select id="pe_cv5_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_cv5_normal" class="all_normal nosh_button" value=""><label for="pe_cv5_normal">All Normal</label><button type="button" id="pe_cv5_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv5_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv6_h"></span>Extremities</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv6">Preview:</label><textarea style="width:95%" rows="20" name="pe_cv6" id="pe_cv6" class="pe_entry text"></textarea><input type="hidden" id="pe_cv6_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_cv6_template">Choose Template:</label><select id="pe_cv6_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_cv6_normal" class="all_normal nosh_button" value=""><label for="pe_cv6_normal">All Normal</label><button type="button" id="pe_cv6_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv6_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_ch_dialog" title="Chest">
	<input type="checkbox" id="pe_ch_normal" class="all_normal1 nosh_button" value=""><label for="pe_ch_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_ch_accordion">
		<h3><a href="#"><span id="pe_ch1_h"></span>Inspection</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ch1">Preview:</label><textarea style="width:95%" rows="20" name="pe_ch1" id="pe_ch1" class="pe_entry text"></textarea><input type="hidden" id="pe_ch1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ch1_template">Choose Template:</label><select id="pe_ch1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ch1_normal" class="all_normal nosh_button" value=""><label for="pe_ch1_normal">All Normal</label><button type="button" id="pe_ch1_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ch1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ch2_h"></span>Palpation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ch2">Preview:</label><textarea style="width:95%" rows="20" name="pe_ch2" id="pe_ch2" class="pe_entry text"></textarea><input type="hidden" id="pe_ch2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ch2_template">Choose Template:</label><select id="pe_ch2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ch2_normal" class="all_normal nosh_button" value=""><label for="pe_ch2_normal">All Normal</label><button type="button" id="pe_ch2_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ch2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_gi_dialog" title="Gastrointestinal">
	<input type="checkbox" id="pe_gi_normal" class="all_normal1 nosh_button" value=""><label for="pe_gi_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_gi_accordion">
		<h3><a href="#"><span id="pe_gi1_h"></span>Masses and Tenderness</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi1">Preview:</label><textarea style="width:95%" rows="20" name="pe_gi1" id="pe_gi1" class="pe_entry text"></textarea><input type="hidden" id="pe_gi1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi1_template">Choose Template:</label><select id="pe_gi1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_gi1_normal" class="all_normal nosh_button" value=""><label for="pe_gi1_normal">All Normal</label><button type="button" id="pe_gi1_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gi2_h"></span>Liver and Spleen</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi2">Preview:</label><textarea style="width:95%" rows="20" name="pe_gi2" id="pe_gi2" class="pe_entry text"></textarea><input type="hidden" id="pe_gi2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi2_template">Choose Template:</label><select id="pe_gi2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_gi2_normal" class="all_normal nosh_button" value=""><label for="pe_gi2_normal">All Normal</label><button type="button" id="pe_gi2_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gi3_h"></span>Hernia</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi3">Preview:</label><textarea style="width:95%" rows="20" name="pe_gi3" id="pe_gi3" class="pe_entry text"></textarea><input type="hidden" id="pe_gi3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi3_template">Choose Template:</label><select id="pe_gi3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_gi3_normal" class="all_normal nosh_button" value=""><label for="pe_gi3_normal">All Normal</label><button type="button" id="pe_gi3_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gi4_h"></span>Anus, Perineum, and Rectum</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi4">Preview:</label><textarea style="width:95%" rows="20" name="pe_gi4" id="pe_gi4" class="pe_entry text"></textarea><input type="hidden" id="pe_gi4_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_gi4_template">Choose Template:</label><select id="pe_gi4_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_gi4_normal" class="all_normal nosh_button" value=""><label for="pe_gi4_normal">All Normal</label><button type="button" id="pe_gi4_reset" class="reset nosh_button">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi4_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_gu_dialog" title="Genitourinary">
	<input type="checkbox" id="pe_gu_normal" class="all_normal1 nosh_button" value=""><label for="pe_gu_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_gu_accordion">
		<?php if (Session::get('gender') == 'female') {?>
			<h3><a href="#"><span id="pe_gu1_h"></span>Genitalia</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu1">Preview:</label><textarea style="width:95%" rows="20" name="pe_gu1" id="pe_gu1" class="pe_entry text"></textarea><input type="hidden" id="pe_gu1_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu1_template">Choose Template:</label><select id="pe_gu1_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu1_normal" class="all_normal nosh_button" value=""><label for="pe_gu1_normal">All Normal</label><button type="button" id="pe_gu1_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu1_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu2_h"></span>Urethra</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu2">Preview:</label><textarea style="width:95%" rows="20" name="pe_gu2" id="pe_gu2" class="pe_entry text"></textarea><input type="hidden" id="pe_gu2_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu2_template">Choose Template:</label><select id="pe_gu2_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu2_normal" class="all_normal nosh_button" value=""><label for="pe_gu2_normal">All Normal</label><button type="button" id="pe_gu2_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu2_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu3_h"></span>Bladder</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu3">Preview:</label><textarea style="width:95%" rows="20" name="pe_gu3" id="pe_gu3" class="pe_entry text"></textarea><input type="hidden" id="pe_gu3_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu3_template">Choose Template:</label><select id="pe_gu3_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu3_normal" class="all_normal nosh_button" value=""><label for="pe_gu3_normal">All Normal</label><button type="button" id="pe_gu3_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu3_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu4_h"></span>Cervix</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu4">Preview:</label><textarea style="width:95%" rows="20" name="pe_gu4" id="pe_gu4" class="pe_entry text"></textarea><input type="hidden" id="pe_gu4_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu4_template">Choose Template:</label><select id="pe_gu4_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu4_normal" class="all_normal nosh_button" value=""><label for="pe_gu4_normal">All Normal</label><button type="button" id="pe_gu4_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu4_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu5_h"></span>Uterus</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu5">Preview:</label><textarea style="width:95%" rows="20" name="pe_gu5" id="pe_gu5" class="pe_entry text"></textarea><input type="hidden" id="pe_gu5_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu5_template">Choose Template:</label><select id="pe_gu5_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu5_normal" class="all_normal nosh_button" value=""><label for="pe_gu5_normal">All Normal</label><button type="button" id="pe_gu5_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu5_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu6_h"></span>Adnexa</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu6">Preview:</label><textarea style="width:95%" rows="20" name="pe_gu6" id="pe_gu6" class="pe_entry text"></textarea><input type="hidden" id="pe_gu6_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu6_template">Choose Template:</label><select id="pe_gu6_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu6_normal" class="all_normal nosh_button" value=""><label for="pe_gu6_normal">All Normal</label><button type="button" id="pe_gu6_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu6_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
		<?php } else {?>
			<h3><a href="#"><span id="pe_gu7_h"></span>Scrotum</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu7">Preview:</label><textarea style="width:95%" rows="8" name="pe_gu7" id="pe_gu7" class="pe_entry text"></textarea><input type="hidden" id="pe_gu7_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu7_template">Choose Template:</label><select id="pe_gu7_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu7_normal" class="all_normal nosh_button" value=""><label for="pe_gu7_normal">All Normal</label><button type="button" id="pe_gu7_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu7_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu8_h"></span>Penis</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu8">Preview:</label><textarea style="width:95%" rows="8" name="pe_gu8" id="pe_gu8" class="pe_entry text"></textarea><input type="hidden" id="pe_gu8_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu8_template">Choose Template:</label><select id="pe_gu8_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu8_normal" class="all_normal nosh_button" value=""><label for="pe_gu8_normal">All Normal</label><button type="button" id="pe_gu8_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu8_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
			<h3><a href="#"><span id="pe_gu9_h"></span>Prostate</a></h3>
			<div class="pure-g">
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu9">Preview:</label><textarea style="width:95%" rows="8" name="pe_gu9" id="pe_gu9" class="pe_entry text"></textarea><input type="hidden" id="pe_gu9_old"/>
					</form>
				</div>
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="pe_gu9_template">Choose Template:</label><select id="pe_gu9_template" class="pe_template_choose text"></select><br>
					</form>
					<input type="checkbox" id="pe_gu9_normal" class="all_normal nosh_button" value=""><label for="pe_gu9_normal">All Normal</label><button type="button" id="pe_gu9_reset" class="reset nosh_button">Clear</button><br>
					<div class="pe_template_div">
						<br><form id="pe_gu9_form" class="pe_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
		<?php }?>
	</div>
</div>
<div id="pe_lymph_dialog" title="Lymphatic">
	<input type="checkbox" id="pe_lymph_normal" class="all_normal1 nosh_button" value=""><label for="pe_lymph_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_lymph_accordion">
		<h3><a href="#"><span id="pe_lymph1_h"></span>Neck</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_lymph1">Preview:</label><textarea style="width:95%" rows="20" name="pe_lymph1" id="pe_lymph1" class="pe_entry text"></textarea><input type="hidden" id="pe_lymph1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_lymph1_template">Choose Template:</label><select id="pe_lymph1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_lymph1_normal" class="all_normal nosh_button" value=""><label for="pe_lymph1_normal">All Normal</label><button type="button" id="pe_lymph1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_lymph1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_lymph2_h"></span>Axillae</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_lymph2">Preview:</label><textarea style="width:95%" rows="20" name="pe_lymph2" id="pe_lymph2" class="pe_entry text"></textarea><input type="hidden" id="pe_lymph2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_lymph2_template">Choose Template:</label><select id="pe_lymph2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_lymph2_normal" class="all_normal nosh_button" value=""><label for="pe_lymph2_normal">All Normal</label><button type="button" id="pe_lymph2_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_lymph2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_lymph3_h"></span>Groin</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_lymph3">Preview:</label><textarea style="width:95%" rows="20" name="pe_lymph3" id="pe_lymph3" class="pe_entry text"></textarea><input type="hidden" id="pe_lymph3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_lymph3_template">Choose Template:</label><select id="pe_lymph3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_lymph3_normal" class="all_normal nosh_button" value=""><label for="pe_lymph3_normal">All Normal</label><button type="button" id="pe_lymph3_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_lymph3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_ms_dialog" title="Musculoskeletal">
	<input type="checkbox" id="pe_ms_normal" class="all_normal1 nosh_button" value=""><label for="pe_ms_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_ms_accordion">
		<h3><a href="#"><span id="pe_ms1_h"></span>Gait and Station</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms1">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms1" id="pe_ms1" class="pe_entry text"></textarea><input type="hidden" id="pe_ms1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms1_template">Choose Template:</label><select id="pe_ms1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms1_normal" class="all_normal nosh_button" value=""><label for="pe_ms1_normal">All Normal</label><button type="button" id="pe_ms1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms2_h"></span>Digits and Nails</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms2">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms2" id="pe_ms2" class="pe_entry text"></textarea><input type="hidden" id="pe_ms2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms2_template">Choose Template:</label><select id="pe_ms2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms2_normal" class="all_normal nosh_button" value=""><label for="pe_ms2_normal">All Normal</label><button type="button" id="pe_ms2_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms3_h"></span>Bones, Joints, and Muscles - Shoulder</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms3">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms3" id="pe_ms3" class="pe_entry text"></textarea><input type="hidden" id="pe_ms3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms3_template">Choose Template:</label><select id="pe_ms3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms3_normal" class="all_normal nosh_button" value=""><label for="pe_ms3_normal">All Normal</label><input type="checkbox" id="pe_ms3_normal1" class="all_normal2 nosh_button" value="Full range of motion of the shoulders bilaterally."><label for="pe_ms3_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms3_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms4_h"></span>Bones, Joints, and Muscles - Elbow</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms4">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms4" id="pe_ms4" class="pe_entry text"></textarea><input type="hidden" id="pe_ms4_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms4_template">Choose Template:</label><select id="pe_ms4_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms4_normal" class="all_normal nosh_button" value=""><label for="pe_ms4_normal">All Normal</label><input type="checkbox" id="pe_ms4_normal1" class="all_normal2 nosh_button" value="Full range of motion of the elbows bilaterally."><label for="pe_ms4_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms4_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms4_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms5_h"></span>Bones, Joints, and Muscles - Wrist</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms5">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms5" id="pe_ms5" class="pe_entry text"></textarea><input type="hidden" id="pe_ms5_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms5_template">Choose Template:</label><select id="pe_ms5_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms5_normal" class="all_normal nosh_button" value=""><label for="pe_ms5_normal">All Normal</label><input type="checkbox" id="pe_ms5_normal1" class="all_normal2 nosh_button" value="Full range of motion of the wrists bilaterally."><label for="pe_ms5_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms5_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms5_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms6_h"></span>Bones, Joints, and Muscles - Hand</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms6">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms6" id="pe_ms6" class="pe_entry text"></textarea><input type="hidden" id="pe_ms6_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms6_template">Choose Template:</label><select id="pe_ms6_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms6_normal" class="all_normal nosh_button" value=""><label for="pe_ms6_normal">All Normal</label><input type="checkbox" id="pe_ms6_normal1" class="all_normal2 nosh_button" value="Full range of motion of the fingers and hands bilaterally."><label for="pe_ms6_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms6_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms6_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms7_h"></span>Bones, Joints, and Muscles - Hip</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms7">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms7" id="pe_ms7" class="pe_entry text"></textarea><input type="hidden" id="pe_ms7_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms7_template">Choose Template:</label><select id="pe_ms7_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms7_normal" class="all_normal nosh_button" value=""><label for="pe_ms7_normal">All Normal</label><input type="checkbox" id="pe_ms7_normal1" class="all_normal2 nosh_button" value="Full range of motion of the hips bilaterally."><label for="pe_ms7_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms7_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms7_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms8_h"></span>Bones, Joints, and Muscles - Knee</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms8">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms8" id="pe_ms8" class="pe_entry text"></textarea><input type="hidden" id="pe_ms8_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms8_template">Choose Template:</label><select id="pe_ms8_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms8_normal" class="all_normal nosh_button" value=""><label for="pe_ms8_normal">All Normal</label><input type="checkbox" id="pe_ms8_normal1" class="all_normal2 nosh_button" value="Full range of motion of the knees bilaterally."><label for="pe_ms8_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms8_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms8_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms9_h"></span>Bones, Joints, and Muscles - Ankle</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms9">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms9" id="pe_ms9" class="pe_entry text"></textarea><input type="hidden" id="pe_ms9_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms9_template">Choose Template:</label><select id="pe_ms9_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms9_normal" class="all_normal nosh_button" value=""><label for="pe_ms9_normal">All Normal</label><input type="checkbox" id="pe_ms9_normal1" class="all_normal2 nosh_button" value="Full range of motion of the ankles bilaterally."><label for="pe_ms9_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms9_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms9_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms10_h"></span>Bones, Joints, and Muscles - Foot</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms10">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms10" id="pe_ms10" class="pe_entry text"></textarea><input type="hidden" id="pe_ms10_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms10_template">Choose Template:</label><select id="pe_ms10_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms10_normal" class="all_normal nosh_button" value=""><label for="pe_ms10_normal">All Normal</label><input type="checkbox" id="pe_ms10_normal1" class="all_normal2 nosh_button" value="Full range of motion of the toes and feet bilaterally."><label for="pe_ms10_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms10_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms10_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms11_h"></span>Bones, Joints, and Muscles - Cervical Spine</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms11">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms11" id="pe_ms11" class="pe_entry text"></textarea><input type="hidden" id="pe_ms11_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms11_template">Choose Template:</label><select id="pe_ms11_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms11_normal" class="all_normal nosh_button" value=""><label for="pe_ms11_normal">All Normal</label><input type="checkbox" id="pe_ms11_normal1" class="all_normal2 nosh_button" value="Full range of motion of the cervical spine."><label for="pe_ms11_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms11_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms11_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms12_h"></span>Bones, Joints, and Muscles - Thoracic and Lumbar Spine</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms12">Preview:</label><textarea style="width:95%" rows="20" name="pe_ms12" id="pe_ms12" class="pe_entry text"></textarea><input type="hidden" id="pe_ms12_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_ms12_template">Choose Template:</label><select id="pe_ms12_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_ms12_normal" class="all_normal nosh_button" value=""><label for="pe_ms12_normal">All Normal</label><input type="checkbox" id="pe_ms12_normal1" class="all_normal2 nosh_button" value="Full range of motion of the thoracic and lumbar spine."><label for="pe_ms12_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms12_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_ms12_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_neuro_dialog" title="Neurological">
	<input type="checkbox" id="pe_neuro_normal" class="all_normal1 nosh_button" value=""><label for="pe_neuro_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_neuro_accordion">
		<h3><a href="#"><span id="pe_neuro1_h"></span>Cranial Nerves</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neuro1">Preview:</label><textarea style="width:95%" rows="20" name="pe_neuro1" id="pe_neuro1" class="pe_entry text"></textarea><input type="hidden" id="pe_neuro1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neuro1_template">Choose Template:</label><select id="pe_neuro1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_neuro1_normal" class="all_normal nosh_button" value=""><label for="pe_neuro1_normal">All Normal</label><button type="button" id="pe_neuro1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_neuro1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_neuro2_h"></span>Deep Tendon Reflexes</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neuro2">Preview:</label><textarea style="width:95%" rows="20" name="pe_neuro2" id="pe_neuro2" class="pe_entry text"></textarea><input type="hidden" id="pe_neuro2_old"/><br>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neuro2_template">Choose Template:</label><select id="pe_neuro2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_neuro2_normal" class="all_normal nosh_button" value=""><label for="pe_neuro2_normal">All Normal</label><input type="checkbox" id="pe_neuro2_normal1" class="all_normal2 nosh_button" value="Biceps, Patellar, and Achillies deep tendon reflexes are equal bilaterally."><label for="pe_neuro2_normal1">Equal and Bilateral</label><button type="button" id="pe_neuro2_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_neuro2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_neuro3_h"></span>Sensation and Motor</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neuro3">Preview:</label><textarea style="width:95%" rows="20" name="pe_neuro3" id="pe_neuro3" class="pe_entry text"></textarea><input type="hidden" id="pe_neuro3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_neuro3_template">Choose Template:</label><select id="pe_neuro3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_neuro3_normal" class="all_normal nosh_button" value=""><label for="pe_neuro3_normal">All Normal</label><button type="button" id="pe_neuro3_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_neuro3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_psych_dialog" title="Psychological">
	<input type="checkbox" id="pe_psych_normal" class="all_normal1 nosh_button" value=""><label for="pe_psych_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_psych_accordion">
		<h3><a href="#"><span id="pe_psych1_h"></span>Judgement and Insight</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych1">Preview:</label><textarea style="width:95%" rows="20" name="pe_psych1" id="pe_psych1" class="pe_entry text"></textarea><input type="hidden" id="pe_psych1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych1_template">Choose Template:</label><select id="pe_psych1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_psych1_normal" class="all_normal nosh_button" value=""><label for="pe_psych1_normal">All Normal</label><button type="button" id="pe_psych1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_psych1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_psych2_h"></span>Orientation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych2">Preview:</label><textarea style="width:95%" rows="20" name="pe_psych2" id="pe_psych2" class="pe_entry text"></textarea><input type="hidden" id="pe_psych2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych2_template">Choose Template:</label><select id="pe_psych2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_psych2_normal" class="all_normal nosh_button" value=""><label for="pe_psych2_normal">All Normal</label><button type="button" id="pe_psych2_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_psych2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_psych3_h"></span>Memory</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych3">Preview:</label><textarea style="width:95%" rows="20" name="pe_psych3" id="pe_psych3" class="pe_entry text"></textarea><input type="hidden" id="pe_psych3_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych3_template">Choose Template:</label><select id="pe_psych3_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_psych3_normal" class="all_normal nosh_button" value=""><label for="pe_psych3_normal">All Normal</label><button type="button" id="pe_psych3_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_psych3_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_psych4_h"></span>Mood and Affect</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych4">Preview:</label><textarea style="width:95%" rows="20" name="pe_psych4" id="pe_psych4" class="pe_entry text"></textarea><input type="hidden" id="pe_psych4_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_psych4_template">Choose Template:</label><select id="pe_psych4_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_psych4_normal" class="all_normal nosh_button" value=""><label for="pe_psych4_normal">All Normal</label><button type="button" id="pe_psych4_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_psych4_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_skin_dialog" title="Skin">
	<input type="checkbox" id="pe_skin_normal" class="all_normal1 nosh_button" value=""><label for="pe_skin_normal">All Normal</label>
	<br><hr class="ui-state-default" style="width:99%"/>
	<div id="pe_skin_accordion">
		<h3><a href="#"><span id="pe_skin1_h"></span>Inspection</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_skin1">Preview:</label><textarea style="width:95%" rows="20" name="pe_skin1" id="pe_skin1" class="pe_entry text"></textarea><input type="hidden" id="pe_skin1_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_skin1_template">Choose Template:</label><select id="pe_skin1_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_skin1_normal" class="all_normal nosh_button" value=""><label for="pe_skin1_normal">All Normal</label><button type="button" id="pe_skin1_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_skin1_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_skin2_h"></span>Palpation</a></h3>
		<div class="pure-g">
			<div class="pure-u-11-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_skin2">Preview:</label><textarea style="width:95%" rows="20" name="pe_skin2" id="pe_skin2" class="pe_entry text"></textarea><input type="hidden" id="pe_skin2_old"/>
				</form>
			</div>
			<div class="pure-u-13-24">
				<form class="pure-form pure-form-stacked">
					<label for="pe_skin2_template">Choose Template:</label><select id="pe_skin2_template" class="pe_template_choose text"></select><br>
				</form>
				<input type="checkbox" id="pe_skin2_normal" class="all_normal nosh_button" value=""><label for="pe_skin2_normal">All Normal</label><button type="button" id="pe_skin2_reset" class="reset nosh_button">Clear</button><br>
				<div class="pe_template_div">
					<br><form id="pe_skin2_form" class="pe_template_form ui-widget pure-form"></form>
				</div>
			</div>
		</div>
	</div>
</div>
