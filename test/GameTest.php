<?php


namespace Test;


use App\ChessBoard;
use App\ChessBoardInitializer;
use App\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = ChessBoardInitializer::CLASSIC_CHESSBOARD;
        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testNotFreeCaseMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('A1', 'C1');
    }

    public function testMoveWithPieceBeetween()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('A1', 'D1');
    }

    public function testCatchPiece()
    {
        $this->game
            ->gameMove('A2', 'A4')
            ->gameMove('A7', 'A5')
            ->gameMove('A1','A3')
            ->gameMove('A8','A6')
            ->gameMove('A3','B3')
            ->gameMove('A6','B6');
        $this->assertEquals('black', $this->game->getChessBoard()->getPiece('B6')->getColor());
        $this->game->gameMove('B3', 'B6');
        $this->assertEquals('white', $this->game->getChessBoard()->getPiece('B6')->getColor());
    }
}