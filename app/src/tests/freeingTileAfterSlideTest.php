<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;
use App\classes\Util;

class FreeingTileAfterSlideTest extends TestCase {
    protected $hiveGame;

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
            "2,0" => [["0", "S"]],
        ];
    }

    public function testSlideLeavesPreviousTileUnplayable() {
        // Arrange
        $_SESSION['board'] = [
            'A1' => [['0', 'S']],
            'B1' => [['1', 'Q']],
            'C1' => [['0', 'B']],
            'D1' => [['1', 'B']],
            'E1' => [['0', 'A']]
        ];
        $_SESSION['player'] = 0;

        $this->hiveGame->move('A1', 'D1');

        // Act
        $this->hiveGame->play('B', 'A1');

        // Assert
        $this->assertTrue(isset($_SESSION['board']['A1']));
        $this->assertCount(1, $_SESSION['board']['A1']);
        $this->assertEquals(['0', 'B'], $_SESSION['board']['A1'][0]);
    }
}