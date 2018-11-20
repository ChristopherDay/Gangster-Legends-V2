<?php

    class propertyManagementTemplate extends template {
        
        public $propertyManagement = '

    		<div class="row">
        		<div class="col-md-6">
		        	<h4>Update Cost or Max Bet</h4>

					<form action="?page=propertyManagement&module={module}&action=cost" method="post">
		                <input type="number" name="cost" class="form-control form-control-inline" value="{cost}" /> 
		                <button class="btn btn-default">Update</button>
					</form>
				</div>
        		<div class="col-md-6">
		        	<h4>Transfer Ownership</h4>

					<form action="?page=propertyManagement&module={module}&action=transfer" method="post">
		                <input type="text" name="transfer" class="form-control form-control-inline" /> 
		                <button class="btn btn-default">Transfer</button>
					</form>
				</div>
			</div>

        	<h3>
        		Total Profit: {profit}
        	</h3>
    		<small>
    			<a href="?page=propertyManagement&module={module}&action=reset">Reset profit loss</a>
    		</small>

        ';
        
    }

?>