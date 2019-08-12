<?php

use App\ChessBoard;
use App\ChessBoardInitializer;
use App\Game;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require '../vendor/autoload.php';


session_start();
$movesRecording = $_SESSION['movesRecording'] ?? new \App\MovesRecording();

if (isset($_GET['reset'])) {
    session_destroy();
}

$loader = new FilesystemLoader('../src/View');
$twig = new Environment($loader, [
    'cache' => false,
]);

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

$chessboard = new ChessBoard($_SESSION['chessboard'] ?? ChessBoardInitializer::CLASSIC_CHESSBOARD);

$game = new Game($chessboard, true, $movesRecording);


if ($start && $end) {
    try {
        $game->gameMove($start, $end);
    } catch (LogicException $exception) {
        $error = $exception->getMessage();
    }
    unset($start);
    unset($end);
}

$_SESSION['chessboard'] = ChessBoardInitializer::createInitFromPieces($chessboard->render());
$_SESSION['movesRecording'] = $game->getMovesRecording();

echo $twig->render('index.html.twig', [
        'error'      => $error ?? '',
        'start' => $start ?? null,
        'moves' => $movesRecording,
        'chessboard' => $chessboard->render(),
    ]
);

