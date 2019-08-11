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

    abstract public function authorizedCase(ChessBoard $chessBoard) :array;

    public function isMoveValid(ChessBoard $chessBoard, string $destination) :bool
    {
        return in_array($destination, $this->authorizedCase($chessBoard));
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