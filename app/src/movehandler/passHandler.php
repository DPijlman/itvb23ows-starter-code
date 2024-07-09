<?php
namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Database\Database;
use HiveGame\Util\Util;

class PassHandler
{
    public static function pass()
    {
        SessionManager::startSession();

        $board = SessionManager::get('board');
        $player = SessionManager::get('player');

        if (self::canMakeAnyMove($board, $player)) {
            SessionManager::set('error', 'You cannot pass your turn as you have valid moves available.');
            header('Location: index.php');
            exit();
        }

        $player = 1 - $player;
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

    private static function canMakeAnyMove($board, $player)
    {
        foreach ($board as $coords => $stack) {
            $piece = end($stack);
            if ($piece[0] == $player) {
                foreach (Util::$OFFSETS as $offset) {
                    $neighborCoords = self::getNeighborCoords($coords, $offset);
                    if (self::isValidMove($piece, $board, $coords, $neighborCoords)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private static function getNeighborCoords($coords, $offset)
    {
        $coords = explode(',', $coords);
        return ($coords[0] + $offset[0]) . ',' . ($coords[1] + $offset[1]);
    }

    private static function isValidMove($piece, $board, $from, $to)
    {
        switch ($piece[1]) {
            case 'grasshopper':
                return Grasshopper::isValidMove($board, $from, $to);
            case 'spider':
                return Spider::isValidMove($board, $from, $to);
            default:
                return Util::slide($board, $from, $to) !== false;
        }
    }
}
