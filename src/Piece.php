<?php


namespace App;


abstract class Piece
{
    /**
     * @var string
     */
    protected $name;

    protected $color;

    public function __construct(string $name, string $color)
    {
        $this->name = $name;
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Piece
     */
    public function setName(string $name): Piece
    {
        $this->name = $name;

        return $this;
    }

    abstract public function authorizedCase(ChessBoard $chessBoard, MovesRecording $movesRecording) :array;

    public function isMoveValid(ChessBoard $chessBoard, string $destination, MovesRecording $movesRecording) :bool
    {
        // TODO si la pièce bouge, il ne faut pas que ça mette son propre roi en échec (cas d'une protection par la piece)
        // tester donc d'enlever la piece (sauvegarder et mettre position à null sur le chessboard, verifier que isChess() à false et "readder" la piece;
        // si c'est bon, on move, sinon on empeche et leve une exception "impossible car met le roi en echec"

        return in_array($destination, $this->authorizedCase($chessBoard, $movesRecording));
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Piece
     */
    public function setColor(string $color): Piece
    {
        $this->color = $color;

        return $this;
    }


}