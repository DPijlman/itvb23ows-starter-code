<?php
namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Database\Database;

class PassHandler
{
    public static function pass()
    {
        SessionManager::startSession();

        $player = 1 - SessionManager::get('player');
        SessionManager::set('player', $player);
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_from, move_to) VALUES (?, ?, ?, ?)');
        if ($stmt === false) {
            die('Prepare statement error: ' . $db->error);
        }

        $game_id = SessionManager::get('game_id');
        $type = 'pass';
        $move_from = null;
        $move_to = null;

        $stmt->bind_param('isss', $game_id, $type, $move_from, $move_to);
        
        if ($stmt->execute() === false) {
            die('Execute statement error: ' . $stmt->error);
        }

        header('Location: index.php');
        exit();
    }
}
