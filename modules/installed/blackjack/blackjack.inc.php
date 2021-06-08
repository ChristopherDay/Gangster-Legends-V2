<?php

class Deck {
    public $cards = array();

    private $property;

    public function __construct($exclude = array()) {
        $deck = array();

        $suits = array("C" => "clubs", "D" => "diamonds", "S" => "spades", "H" => "hearts");
        $cards = array(
            "ace" => "A", 
            "two" => 2, 
            "three" => 3, 
            "four" => 4, 
            "five" => 5, 
            "six" => 6, 
            "seven" => 7, 
            "eight" => 8, 
            "nine" => 9, 
            "ten" => 10, 
            "jack" => "J", 
            "queen" => "Q", 
            "king" => "K"
        );

        foreach ($suits as $suitID => $suit) {
            foreach ($cards as $cardID => $card) {
                $id = $suitID.$card;
                if (!in_array($id, $exclude)) $deck[] = array(
                    "desc" => $cardID . " of " . $suit,
                    "suit" => $suit,
                    "card" => $card, 
                    "id" => $id
                );
            }
        }

        $this->cards = $deck;

        $this->shuffle();

        return $this;

    }

    public function shuffle() {
        shuffle($this->cards);
        return $this;
    }

    public function draw() {
        return array_shift($this->cards);
    }

}

class blackjack extends module {

    public $maxBet = 10000;

    public $allowedMethods = array(
        "bet" => array("type" => "REQUEST")
    );

    public function scoreHand($hand, $actualScore = false) {
        $aces = 0;
        $score = 0;
        foreach ($hand as $card) {
            switch ($card["card"]) {
                case "J":
                case "Q":
                case "K":
                    $score += 10;
                break;
                case "A":
                    $score += 11;
                    $aces++;
                break;
                default: 
                    $score += $card["card"];
                break;
            }
        }

        /* change the aces to 1 if the score is over 21 */ 
        if ($score > 21) {
            $i = 0;
            while ($i < $aces) {
                $score -= 10;
                if ($score < 21) break; 
                $i++;
            }
        }

        /* return 0 if the hand is bust */
        if ($score > 21 && !$actualScore) {
            $score = 0;
        }

        /* check to see if the hand is blackjack */
        if ($score == 21 && count($hand) == 2 && !$actualScore) {
            $score = 22;
        }

        return $score;
    }



    public function constructModule() {

        $this->property = new Property($this->user, "blackjack");

        if (isset($_SESSION["BJ_GAME"])) {
            $this->method_play();
        } else {
            $this->method_bet();
        }

    }

    public function method_own() {
        $this->property = new Property($this->user, "blackjack");

        $owner = $this->property->getOwnership();

        if ($owner["user"]) {

            $user = $this->page->buildElement("userName", $owner);

            return $this->html .= $this->page->buildElement("error", array(
                "text" => "This property is owned by " . $user
            ));
        }

        if ($this->user->info->US_money < 1000000) {
            return $this->html .= $this->page->buildElement("error", array(
                "text" => "You need $1,000,000 to buy of this property."
            ));
        }

        $update = $this->db->prepare("
            UPDATE userStats SET US_money = US_money - 1000000 WHERE US_id = :id
        ");
        $update->bindParam(":id", $this->user->id);
        $update->execute();

        $this->property->transfer($this->user->id);

        return $this->html .= $this->page->buildElement("success", array(
            "text" => "You paid $1,000,000 to buy this property."
        ));

    }

    public function method_hit() {
        if (!isset($_SESSION["BJ_GAME"])) return;
        $game = $_SESSION["BJ_GAME"];

        $inUse = array();

        foreach ($game["user"] as $cards) {
            $inUse[] = $cards["id"];
        }

        foreach ($game["dealer"] as $cards) {
            $inUse[] = $cards["id"];
        }

        $deck = new Deck($inUse);

        $card = $deck->draw();

        $_SESSION["BJ_GAME"]["user"][] = $card;

        $this->html .= $this->page->buildElement("info",  array(
            "text" => "The dealer draws you a " . $card["desc"]
        ));

    }

    public function method_stand() {
        if (!isset($_SESSION["BJ_GAME"])) return;
        $game = $_SESSION["BJ_GAME"];

        $inUse = array();

        foreach ($game["user"] as $cards) {
            $inUse[] = $cards["id"];
        }

        foreach ($game["dealer"] as $cards) {
            $inUse[] = $cards["id"];
        }

        $deck = new Deck($inUse);

        $game["dealer"][0]["hide"] = false;

        $user = $this->scoreHand($game["user"]);
        $dealer = $this->scoreHand($game["dealer"]);

        while ($user > $dealer || $dealer < 17) {
            $game["dealer"][] = $deck->draw();
            $dealer = $this->scoreHand($game["dealer"]);
            if ($dealer == 0 || $dealer > 17) {
                break;
            }
        }

        if ($user == $dealer) {
            $this->html .= $this->page->buildElement("info", array(
                "text" => "You drew, " . $this->money($game["bet"]) . " was returned"
            ));    
            $winnings = $game["bet"];
            $this->userWins($winnings);

            $game["gameOver"] = true;
        } else if ($user > $dealer) {
            $this->html .= $this->page->buildElement("success", array(
                "text" => "The dealer went bust, you won " . $this->money($game["bet"])
            ));
            $winnings = ($game["bet"] * 2);
            $this->userWins($winnings);
            $game["gameOver"] = true;
        } else if ($user < $dealer) {
            $this->html .= $this->page->buildElement("error", array(
                "text" => "The dealer won, you lost your bet of " . $this->money($game["bet"])
            ));

            $game["gameOver"] = true;
        }

        $_SESSION["BJ_GAME"] = $game;
    }

    public function method_play() {
        $game = $_SESSION["BJ_GAME"];

        $score = $this->scoreHand($game["user"]);
        $actualScore = $this->scoreHand($game["user"], true);
        $game["score"] = $actualScore;
        $game["formatedBet"] = number_format($game["bet"]);

        if ($score == 0) {
            $game["gameOver"] = true;
            $this->html .= $this->page->buildElement("error", array(
                "text" => "You went bust and lost " . $this->money($game["bet"])
            ));
        } else if ($score == 22) {
            $game["gameOver"] = true;
            $this->html .= $this->page->buildElement("success", array(
                "text" => "You got blackjack and won " . $this->money($game["bet"] * 1.5)
            ));
            $winnings = ($game["bet"] * 2.5);
            $this->userWins($winnings);
        }

        if (isset($game["gameOver"])) {
            $actualDealerScore = $this->scoreHand($game["dealer"], true);
            $game["dealerScore"] = $actualDealerScore;
            $game["dealer"][0]["hide"] = false;
            unset($_SESSION["BJ_GAME"]); 
        } 

        $this->html .= $this->page->buildElement("blackjackTable", $game);

    }
    
    public function method_bet() {

        $this->property = new Property($this->user, "blackjack");

        $options = $this->property->getOwnership();
        if ($options["cost"]) $this->maxBet = $options["cost"];

        if (isset($this->methodData->bet)) {
            $bet = (int) $this->methodData->bet;

            if ($bet > $this->user->info->US_money) {
                $this->html .= $this->page->buildElement("error", array(
                    "text" => "You dont have enough cash to cover this bet"
                ));
            } else if ($bet < 100) {
                $this->html .= $this->page->buildElement("error", array(
                    "text" => "You must bet atleast $100"
                ));
            } else if ($bet > $this->maxBet) {
                $this->html .= $this->page->buildElement("error", array(
                    "text" => "The max bet is " . $this->money($this->maxBet)
                ));
            } else {

                $owner = $this->property->getOwnership();
                $this->property->updateProfit($bet);

                if ($owner["user"]) {
                    $ownerID = $owner["user"]["id"];
                } else {
                    $ownerID = 0;
                }

                $user = $this->db->prepare("
                    UPDATE userStats SET 
                        US_money = US_money + :bet
                    WHERE 
                        US_id = :owner;
                ");
                $user->bindParam(":bet", $bet);
                $user->bindParam(":owner", $ownerID);
                $user->execute();

                $this->user->set("US_money", $this->user->info->US_money - $bet);

                $deck = new Deck();
                $_SESSION["BJ_GAME"] = array(
                    "bet" => $bet, 
                    "dearler" => array(),
                    "user" => array()
                );

                $_SESSION["BJ_GAME"]["user"][] = $deck->draw();
                $_SESSION["BJ_GAME"]["dealer"][] = $deck->draw();
                $_SESSION["BJ_GAME"]["user"][] = $deck->draw();
                $_SESSION["BJ_GAME"]["dealer"][] = $deck->draw();
                $_SESSION["BJ_GAME"]["dealer"][0]["hide"] = true;

                return $this->method_play();
            }

        }

        $options["maxBet"] = $this->money($this->maxBet);
        $this->html .= $this->page->buildElement("placeBet", $options);
    }

    public function userWins($cash) {

        $this->property = new Property($this->user, "blackjack");
        
        $owner = $this->property->getOwnership();

        $actionHook = new hook("userAction");

        $action = array(
            "user" => $this->user->id, 
            "module" => "casinoPayout", 
            "id" => 1, 
            "success" => true, 
            "reward" => $cash / 2, 
            "gt" => true
        );
        $actionHook->run($action);

        if ($owner["user"]) {

            $owner = new User($owner["user"]["id"]);

            if ($cash > $owner->info->US_money) {
                $this->property->transfer($this->user->id);
                $this->html .= $this->page->buildElement("warning", array(
                    "text" => "The owner did not have enough cash to pay the bet, you took ownership of the casino."
                ));

                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "casinoBust", 
                    "id" => 1, 
                    "success" => true, 
                    "reward" => 0
                );
                $actionHook->run($action);

            } else {
                $user = $this->db->prepare("
                    UPDATE userStats SET 
                        US_money = US_money - :bet
                    WHERE 
                        US_id = :owner
                ");

                $user->bindParam(":bet", $cash);
                $user->bindParam(":owner", $owner->id);
                $user->execute();
                $this->user->set("US_money", $this->user->info->US_money + $cash);
            }

        } else {

            $user = $this->db->prepare("
                UPDATE userStats SET 
                    US_money = US_money + :bet
                WHERE 
                    US_id = :id
            ");

            $user->bindParam(":bet", $cash);
            $user->bindParam(":id", $this->user->info->US_id);
            $user->execute();

        }

        $this->property->updateProfit(-$cash);

    }

}

