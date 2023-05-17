<?php   
// hier word en 2 fleets aangemaakt met 4 random ships
function createFleet(int $allShips): array 
{
    $fleet = [];
    $specialNumber = NULL;
    $numberOfShips = $allShips - 1;

    for ($i = 0; $i < 2; $i++){
        $number = rand(0, $numberOfShips);
        do {
            $number = rand(0, 3);
        } while ($number == $specialNumber);
        $specialNumber = $number;
        $fleet[] += $number;
    }
    return $fleet;
}
function checkShips($fleet1, $fleet2){
    // Hier word gecheckt of de fleets niet 2 bombers/carriers bevatten. De game word beeindigd
    // als deze lineup bestaat. Anders word er geen damage gedaan
    if ((get_class($fleet1[0]) == "BomberJet" || get_class($fleet1[0]) == "CarrierJet") &&
    (get_class($fleet1[1]) == "BomberJet" || get_class($fleet1[1]) == "CarrierJet") &&
    (get_class($fleet2[0]) == "BomberJet" || get_class($fleet2[0]) == "CarrierJet") &&
    (get_class($fleet2[1]) == "BomberJet" || get_class($fleet2[1]) == "CarrierJet")
    ){
        stopScript();
    }
    else;
}
function stopScript() {
    // Het beeindigen van de game als de lineup niet goed is
    exit("Game has been cancelled, as the fleet lineup contained two or more bomberjets and carrierjets.");
  }
?>