<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;
use App\features\SoldierAnt;

class SoldierAntTest extends TestCase {
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
            "4,0" => [["0", "A"]],
        ];
    }

    public function testSoldierAntMove() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "A"]];

        // Act
        $result = $this->game->move("1,1", "4,0");

        // Assert
        $this->assertTrue($result, "Soldier Ant should be able to slide to an empty tile");
        $this->assertArrayHasKey("4,0", $_SESSION['board']);
        $this->assertEquals([["0", "A"]], $_SESSION['board']["4,0"]);
    }

    public function testSoldierAntCannotMoveToOccupiedSpot() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "A"]];
        $_SESSION['board']["3,0"] = [["1", "Q"]];

        // Act
        $result = $this->game->move("1,1", "3,0");

        // Assert
        $this->assertFalse($result, "Soldier Ant cannot move to an occupied tile");
        $this->assertEquals([["0", "A"]], $_SESSION['board']["1,1"]);
    }

    public function testSoldierAntCannotMoveToSameSpot() {
        // Arrange
        $_SESSION['board']["1,1"] = [["0", "A"]];

        // Act
        $result = $this->game->move("1,1", "1,1");

        // Assert
        $this->assertFalse($result, "Soldier Ant cannot move to its current position");
        $this->assertEquals([["0", "A"]], $_SESSION['board']["1,1"]);
    }
}
