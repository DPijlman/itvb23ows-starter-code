<?php

namespace App\GameState;

use App\Database\Database;

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
            0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
            1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
        ];
        $_SESSION['player'] = 0;

        $db = Database::getInstance()->getConnection();
        $db->prepare('INSERT INTO games VALUES ()')->execute();
        $_SESSION['game_id'] = $db->insert_id;
    }
}
