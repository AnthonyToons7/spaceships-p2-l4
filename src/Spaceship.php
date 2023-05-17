<?php
// Hier maak ik een battle aan, die zichzelf loopt en continu willekeurige acties uit voert
function createBattle($fleet1, $fleet2)
{
    $i=0;
    echo "Battle time! <br><br>";
    $allFleets = [$fleet1, $fleet2];
    // Een do while loop vond ik iets fijner dan een for loop. Een while gaat door tot de conditie niet meer vervuld is
    // Een do while heeft een soort if statement er aan hangen, wat iets handiger is dan een for loop.
    do {
        // Maak hier wat random getallen aan voor random ships en acties
        $randomOption = rand(0,5);
        $randomisedShip = rand(0,1);
        $randomisedShip2 = rand(0,1);
        if ($i % 2 == 0){
            echo "fleet1";
            // Ik kon if else gebruiken, maar ik wilde liever een switch case gebruiken,
            // omdat ik eerst moveBack() ging gebruiken, wat ik uiteindelijk niet heb gedaan, omdat de game te lang door ging
            switch ($randomOption){
                case 0:
                    $fleet1[rand(0,1)]->move();
                    echo "<p class='battle-phase'>" . get_class($fleet1[$randomisedShip]) . "--> move</p>";
                    break;
                case $randomOption >= 1:
                    // als het hoger is dan 1, dan het schieten, treffen, en de battle status ophalen.
                    $dmg = $fleet1[$randomisedShip]->shoot();
                    $fleet2[$randomisedShip2]->hit($dmg);
                    echo "<p class='battle-phase'>" . get_class($fleet1[$randomisedShip]) . " --" . $fleet1[$randomisedShip]->getBattleStatus() . "--> " . get_class($fleet2[$randomisedShip2]) . "</p>";
                    break;
           };
        } else{
            echo "fleet2";
            switch ($randomOption){
                case 0:
                    $fleet2[rand(0,1)]->move();
                    echo "<p class='battle-phase'>" . get_class($fleet2[$randomisedShip]) . "--> move </p>";
                    break;
                case $randomOption >= 1:
                    $dmg = $fleet2[$randomisedShip]->shoot();
                    $fleet1[$randomisedShip2]->hit($dmg);
                    echo "<p class='battle-phase'>" . get_class($fleet2[$randomisedShip]) . " --" . $fleet2[$randomisedShip]->getBattleStatus() . "--> " . get_class($fleet1[$randomisedShip2]) . "</p>";
                    break;
           };
        }
        // Als alle 4 ships een turn hebben gehad, dan word de data getoond
        if ($i % 4 == 0){
            getAllshipData($fleet1,$fleet2);
        }
        $i++;
    } while (
        // Code moet stoppen als een van de fleets plat ligt, of als de turn counter hoger is dan 20
        // Ik heb ervoor gekozen om de game turn based te maken, en dat alles in laad, omdat ik het iets makkelijker vind
        // Ik ben persoonlijk een fan van turn-based games en RPG's, dus dat past hier wel bij.
        // Ik kon er een session aan hangen, waarbij de staat van de game update als je de pagina ververst,
        // maar het zou wat duidelijker zijn als ik een log had van de hele game.
        (($fleet1[0]->getHitpointsCheck()>0 && $fleet1[1]->getHitpointsCheck()>0) ||
        ($fleet2[0]->getHitpointsCheck()>0 && $fleet2[1]->getHitpointsCheck()>0)) || $i < 20
    );
    if (
        // Hier word gecheckt welke fleet heeft gewonnen
        ($fleet1[0]->getHitpointsCheck()<=0 &&
        $fleet1[1]->getHitpointsCheck()<=0) ||
        ($fleet2[0]->getHitpointsCheck()<=0 &&
        $fleet2[1]->getHitpointsCheck()<=0)
    ){
        echo ranking($fleet1, $fleet2);
        getAllshipData($fleet1,$fleet2);
        exit("end of game");
    }
};
function getAllshipData($fleet1,$fleet2){
    // Haal alle informatie van de ships op en laat die zien
    echo "<br><p>";
    echo "<br><br><span class=\"fleet1\">";
    foreach ($fleet1[0]->getData() as $key => $value){
        echo $key . $value . "<br>";
    }
    echo "<br></span><br><br><span class=\"fleet1\">";
    foreach ($fleet1[1]->getData() as $key => $value){
        echo $key . $value . "<br>";
    }
    echo "</span><br><span class=\"fleet2\">";
    foreach ($fleet2[0]->getData() as $key => $value){
        echo $key . $value . "<br>";
    }
    echo "<br></span><br><span class=\"fleet2\">";
    foreach ($fleet2[1]->getData() as $key => $value){
        echo $key . $value . "<br>";
    }
    echo "</span><br>";
    echo "</p>";
}
function ranking($fleet1, $fleet2) {
    // Leaderboard
    // Sorteer alle ships op de HP die ze over hebben
    $allShips = array_merge($fleet1, $fleet2);
    // maak gebruik van usort om de ships te sorten op HP
    usort($allShips, function($a, $b) {
        return $b->getHitpointsCheck() - $a->getHitpointsCheck();
    });
    echo "<br>LEADERBOARD (determined by the ship with the most HP)<br>";
    foreach ($allShips as $key => $ship) {
        echo "<span class='leaderboard'>" . $key + 1 . ": " . $ship->getHitpointsCheck() . "</span><br>";
    }
}
class ship 
{
    protected bool $isAlive;
    private int $fuel;
    private int $moveAmt;
    protected int $hitPoints;
    public function __construct (
        $fuel = 100,
        $hitPoints = 50,
        $moveAmt = 1,
        $location = 0
    ) {
        $this->fuel = $fuel;
        $this->hitPoints = $hitPoints;
        $this->moveAmt = $moveAmt;
        $this->location = $location;
        $this->isAlive = true;
    }
    public function getBarrier(){
        return $this->barrier;
    }
    public function move()
    // Bewegen naar voren door de x-as aan te passen, zolang het ship nog in leven is
    {
        if ($this->isAlive == true){
            $fuelUsage = 23;
            if ($this->fuel - $fuelUsage > 0) {
                $this->fuel -= $fuelUsage;
                $this->location += $this->moveAmt;
            } else {
                $this->fuel = 0;
            }
        } else;
    }
    public function moveBack()
    // Bewegen naar achteren door de X-as aan te passen
    {
        $fuelUsage = 2;
        if ($this->fuel - $fuelUsage > 0) {
            $this->fuel -= $fuelUsage;
            $this->location -= $this->moveAmt;
        } else {
            $this->fuel = 0;
        }
    }
    public function shoot()
    // De damage die het schot doet is willekeurig, maar word altijd vermenigvuldigd met 5
    {
        if(get_class($this) == "FighterJet"){
            $shot = 5;
            $damage = rand(1, 5);
            if ($this->ammo - $shot >= 0) {
                $this->ammo -= $shot;
                return ($shot * $damage);
            } else {
                return 0;
            }
            // als het ship die shoot gebruikt een BomberJet is, dan verwijst hij naar explode
        } else if (get_class($this) == "BomberJet"){
            $this->explode();
        } else {
            return 0;
        }
    }        
    public function hit($damage)
    // Hier krijgt het ship dat aangevallen word de damage. Als het een Carrier jet is, dan word er een Check gedaan of 
    // er een barrier actief is
    {
        if(get_class($this) !== "CarrierJet") {
            if ($this->hitPoints - $damage > 0 && $this->isAlive != false) {
                $this->hitPoints -= $damage;
            }else if ($this->hitPoints - $damage <= 0) {
                $this->isAlive = false;
                $this->hitPoints -= $damage;
            }
        } else{
            $this->damageBarrier($damage);
        }
    }
    public function getData(){
        // Hier word alle data opgehaald voor getAllshipData();
        // Hij checkt ieder ship op welk type ship het is, en toont verschillende data voor ieder schip.
        if(get_class($this) == "FighterJet") {
            return array(
                get_class($this) . " HP: " => $this->hitPoints,
                get_class($this) . " ammo: " => $this->ammo,
                get_class($this) . " fuel: " => $this->fuel,
                get_class($this) . " location: " => $this->location
            );
        } else if (get_class($this) == "CarrierJet") {
            return array(
                get_class($this) . " HP: " => $this->hitPoints,
                get_class($this) . " fuel: " => $this->fuel,
                get_class($this) . " location: " => $this->location,
                get_class($this) . " barrier: " => $this->barrier
            );
        } else {
            return array(
                get_class($this) . " HP: " => $this->hitPoints,
                get_class($this) . " fuel: " => $this->fuel,
                get_class($this) . " location: " => $this->location
            );
        }
    }
    public function getBattleStatus(){
        // Dit is om de battle status te laten zien. 
        // Als een fighterjet een ander ship beschadigd, dan word het een D van Damage
        // Als een Bomberjet explodeert, dan word het een XD van death damage
        // Als het een carrier of een pre-exploded-bomberjet is, dan word de battle geanuleerd, en het resultaat is C van canceled.
         if(get_class($this) == "FighterJet") {
            return "D";
        } else if (get_class($this) == "CarrierJet") {
            return "C";
        } else if (get_class($this) == "BomberJet") {
            if ($this->hitPoints <= 0) {
                return "XD";
            } else {
                return "C";
            }
        }
    }
    public function getHitpointsCheck(){
        return $this->hitPoints;
    }
    public function getHitPoints(){
        // Haal HP op. Als het ship dood is, dan stuurt hij "DEAD" trug
        if ($this->hitPoints > 0) {
            return $this->hitPoints;
        } else {
            $this->isAlive = false;
            return "DEAD";
        }
    }
    public function getStatus(){
        // Zelfde check als HP, maar dan met de isAlive status
        if ($this->isAlive == false){
            return $this->isAlive;
        } else {
            return $this->isAlive = true;
        }
    }
}
class FighterJet extends ship
{
    protected int $ammo;
    public function callAmmo(
        $ammo=100
        ): int{
        return $this->ammo = $ammo;
    }
}
class CarrierJet extends ship
{
    protected int $barrier;
    protected bool $barrierUp;
    // maak de barrier
    public function callBarrier(
        $barrier = 5,
        $barrierUp = true
    ){
        $this->barrier = $barrier;
        $this->barrierUp = $barrierUp;
    }
    public function barrierStatus(){
        // Haal de status van de barrier op
        if ($this->barrier <= 0){
            $this->barrierUp = false;
        } else {
            $this->barrierUp = true;
        }
        return (boolval($this->barrierUp) ? 'true' : 'false');
    }
    public function damageBarrier($damage){
        // Het aanvallen van een barrier. Barriers absorberen alle damage
        // Als er geen barrier is, dan word het ship zelf beschadigd
        if($this->barrierUp === true && $this->barrier > 0){
            $this->barrier -= $damage;
        } else {
            if ($this->hitPoints - $damage > 0) {
                $this->hitPoints -= $damage;
            }else if ($this->hitPoints - $damage <= 0){
                $this->isAlive = false;
                $this->hitPoints -= $damage;
            }
        }
    }
}
class BomberJet extends ship
{
    protected int $explosiveDmg;
    protected int $exploded;
    public function callExplode(
        // Maak de explosie variabelen aan
        $explosiveDmg = 50,
        $exploded = false
    ) {
        $this->explosiveDmg = $explosiveDmg;
        $this->exploded = $exploded;
    }
    public function explode()
    // Vermenigvuldig de DMG gebaseerd op de locatie van het schip.
    {
        if ($this->location <= 0){
            $damage = 3;
        } else if ($this->location > 0){
            $damage = 2;
        }
        // check of het ship dood is. als het niet het geval is, dan doet het ship niks
        if (($this->isAlive = false || $this->hitPoints <= 0) && $this->exploded != true) {
            $this->exploded = true;
            $damage = $this->explosiveDmg * $damage;
            return $damage;
        } else {
            return 0;
        }
    }
}