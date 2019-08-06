<?php


namespace Test;


use App\ChessBoard;
use App\ChessBoardInitializer;
use App\Game;
use App\Tower;
use PHPUnit\Framework\TestCase;

class TowerTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = [
            'D1' => [Queen::class, 'white'],
            'D8' => [Queen::class, 'white'],
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

    public function testNotAuthorizedMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('D1', 'H2');
    }
}