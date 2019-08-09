<?php


namespace App;


class MovesRecording
{
    private $moves = [];

    /**
     * @return array
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    public function record(Piece $piece, string $start, string $end) :void
    {
        $this->moves[] = [$piece, $start, $end];
    }

    public function last()
    {
        $moves = $this->getMoves();
        return end($moves);
    }
    /**
     * @param array $moves
     * @return MovesRecording
     */
    public function setMoves(array $moves): self
    {
        $this->moves = $moves;

        return $this;
    }



}