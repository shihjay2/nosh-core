<div id="financial_dialog" title="Financial">
	<div id="financial_accordion">
		<h3>Bill Submission</h3>
		<div>
			<table id="submit_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="submit_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="submit_batch" class="nosh_button_print">Create Batched Print Image</button>
			<button type="button" id="submit_batch1" class="nosh_button_print">Create Batched HCFA-1500 - Editable</button>
			<button type="button" id="submit_batch2" class="nosh_button_print">Create Batched HCFA-1500</button><br><br>
			<table id="bills_done" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="bills_done_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="bill_resubmit" class="nosh_button_reactivate">Resubmit Bill</button>
			<button type="button" id="payment_encounter_charge1" class="nosh_button_check">Make Payment to Encounter</button>
		</div>
		<h3>Outstanding Balances</h3>
		<div>
			<table id="outstanding_balance" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="outstanding_balance_pager" class="scroll" style="text-align:center;"></div>
		</div>
		<h3>Import ERA 835 Files</h3>
		<div>
			<button type="button" id="import_era" class="nosh_button_add">Upload 835 File</button><br><br>
			<div id="claim_associate_div"></div><br>
			<table id="era_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="era_list_pager" class="scroll" style="text-align:center;"></div>
		</div>
		<h3>Reports</h3>
		<div>
			<table id="monthly_stats" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="monthly_stats_pager" class="scroll" style="text-align:center;"></div><br>
			<table id="yearly_stats" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="yearly_stats_pager" class="scroll" style="text-align:center;"></div><br>
			<form id="financial_query_form" class="pure-form pure-form-aligned">
				<fieldset>
					<legend>Query</legend>
					<div id="financial_query_div">
						<table>
							<div class="pure-control-group"><label for="financial_query_type">Search:</label><select name="type" id="financial_query_type" class="text" required></select></div>
							<div class="pure-control-group"><label for="financial_query_variables">Variables:</label><select name="variables[]" multiple="multiple"  style="width:400px" id="financial_query_variables" required></select></div>
							<div class="pure-control-group"><label for="financial_query_year">Year:</label><select name="year[]" multiple="multiple"style="width:400px"  id="financial_query_year" required></select></div>
						</table>
					</div><br>
					<button type="button" id="financial_query_submit" class="nosh_button">Submit Query</button> <button type="button" id="financial_query_print" class="nosh_button_print">Print Results</button> <button type="button" id="financial_query_reset" class="nosh_button">Reset Query</button><br><br>
				</fieldset><br><br>
			</form>
			<table id="financial_query_results" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="financial_query_results_pager" class="scroll" style="text-align:center;"></div><br>
		</div>
	</div>
</div>
<div id="submit_bill_dialog" title="Choose Method Of Bill Submission">
	<form name="submit_bill_form" id="submit_bill_form_id">
		<input type="hidden" id="billing_eid"/>
		<button type="button" id="submit_batch_printimage" class="nosh_button_add" style="width:210px">Batch Print Image</button><br><br>
		<button type="button" id="submit_batch_hcfa" class="nosh_button_add" style="width:210px">Batch Print HCFA-1500</button><br><br>
		<button type="button" id="submit_single_printimage" class="nosh_button_print" style="width:210px">Create Single Print Image</button><br><br>
		<button type="button" id="submit_hcfa" class="nosh_button_print" style="width:210px">Print HCFA-1500 - Editable</button><br><br>
		<button type="button" id="submit_hcfa2" class="nosh_button_print" style="width:210px">Print HCFA-1500</button>
	</form>
</div>
<div id="era_dialog" title="ERA Details"></div>
