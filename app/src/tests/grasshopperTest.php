<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;
use App\features\Grasshopper;

class GrasshopperTest extends TestCase {
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

        $_SESSION['board'] = [
            "0,0" => [["0", "Q"]],
            "1,0" => [["1", "Q"]],
            "2,0" => [["0", "B"]],
            "3,0" => [["1", "B"]],
            "4,0" => [["0", "G"]],
        ];
    }

    public function testGrasshopperJumpTile() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "G"]];

        // Act
        $result = $this->game->move("1,1", "4,0");

        // Assert
        $this->assertTrue($result, "Grasshopper should be able to jump to an empty tile after obstacles");
        $this->assertArrayHasKey("4,0", $_SESSION['board']);
        $this->assertEquals([["0", "G"]], $_SESSION['board']["4,0"]);
    }

    public function testGrasshopperCannotJumpToOccupiedSpot() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "G"]];
        $_SESSION['board']["3,0"] = [["1", "Q"]];

        // Act
        $result = $this->game->move("1,1", "3,0");

        // Assert
        $this->assertFalse($result, "Grasshopper cannot jump to an occupied tile");
        $this->assertEquals([["0", "G"]], $_SESSION['board']["1,1"]);
    }

    public function testGrasshopperCannotJumpEmptySpots() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "G"]];
        unset($_SESSION['board']["2,0"]);

        // Act
        $result = $this->game->move("1,1", "4,0");

        // Assert
        $this->assertFalse($result, "Grasshopper cannot jump over empty tiles");
        $this->assertEquals([["0", "G"]], $_SESSION['board']["1,1"]);
    }

    public function testGrasshopperCannotJumpOnSameSpot() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "G"]];

        // Act
        $result = $this->game->move("1,1", "1,1");

        // Assert
        $this->assertFalse($result, "Grasshopper cannot move to the same spot");
        $this->assertEquals([["0", "G"]], $_SESSION['board']["1,1"]);
    }
}
