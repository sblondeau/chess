<?php


namespace Test;


use App\Bishop;
use App\ChessBoard;
use App\Game;
use App\Tower;
use PHPUnit\Framework\TestCase;

class BishopTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = [
            'C1' => [Bishop::class, 'white'],
            'E3' => [Tower::class, 'white'],
            'F1' => [Bishop::class, 'white'],
            'C8' => [Bishop::class, 'black'],
            'F8' => [Bishop::class, 'black'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testDiagonalUpRightMove()
    {
        $this->game->gameMove('C1', 'D2');
        $this->assertInstanceOf(Bishop::class, $this->game->getChessBoard()->getPiece('D2'));
    }
    
    public function testDiagonalUpLeftMove()
    {
        $this->game->gameMove('C1', 'A3');
        $this->assertInstanceOf(Bishop::class, $this->game->getChessBoard()->getPiece('A3'));
    }

    public function testDiagonalDownRightMove()
    {
        $this->game->gameMove('C8', 'H3');
        $this->assertInstanceOf(Bishop::class, $this->game->getChessBoard()->getPiece('H3'));
    }

    public function testDiagonalDownLeftMove()
    {
        $this->game->gameMove('F8', 'E7');
        $this->assertInstanceOf(Bishop::class, $this->game->getChessBoard()->getPiece('E7'));
    }

    public function testNotAuthorizedTowerMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('F8', 'A8');
    }

    public function testDiagonalBlockedMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('C1', 'D2');
        $this->game->gameMove('D2', 'F4');
    }

}