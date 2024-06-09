<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;
use App\features\Grasshopper;

class GrasshopperMoveTest extends TestCase {
    private $game;

    protected function setUp(): void {
        $this->game = new HiveGame();

        $_SESSION = [];
        $_SESSION['board'] = [];
        $_SESSION['hand'] = [
            0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
            1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
        ];
        $_SESSION['player'] = 0;
    }

    public function testGrasshopperJumpTile() {
        $condition = true;
        $this->assertFalse(
         
            $condition, 
            "condition is true or false"
        ); 
    }
}