<?php


namespace Test;


use App\ChessBoard;
use App\ChessBoardInitializer;
use App\Game;
use App\King;
use App\Tower;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private $chessboard;
    private $game;

    public function setUp(): void
    {
        $this->chessboard = new ChessBoard();
        $this->game = new Game($this->chessboard, true);
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
            ->gameMove('A1', 'A3')
            ->gameMove('A8', 'A6')
            ->gameMove('A3', 'B3')
            ->gameMove('A6', 'B6');
        $this->assertEquals('black', $this->game->getChessBoard()->getPiece('B6')->getColor());
        $this->game->gameMove('B3', 'B6');
        $this->assertEquals('white', $this->game->getChessBoard()->getPiece('B6')->getColor());
    }

    public function testSmallRoque()
    {
        $this->game
            ->gameMove('G2', 'G3')
            ->gameMove('A7', 'A6')
            ->gameMove('F1', 'H3')
            ->gameMove('A6', 'A5')
            ->gameMove('G1', 'F3');
        $this->game->roque('small');
        $this->assertInstanceOf(Tower::class, $this->game->getChessBoard()->getPiece('F1'));
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('G1'));
    }

    public function testImpossibleRoquePieceBeetween()
    {
        $this->expectException(\LogicException::class);
        $this->game->roque('small');
    }

    public function testImpossibleRoqueInCheck()
    {
        $this->expectException(\LogicException::class);

        $this->game
            ->gameMove('G2', 'G4')
            ->gameMove('E7', 'E6')
            ->gameMove('F1', 'H3')
            ->gameMove('D8', 'G5')
            ->gameMove('G1', 'F3')
            ->gameMove('G5', 'G4');
        $this->game->roque('small');

    }

    public function testImpossibleRoquePieceNotHere()
    {
        $this->expectException(\LogicException::class);

        $this->game
            ->gameMove('G2', 'G3')
            ->gameMove('F1', 'H3')
            ->gameMove('G1', 'F3')
            ->gameMove('E1', 'F1');
        $this->game->roque('small');
    }

    public function testImpossibleRoquePieceAlreadyMove()
    {
        $this->expectException(\LogicException::class);

        $this->game
            ->gameMove('G2', 'G3')
            ->gameMove('F1', 'H3')
            ->gameMove('G1', 'F3')
            ->gameMove('E1', 'F1')
            ->gameMove('F1', 'E1');
        $this->game->roque('small');
    }

    public function testImpossibleBigRoque()
    {
        $this->expectException(\LogicException::class);

        $this->game->roque('big');
    }

    public function testBigBlackRoque()
    {
        $this->game
            ->gameMove('B7', 'B6')
            ->gameMove('A2', 'A3')
            ->gameMove('C8', 'A6')
            ->gameMove('A3', 'A4')
            ->gameMove('B8', 'C6')
            ->gameMove('A4', 'A5')
            ->gameMove('D7', 'D6')
            ->gameMove('H2', 'H3')
            ->gameMove('D8', 'D7');
        $this->game->roque('big', 'black');
        $this->assertInstanceOf(Tower::class, $this->game->getChessBoard()->getPiece('D8'));
        $this->assertInstanceOf(King::class, $this->game->getChessBoard()->getPiece('C8'));
    }

    public function testStrictPlayerSwitchWhiteWrong()
    {
        $this->expectException(\LogicException::class);
        $this->game
            ->gameMove('A2','A4')
            ->gameMove('A4','A5')
        ;
    }
    public function testStrictPlayerSwitchBlackWrong()
    {
        $this->expectException(\LogicException::class);
        $this->game
            ->gameMove('A7','A6')
            ->gameMove('A6','A5')
        ;
    }
}