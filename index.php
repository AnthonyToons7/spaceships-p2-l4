<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'src/Spaceship.php';
include_once 'src/process.php';

// Iedere ship heeft zijn eigen gimmick. De FighterJets kunnen schieten, de CarrierJet is een tanky ship met 
// een barrier die hits absorbeert en meer damage neemt als de barrier gebroken is, en de bomberjet kan 
// snel rondvliegen, kan niet schieten en heeft weinig hp, maar explodeert als die dood is.

// Dmg word willekeurig gecalculeerd, het is een willekeurig getal tussen de 1 en 5, die daarna word vermenigvuldigd
// met 2. Voor speciale instanties, bv. de BomberJet, word de dmg vast gezet.

// Ship 1 = yellow
// Ship 2 = blue
// Ship 3 = green
// Ship 4 = purple
// Barrier = light blue
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Among us??? SUS?!?!?!??!?!???!?!??!?!?!?! OMG (REAL)!!!!! NO CLICKBAIT!!!</title>
    <link rel="stylesheet" href="src/styles/style.css">
</head>
<body>
    <p>
        <?php
        // Maak ships aan en geef de values mee
        $ship1 = new FighterJet();
        $ship2 = new FighterJet(60, 35, 3, 3);
        $ship3 = new CarrierJet(75, 40, 2, 2, 5);
        $ship4 = new BomberJet(71, 10, 4, 2);
        // zorg ervoor dat de values bestaan
        $ship1->callAmmo();
        $ship2->callAmmo(70);
        $ship3->callBarrier();
        $ship4->callExplode();
        $allShips = [$ship1, $ship2, $ship3, $ship4];
        $firstFleet = createFleet(count($allShips));
        $secondFleet = createFleet(count($allShips));
        // maak een paar fleets aan door de ships in een array te zetten
        $fleet1 = [$allShips[$firstFleet[0]], $allShips[$firstFleet[1]]];
        $fleet2 = [$allShips[$secondFleet[0]], $allShips[$secondFleet[1]]];
        echo checkShips($fleet1, $fleet2);
        getAllShipData ($fleet1, $fleet2);
        echo "Game auto cancels if the game is not finished within 20 turns";
        ?>
    </p>
    <br>
    <p class="battle-phase">        
        <?php
        // roep en maak een battle aan
            echo createBattle($fleet1, $fleet2);
        ?>
    </p>
    <?php 
        echo "D = Damage<br>";
        echo "XD = Death damage<br>";
        echo "C = Cancelled<br>";
    ?>
</body>
</html>