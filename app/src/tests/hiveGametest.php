<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;

class HiveGameTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['board'] = [];
        $_SESSION['hand'] = [
            0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
            1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
        ];
        $_SESSION['player'] = 0;
        $_SESSION['game_id'] = 1;
    }

    public function testAvailableTiles()
    {
        // Arrange
        $hiveGame = new HiveGame();

        // Act
        $hiveGame->play('Q', '0,0');

        // Assert
        $this->assertEquals(0, $_SESSION['hand'][0]['Q'], "Expected Queen count in player 0's hand to be 0");
        $this->assertArrayNotHasKey('Q', $_SESSION['hand'][0], "Expected Queen to be removed from player 0's hand");
    }
}
