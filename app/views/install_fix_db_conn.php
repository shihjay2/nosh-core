<div id="logo">Nosh</div><br>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="installcontent">
		<h3>Welcome to the NOSH ChartingSystem Database Connection Fixer</h3>
		<form id="db_fix" class="pure-form pure-form-aligned">
			<p>You are here because the NOSH ChartingSystem database is not available at this time.  This could be due to two things:</p>
			<ul>
				<li>The MySQL server is not activated or stalled on your system.  In your terminal of the server where NOSH ChartingSystem resides, type in <code>sudo service mysql restart</code>.  Afterwards, try to login to NOSH ChartingSystem again.</li>
				<li>The MySQL username and/or password combination has changed.  If so, please fill out the entries to repair the installation of NOSH ChartingSystem.</li>
			</ul>
			<div style="width: 50%;margin: 0 auto;">
				<div class="pure-control-group">
					<label for="db_username">MySQL username</label>
					<input type="text" id="db_username" name="db_username" style='width:500px' class='text' required/>
				</div>
				<div class="pure-control-group">
					<label for="db_password">MySQL password</label>
					<input type="password" id="db_password" name="db_password" style='width:500px' class='text' required/>
				</div>
				<br><button type="button" id="db_submit">Repair NOSH ChartingSystem</button>
			</div>
		</form>
	</div>
</div>
