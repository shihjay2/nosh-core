<div id="update_dialog" title="Update">
	<div id="update_accordion">
		<h3>ICD Database Update</h3>
		<div>
			Scrapes data from www.icd9data.com into NOSH.<br><br>
			<button type="button" id="add_icd9_file" class="nosh_button">Update ICD-9 Database</button>
<!--
			<button type="button" id="add_icd10_file" class="nosh_button">Update ICD-10 Database</button>
-->
			<br><br>
			<div id="add_icd_progress">
				<input type="hidden" id="add_icd_note"/>
			</div><br>
			<div id="add_icd_progress_num"></div>
		</div>
		<h3>Medication List Update</h3>
		<div>
			Imports data from <a href="http://www.fda.gov/Drugs/InformationOnDrugs/ucm142438.htm#download" target="_blank">the FDA NDC databases.</a><br><br>
			<button type="button" id="add_med_file" class="nosh_button">Update Medication List</button><br><br>
			<div id="add_med_progress">
				<input type="hidden" id="add_med_note"/>
			</div><br>
			<div id="add_med_progress_num"></div>
		</div>
		<h3>Supplement Update</h3>
		<div>
			Imports data from <a href="http://www.nlm.nih.gov/medlineplus/druginfo/herb_All.html" target="_blank">the U.S. National Library of Medicine.</a><br><br>
			<button type="button" id="add_supplement_file" class="nosh_button" title="Imports data from the U.S. National Library of Medicine.">Update Supplement List</button><br><br>
			<div id="add_supplement_progress">
				<input type="hidden" id="add_supplement_note"/>
			</div><br>
			<div id="add_supplement_progress_num"></div>
		</div>
		<h3>CPT Update/Upgrade</h3>
		<div>
			Obtain the AMA CPT Data File disk that you have purchased.<br>
			Then upload the LONGULT.txt file here...<br><br>
			<button type="button" id="add_cpt_upload" class="nosh_button">Update CPT List</button><br><br>
			<div id="add_cpt_progress">
				<input type="hidden" id="add_cpt_note"/>
			</div><br>
			<div id="add_cpt_progress_num"></div>
		</div>
		<h3>NPI Taxonomy List Update</h3>
		<div>
			Imports data from <a href="http://www.nucc.org/" target="_blank">the National Uniform Claim Committee website.</a><br><br>
			<button type="button" id="add_npi_upload" class="nosh_button" >Update NPI List</button><br><br>
			<div id="add_npi_progress">
				<input type="hidden" id="add_npi_note"/>
			</div><br>
			<div id="add_npi_progress_num"></div>
		</div>
		<h3>CVX Update</h3>
		<div>
			Imports data from <a href="http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=cvx" target="_blank">the CDC website.</a><br><br>
			<button type="button" id="add_cvx_file" class="nosh_button">Update CVX Database</button><br><br>
			<div id="add_cvx_progress">
				<input type="hidden" id="add_cvx_note"/>
			</div><br>
			<div id="add_cvx_progress_num"></div>
		</div>
	</div>
</div>
