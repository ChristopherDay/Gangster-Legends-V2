<?php

    class bulletsTemplate extends template {
        
        public $bulletPage = '<p>Welcome to the {location} bullet factory, currently it has {stock} bullets in stock at the cost of ${cost} each!</p>
        <p>
            You can buy up to {maxBuy} at once!
        </p>
        <p>
            <form action="?page=bullets&action=buy" method="post">
                <input type="text" class="form-control" autocomplete="off" name="bullets" style="width:calc(97% - 100px); display:inline-block;" placeholder="Qty. to buy" /><br /><br />
                <button type="submit" class="btn btn-link" style="display:inline-block; width:100px;">Buy</button>
            </form>
        </p>
        ';
        
    }

?>