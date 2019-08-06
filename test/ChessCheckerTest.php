<?php


namespace Test;


use App\ChessBoard;
use App\Tower;
use PHPUnit\Framework\TestCase;

class ChessCheckerTest extends TestCase
{
    public function testCaseColor()
    {
        $chessBoard = new ChessBoard();
        $this->assertInstanceOf(Tower::class, $chessBoard->getPiece('A1'));
        $this->assertInstanceOf(Tower::class, $chessBoard->getPiece('A8'));
        $this->assertInstanceOf(Tower::class, $chessBoard->getPiece('H1'));
        $this->assertInstanceOf(Tower::class, $chessBoard->getPiece('H8'));
        $this->assertNull($chessBoard->getPiece('F5'));
    }

    public function testCasePiece()
    {
        $chessBoard = new ChessBoard();
        $this->assertEquals('white', $chessBoard->getPiece('A1')->getColor());
    }


}