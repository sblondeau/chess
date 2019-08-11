<?php


namespace App;


Trait LineTrait
{
    private function checkColumn(ChessBoard $chessBoard, $colStart, $rowStart)
    {
        $colStartNumber = array_search($colStart, ChessBoard::getColumns()) + 1;
        for ($increment = -1; $increment <= 1; $increment += 2) {
            for ($col = $colStartNumber + $increment; $col >= ChessBoard::ROW_START && $col <= ChessBoard::ROW_END; $col += $increment) {
                $case = ChessBoard::getColumns()[$col-1] . $rowStart;
                if ($chessBoard->isEmptyCase($case)) {
                    $cases[] = $case;
                } elseif($chessBoard->getPiece($case)->getColor() !== $this->getColor()) {
                    $cases[] = $case;
                    break;
                } else {
                    break;
                }            }
        }

        return $cases ?? [];
    }

    private function checkRow(ChessBoard $chessBoard, $colStart, $rowStart)
    {
        for ($increment = -1; $increment <= 1; $increment += 2) {
            for ($row = $rowStart + $increment; $row >= ChessBoard::ROW_START && $row <= ChessBoard::ROW_END; $row += $increment) {
                $case = $colStart . $row;
                if ($chessBoard->isFreeCase($this, $case)) {
                    $cases[] = $case;
                } else {
                    break;
                }
            }
        }

        return $cases ?? [];
    }

}