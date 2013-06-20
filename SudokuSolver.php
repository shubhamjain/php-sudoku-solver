<?php

require_once "Stack.php";

class SudokuSolver
{
    protected $_iSudoku = array();
    protected $_oSudoku = array();
    protected $_stack;
    protected $seedVal;

    const NOT_SOLVABLE = 10;
    const NOT_UNIQUE = 11;

    public function __construct( $sudoku = NULL, $stack = NULL, $seedVal = 0 )
    {
        $this->seedVal = $seedVal;

        if( $stack === NULL )
            $this->_stack = new Stack();
        else
            $this->_stack = $stack;

        if( $sudoku === NULL )
            $sudoku = str_repeat("0", 81);

        if( is_string($sudoku) ):
            $sudoku = str_split( $sudoku, 9 );

            array_walk( $sudoku, function( &$arg ){
                $arg = str_split($arg);
            });
        endif;

        $this->_iSudoku = $this->_oSudoku = $sudoku;
    }

    protected function _checkCol ( $m, $n, $val )
    {
        for( $i = 0; $i < 9; $i++ )
            if( (int)$this->_oSudoku[$m][$i] === $val )
                return FALSE;
        return TRUE;
    }

    protected function _checkRow ( $m, $n, $val )
    {
        for( $i = 0; $i < 9; $i++ )
            if( (int)$this->_oSudoku[$i][$n] === $val )
                return FALSE;
        return TRUE;
    }

    protected function _checkBlock ( $m, $n, $val )
    {
        $block = ((int)($m / 3)) * 3 + (int)($n / 3);
        $bc = (int)($block % 3);
        $br = (int)($block / 3);

        for( $i = $br * 3; $i < $br * 3 + 3 ; $i++ )
            for( $j = $bc * 3; $j < $bc * 3 + 3 ; $j++ )
                if( (int)$this->_oSudoku[$i][$j] === $val )
                    return FALSE;
        return TRUE;
    }

    public function checkValid( $m, $n, $val )
    {
        return ($this->_checkCol( $m, $n, $val ) and $this->_checkRow( $m, $n, $val ) and $this->_checkBlock( $m, $n, $val ));
    }

    /**
     * Function checks if the sudoku has a unique solution
     */
    public function HasUnique()
    {

        while( !$this->_stack->isEmpty() )
        {
            $stack = new Stack();
            $oldSudoku = &$this->_oSudoku;

            list($m, $n) = $this->_stack->pop();
            $val = $oldSudoku[$m][$n];
            $oldSudoku[$m][$n] = 0;

            $sudoku = new SudokuSolver( $oldSudoku, $stack, $val );

            if( $sudoku->Solve() !== self::NOT_SOLVABLE )
                return self::NOT_UNIQUE;

        }

        return TRUE;

    }

    public function Solve()
    {
        $m = $n = 0;

        fx: while( $m !== 9 ): //Loop till 9 x 9 sudoku processed

            if( ((int)($val = &$this->_oSudoku[$m][$n]) === 0))
            { 
                for( $i = $val + $this->seedVal + 1; $i <= 9; $i++ )
                {
                    $this->seedVal = 0;

                    if( $this->checkValid( $m, $n, $i ) )
                    {

                        $val = $i;
                        $this->_stack->push( $m, $n ); //Record the insertion

                        if( $n === 8):
                            $m += 1;
                            $n = 0;
                        else:
                            $n += 1;
                        endif;

                        goto fx; ///if insertion was valid continue while
                    }
                }

                $this->_oSudoku[$m][$n] = 0;

                if( $this->_stack->isEmpty() ) //If backtracked till begining return NOT SOLVABLE 
                    return self::NOT_SOLVABLE;
                else
                    list($m, $n) = $this->_stack->pop(); //backtrack

            } else {
                if( $n === 8):
                    $m += 1;
                    $n = 0;
                else:
                    $n += 1;
                endif;
            }
        endwhile;
    }

    public function OutputArray()
    {
        return $this->_oSudoku;
    }

    public function OutputString()
    {
        array_walk($this->_oSudoku, function( &$ele ) { $ele = implode("", $ele ); });

        return implode("", $this->_oSudoku);
    }

    public function __toString()
    {
        return print_r($this->_oSudoku, true);
    }

}
