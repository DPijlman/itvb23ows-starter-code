<?php
namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;
use HiveGame\Database\Database;

class MoveHandler {
    public static function move($from, $to) {
        SessionManager::startSession();

        $board = SessionManager::get('board');
        $player = SessionManager::get('player');
        $game_id = SessionManager::get('game_id');

        if ($game_id === null) {
            SessionManager::set('error', 'Game ID is not set.');
            header('Location: index.php');
            exit;
        }

        $piece = end($board[$from]);
        if ($piece[0] != $player) {
            SessionManager::set('error', 'Invalid move: Not your piece.');
            header('Location: index.php');
            exit;
        }

        if ($piece[1] === 'grasshopper') {
            if (!Util::isValidGrasshopperMove($board, $from, $to)) {
                SessionManager::set('error', 'Invalid move: Grasshopper move is not valid.');
                header('Location: index.php');
                exit;
            }

            // Perform the Grasshopper move
            array_pop($board[$from]);
            if (empty($board[$from])) {
                unset($board[$from]);
            }
            $board[$to][] = $piece;
        } else {
            $new_board = Util::slide($board, $from, $to);
            if ($new_board === false) {
                SessionManager::set('error', 'Invalid move: Slide is not possible.');
                header('Location: index.php');
                exit;
            }

            $board = $new_board;
        }

        SessionManager::set('board', $board);
        SessionManager::set('player', 1 - $player);

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_from, move_to) VALUES (?, ?, ?, ?)');
        $move_type = 'move';
        $stmt->bind_param('isss', $game_id, $move_type, $from, $to);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}
