<?php


namespace App;


class Game
{
    private $chessBoard;
    private $movesRecording;

    public function __construct(ChessBoard $chessBoard)
    {
        $this->chessBoard = $chessBoard;
        $this->movesRecording = new MovesRecording();
    }

    // TODO
    // roi
    // gestion de l'échec
    // roque, promotion

    public function gameMove(string $start, string $end) :self
    {
        $piece = $this->chessBoard->getPiece($start);
        if(!$piece instanceof Piece) {
            throw new \LogicException('No piece at coordinate ' . $start);
        }
        $this->chessBoard->addPiece($end, $piece, $this->movesRecording);
        $this->chessBoard->setPiece($start, null);

        $this->movesRecording->record($piece, $start, $end);

        return $this;
    }

    /**
     * @return ChessBoard
     */
    public function getChessBoard(): ChessBoard
    {
        return $this->chessBoard;
    }

    /**
     * @param ChessBoard $chessBoard
     * @return Game
     */
    public function setChessBoard(ChessBoard $chessBoard): Game
    {
        $this->chessBoard = $chessBoard;

        return $this;
    }



}