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
        $this->assertEquals('black', $this->game->getChessBoard()->getPiece('A8')->getColor());
        $this->game->gameMove('A1', 'A8');
        $this->assertEquals('white', $this->game->getChessBoard()->getPiece('A8')->getColor());
    }
}