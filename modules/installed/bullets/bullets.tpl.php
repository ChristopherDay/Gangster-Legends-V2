<?php

    class bulletsTemplate extends template {
        
        public $bulletPage = '

        <h3>
            {locationName} Bullet Factory
        </h3>

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
                        <td>Bullet Cost</td>
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
                            <button type="submit" class="btn btn-block" style="margin: 0px;">Buy</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <small>
                {>propertyOwnership}
            </small>
        </form>
        ';
        
    }

?>