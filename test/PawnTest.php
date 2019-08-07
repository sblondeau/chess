<?php


namespace Test;


use App\ChessBoard;
use App\Game;
use App\Knight;
use App\Pawn;
use PHPUnit\Framework\TestCase;

class PawnTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = [
            'A2' => [Pawn::class, 'white'],
            'B2' => [Pawn::class, 'white'],
            'C2' => [Pawn::class, 'white'],
            'D2' => [Pawn::class, 'white'],
            'A3' => [Knight::class, 'white'],
            'B3' => [Knight::class, 'black'],
            'A7' => [Pawn::class, 'black'],
            'B7' => [Pawn::class, 'black'],
            'C7' => [Pawn::class, 'black'],
            'B6' => [Knight::class, 'white'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testWhiteMoveOneCase()
    {
        $this->game->gameMove('C2', 'C3');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C3'));
    }

    // TODO : move white/black
    // one/two case for 1st
    // prise en diago uniquement (revoir freecase)
    // prise en passant
    // promotion

//    public function testMoveTwoCase()
//    {
//        $this->game->gameMove('C2', 'C4');
//        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C4'));
//    }
//    public function testMoveTwiceOneCase()
//    {
//        $this->game->gameMove('C2', 'C3');
//        $this->game->gameMove('C2', 'C4');
//        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C4'));
//    }


    public function testNotFreeCaseMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B1', 'C3');
        $this->game->gameMove('C3', 'E2');
        $this->game->gameMove('E2', 'C1');
    }
}