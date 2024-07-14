<?php
namespace HiveGame\Tests;

// Added because tests would automatically fail for some reason if this was not in it
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HiveGame\MoveHandler\PlayHandler;
use HiveGame\Util\SessionManager;

class QueenPlayedTest extends TestCase {
    protected function setUp(): void {
        SessionManager::destroySession();
        SessionManager::set('board', []);
        SessionManager::set('hand', [
            0 => ["Q" => 1, "A" => 3, "S" => 2, "B" => 2, "G" => 3],
            1 => ["Q" => 1, "A" => 3, "S" => 2, "B" => 2, "G" => 3]
        ]);
        SessionManager::set('player', 0);
        SessionManager::set('turns', [0, 0]);
    }

    public function testQueenNotPlayedBeforeTurn4() {
        // Arrange
        $piece = 'A';
        $positions = ['0,0', '1,0', '0,1', '1,1'];

        // Act
        for ($i = 0; $i < 4; $i++) {
            $result = PlayHandler::play($piece, $positions[$i]);
            SessionManager::set('player', 0);
        }

        // Assert
        $this->assertFalse($result);
    }

    public function testQueenPlayedBeforeTurn4() {
        // Arrange
        $piece = 'Q';
        $position = '0,0';

        // Act
        $result = PlayHandler::play($piece, $position);

        // Assert
        $this->assertTrue($result);
        $this->assertNull(SessionManager::get('error'));
    }
}
