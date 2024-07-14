<?php
namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;

class PlayHandler {
    public static function play($piece, $position) {
        $board = SessionManager::get('board');
        $player = SessionManager::get('player');
        $hand = SessionManager::get('hand');

        if (!self::validatePlay($piece, $position, $hand, $player, $board)) {
            return false;
        }

        self::updateBoard($board, $player, $piece, $position);
        self::updateHand($hand, $player, $piece);
        self::saveState($board, $hand, $player);

        return true;
    }

    private static function validatePlay($piece, $position, $hand, $player, $board) {
        if (!isset($hand[$player][$piece]) || !$hand[$player][$piece]) {
            SessionManager::set('error', 'Invalid piece.');
            return false;
        }

        if (!Util::hasNeighbour($position, $board) && count($board) > 0) {
            SessionManager::set('error', 'Invalid position.');
            return false;
        }

        return true;
    }

    private static function updateBoard(&$board, $player, $piece, $position) {
        if (!isset($board[$position])) {
            $board[$position] = [];
        }
        $board[$position][] = [$player, $piece];
    }

    private static function updateHand(&$hand, $player, $piece) {
        $hand[$player][$piece]--;
        if ($hand[$player][$piece] == 0) {
            unset($hand[$player][$piece]);
        }
    }

    private static function saveState($board, $hand, $player) {
        SessionManager::set('board', $board);
        SessionManager::set('hand', $hand);
        SessionManager::set('player', 1 - $player);
        SessionManager::set('turns', $turns);
    }

    private static function queenPlayed($hand, $player) {
        return !isset($hand[$player]['Q']);
    }
}
