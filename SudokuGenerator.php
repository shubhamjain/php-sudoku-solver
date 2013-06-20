<?php

require_once "Stack.php";
require_once "SudokuSolver.php";

class SudokuGenerator extends SudokuSolver
{
    public $EleArray = NULL;

    const METRIC_EASY = 27;
    const METRIC_MEDIUM = 22;
    const METRIC_HARD = 17;

    public function __construct()
    {
        $this->EleArray();
        parent::__construct();
    }

    /*
    Creates an array of possible (row, col) combinations. So that uniquely a random elemnt can be selected
     */
    public function EleArray() 
    {
        $EleArray = array();

        foreach(range(0, 8) as $i )
            foreach(range(0, 8) as $j )
                    $EleArray[] = array($i, $j);

        $this->EleArray = $EleArray;

    }

    public function FillRandomValue()
    {

        if( $this->EleArray === NULL )
            throw new Exception('$this->EleArray() must be called before FillRandomValue', 1);
            
        $ele = array_rand( $this->EleArray );

        $randCol = $this->EleArray[$ele][0];
        $randRow = $this->EleArray[$ele][1];

        unset($this->EleArray[$ele]);


        for ( $i = 1; $i <= 9; $i++ )
        {
            if( $this->checkValid($randRow, $randCol, $i) )
            {

                $this->_oSudoku[$randRow][$randCol] = $i;
                break;
            }
        }

    }

    public function GenerateSudoku( $difficulty )
    {
        $this->EleArray();

        for( $i = 0; $i < $difficulty; $i++ )
            $this->FillRandomValue();

        do {
            $this->FillRandomValue();
            $this->Solve();
        } while( $this->HasUnique() === self::NOT_UNIQUE );

    }

}