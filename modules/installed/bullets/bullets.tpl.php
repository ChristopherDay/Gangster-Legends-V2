<?php

    class bulletsTemplate extends template {


         public $bulletOptions = '

            <form method="post" action="#">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Max cost per bullet ($)</label>
                            <input type="text" class="form-control" name="maxBulletCost" value="{maxBulletCost}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Minimum restock per hour</label>
                            <input type="text" class="form-control" name="bulletsStockMinPerHour" value="{bulletsStockMinPerHour}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Maximum restock per hour</label>
                            <input type="text" class="form-control" name="bulletsStockMaxPerHour" value="{bulletsStockMaxPerHour}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Max bullets to buy per transaction</label>
                            <input type="text" class="form-control" name="maxBulletBuy" value="{maxBulletBuy}" />
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>

            </form>
        ';
        
        public $bulletPage = '
        <div class="panel panel-default">
            <div class="panel-heading">{locationName} Bullet Factory</div>
            <div class="panel-body">


                {#if closed}
                    This property is currently closed!
                {/if}

                {#unless closed}
                    <form action="?page=bullets&action=buy" method="post">
                        <table class="table table-condensed table-responsive table-bordered table-striped" style="width:250px; margin: auto;">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        Buy Bullets
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bullet Stock</td>
                                    <td width="100px">{stock}</td>
                                </tr>
                                <tr>
                                    <td>Price Per Bullet</td>
                                    <td>{cost}</td>
                                </tr>
                                <tr>
                                    <td>Buy Limit</td>
                                    <td>{maxBuy}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" autocomplete="off" name="bullets" placeholder="Qty. to buy" />
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-default btn-block" style="margin: 0px;">Buy</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <small>
                            {>propertyOwnership}
                        </small>
                    </form>
                {/unless}
            </div>
        </div>
        ';
        
    }

