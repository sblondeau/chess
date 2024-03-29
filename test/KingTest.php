<?php


namespace Test;


use App\Bishop;
use App\ChessBoard;
use App\Game;
use App\King;
use App\Pawn;
use App\Tower;
use PHPUnit\Framework\TestCase;

class KingTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $smallChessBoard = [
            'E1' => [King::class, 'white'],
            'F1' => [Pawn::class, 'black'],
            'H7' => [Pawn::class, 'black'],
            'E8' => [King::class, 'black'],
            'D4' => [Pawn::class, 'white'],
            'H5' => [Bishop::class, 'white'],
            'G6' => [Pawn::class, 'black'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testHorizontalMove()
    {
        $this->game->gameMove('E1', 'D1');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('D1'));
        $this->game->gameMove('D1', 'E1');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('E1'));
    }

    public function testVerticalMove()
    {
        $this->game->gameMove('E1', 'E2');
        $this->game->gameMove('E2', 'E3');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('E3'));
        $this->game->gameMove('E3', 'E2');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('E2'));
    }

    public function testDiagonalMove()
    {
        $this->game->gameMove('E8', 'D7');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('D7'));
        $this->game->gameMove('D7', 'E6');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('E6'));
        $this->game->gameMove('E6', 'F7');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('F7'));
        $this->game->gameMove('F7', 'E8');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('E8'));
    }

    public function testCatch()
    {
        $this->assertEquals('black', $this->game->getChessBoard()->getPiece('F1')->getColor());
        $this->game->gameMove('E1', 'F1');
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('F1'));
        $this->assertEquals('white', $this->game->getChessBoard()->getPiece('F1')->getColor());
    }

    public function testMoveInCheck() {
        $this->expectException(\LogicException::class);
        $this->game
            ->gameMove('E8', 'E7')
            ->gameMove('E7','E6')
            ->gameMove('E6','E5')
        ;
    }

    public function testNotAuthorizedMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('E1', 'H5');
    }

    public function testShouldMoveKingInCheck()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('H5', 'G6');
        $this->game->gameMove('H7', 'H6');
    }
}