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
            'A1' => [Tower::class, 'white'],
            'H1' => [Tower::class, 'white'],
            'A8' => [Tower::class, 'black'],
            'H8' => [Tower::class, 'black'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testHorizontalTowerMove()
    {
        $this->game->gameMove('A1', 'B1');
        $this->assertInstanceOf(Tower::class, $this->game->getChessBoard()->getPiece('B1'));
    }

    public function testVerticalTowerMove()
    {
        $this->game->gameMove('A1', 'A5');
        $this->assertInstanceOf(Tower::class, $this->game->getChessBoard()->getPiece('A5'));
    }

    public function testNotAuthorizedTowerMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('A1', 'F5');
    }
}