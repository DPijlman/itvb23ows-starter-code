<?php
require_once __DIR__ . '/vendor/autoload.php';

use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;
use HiveGame\Database\Database;

SessionManager::startSession();

if (is_null(SessionManager::get('board'))) {
    header('Location: restart.php');
    exit();
}

$board = SessionManager::get('board');
$player = SessionManager::get('player');
$hand = SessionManager::get('hand');

$board_keys = array_keys($board);
$to = [];
foreach (Util::$OFFSETS as $pq) {
    foreach ($board_keys as $pos) {
        $pq2 = explode(',', $pos);
        $new_pos = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
        if (!isset($board[$new_pos]) || empty($board[$new_pos])) {
            $to[] = $new_pos;
        }
    }
}
$to = array_unique($to);
if (!count($to)) $to[] = '0,0';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Hive</title>
    <style>
        div.board {
            width: 60%;
            height: 100%;
            min-height: 500px;
            float: left;
            overflow: scroll;
            position: relative;
        }

        div.board div.tile {
            position: absolute;
        }

        div.tile {
            display: inline-block;
            width: 4em;
            height: 4em;
            border: 1px solid black;
            box-sizing: border-box;
            font-size: 50%;
            padding: 2px;
        }

        div.tile span {
            display: block;
            width: 100%;
            text-align: center;
            font-size: 200%;
        }

        div.player0 {
            color: black;
            background: white;
        }

        div.player1 {
            color: white;
            background: black
        }

        div.stacked {
            border-width: 3px;
            border-color: red;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="board">
        <?php
        $min_p = 1000;
        $min_q = 1000;
        foreach ($board as $pos => $tile) {
            $pq = explode(',', $pos);
            if ($pq[0] < $min_p) $min_p = $pq[0];
            if ($pq[1] < $min_q) $min_q = $pq[1];
        }
        foreach (array_filter($board) as $pos => $tile) {
            $pq = explode(',', $pos);
            $h = count($tile);
            echo '<div class="tile player' . $tile[$h - 1][0];
            if ($h > 1) echo ' stacked';
            echo '" style="left: ';
            echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
            echo 'em; top: ';
            echo ($pq[1] - $min_q) * 4;
            echo "em;\">($pq[0],$pq[1])<span>";
            echo substr($tile[$h - 1][1], 0, 1);
            echo '</span></div>';
        }
        ?>
    </div>
    <div class="hand">
        White:
        <?php
        foreach ($hand[0] as $tile => $ct) {
            for ($i = 0; $i < $ct; $i++) {
                echo '<div class="tile player0"><span>' . substr($tile, 0, 1) . "</span></div> ";
            }
        }
        ?>
    </div>
    <div class="hand">
        Black:
        <?php
        foreach ($hand[1] as $tile => $ct) {
            for ($i = 0; $i < $ct; $i++) {
                echo '<div class="tile player1"><span>' . substr($tile, 0, 1) . "</span></div> ";
            }
        }
        ?>
    </div>
    <div class="turn">
        Turn: <?php if ($player == 0) echo "White"; else echo "Black"; ?>
    </div>
    <form method="post" action="play.php">
        <select name="piece">
            <?php
            foreach ($hand[$player] as $tile => $ct) {
                for ($i = 0; $i < $ct; $i++) {
                    echo "<option value=\"$tile\">" . substr($tile, 0, 1) . "</option>";
                }
            }
            ?>
        </select>
        <select name="to">
            <?php
            foreach ($to as $pos) {
                if (Util::hasNeighbour($pos, $board) || count($board) == 0) {
                    if (!isset($board[$pos]) || empty($board[$pos])) {
                        echo "<option value=\"$pos\">$pos</option>";
                    }
                }
            }
            ?>
        </select>
        <input type="submit" value="Play">
    </form>
    <form method="post" action="move.php">
        <select name="from">
            <?php
            foreach (array_keys($board) as $pos) {
                $tile = $board[$pos];
                if ($tile[count($tile) - 1][0] == $player) {
                    echo "<option value=\"$pos\">$pos</option>";
                }
            }
            ?>
        </select>
        <select name="to">
            <?php
            foreach ($to as $pos) {
                if (!isset($board[$pos]) || empty($board[$pos])) {
                    echo "<option value=\"$pos\">$pos</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Move">
    </form>
    <form method="post" action="pass.php">
        <input type="submit" value="Pass">
    </form>
    <form method="post" action="restart.php">
        <input type="submit" value="Restart">
    </form>
    <form method="post" action="undo.php">
        <input type="submit" value="Undo">
    </form>
    <?php
    if (SessionManager::get('error')) {
        echo '<p class="error">' . SessionManager::get('error') . '</p>';
        SessionManager::set('error', null);
    }
    ?>
</body>
</html>
