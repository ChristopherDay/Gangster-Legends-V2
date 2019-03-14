<?php

    class bankTemplate extends template {
        
        public $bank = '
        <form action="?page=bank&action=process" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Deposit</div>
                        <div class="panel-body">
                            <p style="height:54px; line-height:18px;">
                                To launder your money so it is safe to deposit in your bank account will cost you 15% of the amount you are going to deposit!
                            </p>
                            <p>
                                <input type="text" class="form-control" value="{deposit}" name="deposit" />
                            </p>
                            <p class="text-right">
                                <button type="submit" class="btn" name="bank" value="deposit">Deposit</button>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
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
                                <button type="submit" class="btn" name="bank" value="withdraw">Withdraw</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <form method="post" action="?page=bank&action=transfer">
            <div class="panel panel-default">
                <div class="panel-heading">Transfer Money</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="user" placeholder="Username" />
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="money" placeholder="Money to transfer" />
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Transfer</button>
                </div>
            </div>
        </form>
        ';
        
    }

?>