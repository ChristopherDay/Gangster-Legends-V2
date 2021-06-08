<?php

    class gangBankTemplate extends template {
        
        public $bank = '
        <div class="row">
            <div class="col-md-6">
                <form action="?page=gangBank&action=process" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">Cash</div>
                        <div class="panel-body">
                            <p style="height:54px; line-height:18px;">
                                To launder your money so it is safe to deposit in your gangs bank account, You will be charged 15%!
                            </p>
                            <p>
                                <input type="text" class="form-control" value="" name="deposit" />
                            </p>
                            <p class="text-right">
                                {#if canWithdrawCash}
                                    <span class="pull-left">
                                        <strong>Current Ballance:</strong> {#money cash}
                                    </span>
                                {/if}
                                <button type="submit" class="btn btn-danger" name="bank" value="deposit">
                                    Deposit
                                </button>
                                {#if canWithdrawCash}
                                    <button type="submit" class="btn btn-success" name="bank" value="withdraw">
                                        Withdraw
                                    </button>
                                {/if}
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form action="?page=gangBank&action=processBullets" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">Bullets</div>
                        <div class="panel-body">
                            <p style="height:54px; line-height:18px;">
                                To deposit bullets into the gang bank you will loose 25% of the bullets.
                            </p>
                            <p>
                                <input type="text" class="form-control" value="" name="deposit" />
                            </p>
                            <p class="text-right">
                                {#if canWithdrawBullets}
                                    <span class="pull-left">
                                        <strong>Current Stock:</strong> {number_format bullets}
                                    </span>
                                {/if}
                                <button type="submit" class="btn btn-danger" name="bank" value="deposit">
                                    Deposit
                                </button>
                                {#if canWithdrawBullets}
                                    <button type="submit" class="btn btn-success" name="bank" value="withdraw">
                                        Withdraw
                                    </button>
                                {/if}
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        ';
        
    }

