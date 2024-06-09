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
            "4,0" => [["0", "G"]],
        ];
    }

    public function testSoldierAntSlide() {
        // Arrange

        // Act

        // Assert
    }

    public function testSoldierAntSlideReplicatesQueenMovement() {
        // Arrange

        // Act

        // Assert
    }

    public function testSoldierAntCannotMoveToCurrentSpot() {
        // Arrange

        // Act

        // Assert
    }

    public function testSoldierAntMovementOnlyAcrossAndToEmptySpots() {
        // Arrange

        // Act

        // Assert
    }
}
