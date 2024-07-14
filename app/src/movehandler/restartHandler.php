<?php
namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Database\Database;

class RestartHandler {
    public static function restartGame() {
        SessionManager::startSession();

        $_SESSION['board'] = [];
        $_SESSION['hand'] = [
            0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
            1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
        ];
        $_SESSION['player'] = 0;
        $_SESSION['turns'] = [0, 0];

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('INSERT INTO games VALUES ()');
        $stmt->execute();
        $_SESSION['game_id'] = $db->insert_id;
    }
}
