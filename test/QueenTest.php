<?php


namespace Test;


use App\ChessBoard;
use App\Game;
use App\Queen;
use PHPUnit\Framework\TestCase;

class QueenTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = [
            'D1' => [Queen::class, 'white'],
            'D8' => [Queen::class, 'black'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testHorizontalMove()
    {
        $this->game->gameMove('D1', 'B1');
        $this->assertInstanceOf(Queen::class, $this->game->getChessBoard()->getPiece('B1'));
    }

    public function testVerticalMove()
    {
        $this->game->gameMove('D1', 'D5');
        $this->assertInstanceOf(Queen::class, $this->game->getChessBoard()->getPiece('D5'));
    }

    public function testDiagonalMove()
    {
        $this->game->gameMove('D1', 'B3');
        $this->assertInstanceOf(Queen::class, $this->game->getChessBoard()->getPiece('B3'));
    }

    public function testCatch()
    {
        $this->assertEquals('black', $this->game->getChessBoard()->getPiece('D8')->getColor());
        $this->game->gameMove('D1', 'D8');
        $this->assertInstanceOf(Queen::class, $this->game->getChessBoard()->getPiece('D8'));
        $this->assertEquals('white', $this->game->getChessBoard()->getPiece('D8')->getColor());
    }

    public function testNotAuthorizedMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('D1', 'H2');
    }
}