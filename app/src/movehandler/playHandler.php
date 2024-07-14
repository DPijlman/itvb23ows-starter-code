<?php
namespace HiveGame\MoveHandler;

use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;

class PlayHandler {
    public static function play($piece, $position) {
        $board = SessionManager::get('board');
        $player = SessionManager::get('player');
        $hand = SessionManager::get('hand');
        $turns = SessionManager::get('turns');

        if (!self::validatePlay($piece, $position, $hand, $player, $board, $turns[$player])) {
            return false;
        }

        self::updateBoard($board, $player, $piece, $position);
        self::updateHand($hand, $player, $piece);
        self::updateTurns($turns, $player);
        self::saveState($board, $hand, $player, $turns);

        return true;
    }

    private static function validatePlay($piece, $position, $hand, $player, $board, $turn) {
        if (!isset($hand[$player][$piece]) || $hand[$player][$piece] <= 0) {
            SessionManager::set('error', 'Invalid piece.');
            return false;
        }

        error_log("Turn: $turn, Queen Played: " . (self::queenPlayed($hand, $player) ? 'Yes' : 'No'));

        if ($turn >= 3 && !self::queenPlayed($hand, $player) && $piece != 'Q') {
            SessionManager::set('error', 'You must play the queen by your fourth turn.');
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

    private static function updateTurns(&$turns, $player) {
        $turns[$player]++;
    }

    private static function saveState($board, $hand, $player, $turns) {
        SessionManager::set('board', $board);
        SessionManager::set('hand', $hand);
        SessionManager::set('player', 1 - $player);
        SessionManager::set('turns', $turns);
    }

    private static function queenPlayed($hand, $player) {
        return isset($hand[$player]['Q']) && $hand[$player]['Q'] == 0;
    }
}
