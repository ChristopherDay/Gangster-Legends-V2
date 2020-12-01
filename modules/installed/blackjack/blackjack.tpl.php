<?php

    class blackjackTemplate extends template {

        public $scoreTest = '
            <h4>{score}</h4>
            {>cards}
        ';
        public $blackjackTable = '

            
            <div class="panel panel-default">
                <div class="panel-heading">Blackjack</div>
                <div class="panel-body">
                    <div class="dealers-hand">
                        <h4>Dealers Hand {#if dealerScore}<span class="label label-success">{dealerScore}{/if}</span></h4>
                        {#each dealer}
                            {#if hide}
                                <div class="card backs red"></div>
                            {/if}
                            {#unless hide}
                                <div class="card {suit} card-{card}"></div>
                            {/unless}
                        {/each}
                    </div>            

                    <h4>Your Hand <span class="label label-success">{score}</span></h4>
                    {#each user}
                        {#if hide}
                            <div class="card backs red"></div>
                        {/if}
                        {#unless hide}
                            <div class="card {suit} card-{card}"></div>
                        {/unless}
                    {/each}

                    <hr />

                    <strong>Stake:</strong> ${formatedBet}<br />

                    {#unless gameOver}
                        <a href="?page=blackjack&action=hit" class="btn btn-danger">Hit!</a>
                        <a href="?page=blackjack&action=stand" class="btn btn-success">Stand</a>
                    {/unless}
                    {#if gameOver}
                        <a href="?page=blackjack" class="btn btn-success">Play Again</a>
                        <a href="?page=blackjack&bet={bet}" class="btn btn-danger">Same Bet</a>
                    {/if}
                </div>
            </div>
        ';

        public $placeBet = "
            <div class='panel panel-default'>
                <div class='panel-heading'>Place Bet </div>
                <div class='panel-body'>
                    {#if closed}
                        This property is currently closed!
                    {/if}

                    {#unless closed}
                        <h4> Place Bet </h4>
                        <form method='post' action='#'>
                            <input type='number' name='bet' class='form-control form-control-inline' placeholder='0' /> 
                            <button class='btn btn-default'>Bet</button>
                        </form>
                        <hr />
                        
                        <small> Min: $100 Max: {maxBet}</small> <br />
                        <small>{>propertyOwnership}</small>
                    {/unless}
                </div>
            </div>
        ";

        public $cards = '
            {#each cards}
                {#if hide}
                    <div class="card backs red"></div>
                {/if}
                {#unless hide}
                    <div class="card {suit} card-{card}"></div>
                {/unless}
            {/each}
        ';

    }

