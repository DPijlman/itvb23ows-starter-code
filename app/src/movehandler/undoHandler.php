<?php

namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\GameState\GameState;
use HiveGame\Database\Database;

class UndoHandler
{
    public static function undo()
    {
        SessionManager::startSession();

        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM moves WHERE game_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->bind_param('i', SessionManager::get('game_id'));
        $stmt->execute();
        $result = $stmt->get_result();
        $lastMove = $result->fetch_assoc();

        if ($lastMove) {
            $stmt = $db->prepare('DELETE FROM moves WHERE id = ?');
            $stmt->bind_param('i', $lastMove['id']);
            $stmt->execute();

            GameState::setState($lastMove['previous_state']);
        }

        header('Location: index.php');
        exit;
    }
}
