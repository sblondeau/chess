<?php


namespace App;


abstract class Piece
{
    /**
     * @var string
     */
    protected $name;
    protected $symbol;
    protected $color;

    public function __construct(string $name, string $color, string $symbol)
    {
        $this->name = $name;
        $this->color = $color;
        $this->symbol = $symbol;
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

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     * @return Piece
     */
    public function setSymbol(string $symbol): Piece
    {
        $this->symbol = $symbol;

        return $this;
    }




}