<?php

namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;
use HiveGame\Database\Database;

class MoveHandler
{
    public static function move($from, $to)
    {
        SessionManager::startSession();

        $board = SessionManager::get('board');
        $player = SessionManager::get('player');

        if (Util::len($board[$from]) > 0 && $board[$from][Util::len($board[$from]) - 1][0] != $player) {
            SessionManager::set('error', 'Invalid move: Not your piece.');
            header('Location: index.php');
            exit;
        }

        $new_board = Util::slide($board, $from, $to);
        if ($new_board === false) {
            SessionManager::set('error', 'Invalid move: Slide is not possible.');
            header('Location: index.php');
            exit;
        }

        SessionManager::set('board', $new_board);
        SessionManager::set('player', 1 - $player);

        $db = Database::getConnection();
        $stmt = $db->prepare('INSERT INTO moves (game_id, player, piece, destination) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', SessionManager::get('game_id'), $player, $from, $to);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}
