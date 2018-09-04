<?php

    class bankTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $blankElement = '{var1} {var2} {var3}';
		
		public $bank = '
		<form action="?page=bank&action=process" method="post">
			<div class="row">
				<div class="col-md-6">
					<h3>Deposit</h3>
					<p style="height:54px; line-height:18px;">
						To launder your money so it is safe to deposit in your bank account will cost you 15% of the ammount you are going to deposit!
					</p>
					<p>
						<input type="text" class="form-control" value="{deposit}" name="deposit" />
					</p>
					<p class="pull-right">
						<button type="submit" class="btn" name="bank" value="deposit">Deposit</button>
					</p>
				</div>
				<div class="col-md-6">
					<h3>Withdraw</h3>
					<p style="height:54px; line-height:18px;">
						There is no cost to withdraw your money!<br />
					</p>
					<p>
						<input type="text" class="form-control" value="{withdraw}" name="withdraw" />
					</p>
					<p class="pull-right">
						<button type="submit" class="btn" name="bank" value="withdraw">Withdraw</button>
					</p>
				</div>
			</div>
		</form>';
        
    }

?>