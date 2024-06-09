<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;
use App\features\Spider;

class SpiderTest extends TestCase {
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
    public function testSpiderSlidesExactlyThreeTimes() {
        // Arrange

        // Act

        // Assert
    }

    public function testSlideEqualsQueenBeeMove() {
        // Arrange

        // Act
        
        // Assert
    }

    public function testSpiderCannotMoveToSameSpot() {
        // Arrange

        // Act

        // Assert
    }

    public function testSpiderCanOnlyMoveOverAndToEmptySpots() {
        // Arrange

        // Act

        // Assert
    }

    public function testSpiderCannotBacktrackDuringSlide() {
        // Arrange

        // Act

        // Assert
    }
}