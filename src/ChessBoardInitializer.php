<?php


namespace App;

class ChessBoardInitializer
{
    const CLASSIC_CHESSBOARD = [
        'A1' => [Tower::class, 'white'],
        'B1' => [Knight::class, 'white'],
        'C1' => [Bishop::class, 'white'],
        'D1' => [Queen::class, 'white'],
        'F1' => [Bishop::class, 'white'],
        'G1' => [Knight::class, 'white'],
        'H1' => [Tower::class, 'white'],
        'A8' => [Tower::class, 'black'],
        'B8' => [Knight::class, 'black'],
        'C8' => [Bishop::class, 'black'],
        'D8' => [Queen::class, 'black'],
        'F8' => [Bishop::class, 'black'],
        'G8' => [Knight::class, 'black'],
        'H8' => [Tower::class, 'black'],
    ];

    public static function initBoard(ChessBoard $chessboard): void
    {
        foreach ($chessboard::getColumns() as $column) {
            foreach ($chessboard::getRows() as $rowNb => $row) {
                $cases[$column][$row] = null;
            }
        }
        $chessboard->setCases($cases);
    }

    public static function initPieces(ChessBoard $chessboard, array $pieces) :void
    {
        foreach($pieces as $coord => $pieceType) {
            $piece = new $pieceType[0]($pieceType[1]);
            $chessboard->setPiece($coord, $piece);
        }
    }
}