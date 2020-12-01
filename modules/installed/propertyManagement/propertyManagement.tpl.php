<?php

    class propertyManagementTemplate extends template {

        public $dropProperty = '
            <div class="panel panel-default">
                <div class="panel-heading">Are you sure?</div>
                <div class="panel-body">
                    <p>
                        Are you sure that you want to drop this property?
                    </p>
                    <a href="?page=propertyManagement&module={module}&action=dropDo&code={code}" class="btn btn-danger"> 
                        Drop property 
                    </a>
                </div>
            </div>
        ';

        public $propertyManagement = '

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Update Cost or Max Bet</div>
                        <div class="panel-body">
                            <form action="?page=propertyManagement&module={module}&action=cost" method="post">
                                <input type="number" name="cost" class="form-control" value="{cost}" /> 
                                <button class="btn btn-default">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Transfer Ownership</div>
                        <div class="panel-body">
                            <form action="?page=propertyManagement&module={module}&action=transfer" method="post">
                                <input type="text" name="transfer" class="form-control" /> 
                                <button class="btn btn-default">Transfer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
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
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Drop Property</div>
                        <div class="panel-body">
                            <a href="?page=propertyManagement&module={module}&action=drop" class="btn btn-danger"> Drop property </a>
                        </div>
                    </div>
                </div>
            </div>



        ';
        
    }

