<?php

    class propertyManagementTemplate extends template {
        
        public $propertyManagement = '

    		<div class="row">
        		<div class="col-md-6">

		        	<div class="panel panel-primary">
		                <div class="panel-heading">Update Cost or Max Bet</div>
		                <div class="panel-body">
							<form action="?page=propertyManagement&module={module}&action=cost" method="post">
				                <input type="number" name="cost" class="form-control form-control-inline" value="{cost}" /> 
				                <button class="btn btn-default">Update</button>
							</form>
		                </div>
		            </div>
				</div>
        		<div class="col-md-6">
		        	<div class="panel panel-primary">
		                <div class="panel-heading">Transfer Ownership</div>
		                <div class="panel-body">
							<form action="?page=propertyManagement&module={module}&action=transfer" method="post">
				                <input type="text" name="transfer" class="form-control form-control-inline" /> 
				                <button class="btn btn-default">Transfer</button>
							</form>
		                </div>
		            </div>
				</div>
			</div>


        	<div class="panel panel-primary">
                <div class="panel-heading">Accounts</div>
                <div class="panel-body">
		        	<h3>
		        		Total Profit: {profit}
		        	</h3>
		    		<small>
		    			<a href="?page=propertyManagement&module={module}&action=reset">Reset profit loss</a>
		    		</small>
                </div>
            </div>


        ';
        
    }

?>