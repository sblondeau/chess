<?php


namespace Test;


use App\Bishop;
use App\ChessBoard;
use App\Game;
use App\Knight;
use App\Pawn;
use App\Queen;
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
            'E2' => [Pawn::class, 'black'],
            'A3' => [Knight::class, 'white'],
            'B3' => [Knight::class, 'black'],
            'A7' => [Pawn::class, 'black'],
            'B7' => [Pawn::class, 'black'],
            'C7' => [Pawn::class, 'black'],
            'E7' => [Pawn::class, 'white'],
            'B6' => [Knight::class, 'white'],
            'G5' => [Pawn::class, 'white'],
            'H7' => [Pawn::class, 'black'],
        ];

        $this->chessboard = new ChessBoard($smallChessBoard);
        $this->game = new Game($this->chessboard);
    }

    public function testWhiteMoveOneCase()
    {
        $this->game->gameMove('C2', 'C3');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C3'));
    }

    public function testWhiteMultiMovesOneCase()
    {
        $this->game->gameMove('C2', 'C3');
        $this->game->gameMove('C3', 'C4');
        $this->game->gameMove('C4', 'C5');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C5'));
    }

    public function testWhiteMoveTwoCase()
    {
        $this->game->gameMove('C2', 'C4');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C4'));
    }

    public function testWhiteMoveTwoCaseOneCase()
    {
        $this->game->gameMove('C2', 'C4');
        $this->game->gameMove('C4', 'C5');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C5'));
    }

    public function testWhiteMoveTwiceTwoCase()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('C2', 'C4');
        $this->game->gameMove('C4', 'C6');
    }

    public function testWhiteMoveWrongDirection()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('C2', 'C4');
        $this->game->gameMove('C4', 'C3');
    }

    public function testWhiteVsBlackMove()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('C2', 'C4');
        $this->game->gameMove('C7', 'C5');
        $this->game->gameMove('C5', 'C4');
    }

    public function testBlackMoveOneCase()
    {
        $this->game->gameMove('C7', 'C6');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('C6'));
    }


    public function testWhiteOneMovePieceAlreadyHere()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B2', 'B3');
    }

    public function testBlackOneMovePieceAlreadyHere()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B7', 'B6');
    }

    public function testWhiteMoveTwicePieceBetween()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B2', 'B4');
    }

    public function testBlackMoveTwicePieceBetween()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B7', 'B5');
    }

    public function testWhiteDiagonalMoveWithoutCatch()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('C2', 'D3');
    }

    public function testWhiteDiagonalMoveNotFreeCase()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('B2', 'A3');
    }

    public function testWhiteDiagonalMoveWithCatch()
    {
        $this->game->gameMove('A2', 'B3');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('B3'));
    }

    public function testWrongEnPassant()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('H7', 'H6');
        $this->game->gameMove('H6', 'H5');
        $this->game->gameMove('G5', 'H6');
    }

    public function testEnPassant()
    {
        $this->game->gameMove('H7', 'H5');
        $this->game->gameMove('G5', 'H6');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('H6'));
    }
    public function testEnPassantTooLate()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('H7', 'H5');
        $this->game->gameMove('D2', 'D3');
        $this->game->gameMove('A7', 'A6');
        $this->game->gameMove('G5', 'H6');
    }

    public function testWrongPromotion()
    {
        $this->expectException(\LogicException::class);
        $pawn = $this->game->getChessBoard()->getPiece('E7');
        $this->game->promote($pawn, Queen::class);
    }

    public function testWrongPromotionPiece()
    {
        $this->expectException(\LogicException::class);
        $this->game->gameMove('E7', 'E8');
        $pawn = $this->game->getChessBoard()->getPiece('E8');
        $this->game->promote($pawn, Pawn::class);
    }

    public function testWhitePromotion()
    {
        $this->game->gameMove('E7', 'E8');
        $pawn = $this->game->getChessBoard()->getPiece('E8');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('E8'));
        $this->game->promote($pawn, Queen::class);
        $this->assertInstanceOf(Queen::class, $this->game->getChessBoard()->getPiece('E8'));
    }

    public function testBlackPromotion()
    {
        $this->game->gameMove('E2', 'E1');
        $pawn = $this->game->getChessBoard()->getPiece('E1');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('E1'));
        $this->game->promote($pawn, Queen::class);
        $this->assertInstanceOf(Queen::class, $this->game->getChessBoard()->getPiece('E1'));
    }

    public function testWhitePromotionKnight()
    {
        $this->game->gameMove('E7', 'E8');
        $pawn = $this->game->getChessBoard()->getPiece('E8');
        $this->assertInstanceOf(Pawn::class, $this->game->getChessBoard()->getPiece('E8'));
        $this->game->promote($pawn, Knight::class);
        $this->assertInstanceOf(Knight::class, $this->game->getChessBoard()->getPiece('E8'));
    }
}