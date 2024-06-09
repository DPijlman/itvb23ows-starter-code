<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;
use App\classes\Util;
use App\features\Spider;

class SpiderTest extends TestCase {
    private $game;

    protected function setUp(): void {
        $this->hiveGame = new HiveGame();

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
        $_SESSION['board'] = [
            'A1' => [['0', 'S']],
            'B1' => [['1', 'Q']],
            'C1' => [['0', 'B']],
            'D1' => [['1', 'B']],
            'E1' => [['0', 'A']]
        ];
        $_SESSION['player'] = 0;

        // Act
        $result = $this->game->move('A1', 'D1');

        // Assert
        $this->assertTrue($result);
        $this->assertFalse(isset($_SESSION['board']['A1']));
        $this->assertTrue(isset($_SESSION['board']['D1']));
        $this->assertCount(1, $_SESSION['board']['D1']);
        $this->assertEquals(['0', 'S'], $_SESSION['board']['D1'][0]);
        $this->assertEquals(1, $_SESSION['player']);
    }

    public function testSpiderCannotMoveToSameSpot() {
        // Arrange
        $_SESSION['board'] = [
            'A1' => [['0', 'S']],
            'B1' => [['1', 'Q']],
            'C1' => [['0', 'B']],
            'D1' => [['1', 'B']],
            'E1' => [['0', 'A']]
        ];
        $_SESSION['player'] = 0;

        // Act
        $result = $this->game->move('A1', 'A1');

        // Assert
        $this->assertFalse($result);
        $this->assertTrue(isset($_SESSION['board']['A1']));
        $this->assertCount(1, $_SESSION['board']['A1']);
        $this->assertEquals(['0', 'S'], $_SESSION['board']['A1'][0]);
        $this->assertEquals(0, $_SESSION['player']);
    }

    public function testSlideEqualsQueenBeeMove() {
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