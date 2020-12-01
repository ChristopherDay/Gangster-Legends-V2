<?php

    class bankTemplate extends template {


         public $options = '

            <form method="post" action="#">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">Tax (%)</label>
                            <input type="text" class="form-control" name="bankTax" value="{bankTax}" />
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>

            </form>
        ';

        
        public $bank = '
            <div class="row">
                <div class="col-md-6">
                    <form action="?page=bank&action=process" method="post">
                        <div class="panel panel-default">
                            <div class="panel-heading">Deposit</div>
                            <div class="panel-body">
                                <p style="height:54px; line-height:18px;">
                                    To launder your money so it is safe to deposit in your bank account will cost you {tax}% of the amount you are going to deposit!
                                </p>
                                <p>
                                    <input type="text" class="form-control" value="{deposit}" name="deposit" />
                                </p>
                                <p class="text-right">
                                    <button type="submit" class="btn btn-default" name="bank" value="deposit">Deposit</button>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="?page=bank&action=process" method="post">
                        <div class="panel panel-default">
                            <div class="panel-heading"> Withdraw </div>
                            <div class="panel-body">
                                <p style="height:54px; line-height:18px;">
                                    There is no cost to withdraw your money!<br />
                                </p>
                                <p>
                                    <input type="text" class="form-control" value="{withdraw}" name="withdraw" />
                                </p>
                                <p class="text-right">
                                    <button type="submit" class="btn btn-default" name="bank" value="withdraw">Withdraw</button>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <form method="post" action="?page=bank&action=transfer">
                        <div class="panel panel-default">
                            <div class="panel-heading">Transfer Money</div>
                            <div class="panel-body">
                                <p>
                                    <input type="text" class="form-control" name="user" placeholder="Username" />
                                </p>
                                <p>
                                    <input type="number" class="form-control" name="money" placeholder="Money to transfer" />
                                </p>
                                <p class="text-right">
                                    <button type="submit" class="btn btn-default" name="submit" value="1">Transfer</button>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>    
        ';
        
    }
