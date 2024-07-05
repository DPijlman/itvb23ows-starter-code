<?php
namespace HiveGame\GameState;

use HiveGame\Database\Database;

class GameState {
    public static function getState() {
        return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
    }

    public static function setState($state) {
        list($_SESSION['hand'], $_SESSION['board'], $_SESSION['player']) = unserialize($state);
    }

    public static function restartGame() {
        $_SESSION['board'] = [];
        $_SESSION['hand'] = [
            0 => ["queen" => 1, "ant" => 3, "spider" => 2, "beetle" => 2, "grasshopper" => 3],
            1 => ["queen" => 1, "ant" => 3, "spider" => 2, "beetle" => 2, "grasshopper" => 3]
        ];
        $_SESSION['player'] = 0;

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('INSERT INTO games () VALUES ()');
        $stmt->execute();
        $_SESSION['game_id'] = $db->insert_id;
    }
}
