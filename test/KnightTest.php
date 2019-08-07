<?php


namespace Test;


use App\Bishop;
use App\ChessBoard;
use App\Game;
use App\Knight;
use App\Tower;
use PHPUnit\Framework\TestCase;

class KnightTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = [
            'B1' => [Knight::class, 'white'],
            'A1' => [Tower::class, 'white'],
            'B2' => [Tower::class, 'white'],
            'C2' => [Bishop::class, 'white'],
            'C3' => [Bishop::class, 'black'],
            'D6' => [Bishop::class, 'black'],
            'G1' => [Knight::class, 'white'],
            'B8' => [Knight::class, 'black'],
            'G8' => [Knight::class, 'black'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testMoves()
    {
        $this->game->gameMove('B1', 'B3');
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('B3'));
        $this->game->gameMove('B3', 'B5');
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('B5'));
        $this->game->gameMove('B5', 'D5');
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('D5'));
        $this->game->gameMove('D5', 'B4');
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('B4'));
    }

    public function testJumbMove()
    {
        $this->game->gameMove('B1', 'C3');
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('C3'));
    }

    public function testCatch()
    {
        $this->assertEquals('black', $this->game->getChessBoard()->getPiece('D6')->getColor());
        $this->assertInstanceOf(Bishop::class, $this->game->getChessBoard()->getPiece('D6'));
        $this->game->gameMove('B1', 'C3');
        $this->game->gameMove('B1', 'D6');
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('D6'));
        $this->assertEquals('white', $this->game->getChessBoard()->getPiece('D6')->getColor());
    }

    public function testNotAuthorizedMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B1', 'D3');
    }

    public function testNotFreeCaseMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B1', 'C3');
        $this->game->gameMove('C3', 'E2');
        $this->game->gameMove('E2', 'C1');
    }
}