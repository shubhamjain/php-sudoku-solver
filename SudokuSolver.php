<?php

require_once "Stack.php";

class SudokuSolver
{
    protected $_iSudoku = Array();
    protected $_oSudoku = Array();
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

    public function HasUnique()
    {

        strt:while( !$this->_stack->isEmpty() )
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
        $flag = FALSE;

        fx: while( $m !== 9 ):

            if( ((int)($val = &$this->_oSudoku[$m][$n]) === 0) or $flag )
            {
                for( $i = $val + $this->seedVal + 1; $i <= 9; $i++ )
                {
                    $this->seedVal = 0;

                    if( $this->checkValid( $m, $n, $i ) )
                    {

                        $flag = FALSE;
                        $val = $i;
                        $this->_stack->push( $m, $n );

                        if( $n === 8):
                            $m += 1;
                            $n = 0;
                        else:
                            $n += 1;
                        endif;

                        goto fx;
                    }
                }

                $this->_oSudoku[$m][$n] = 0;

                if( $this->_stack->isEmpty() )
                    return self::NOT_SOLVABLE;
                else
                    list($m, $n) = $this->_stack->pop();

                $flag = TRUE;

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

$sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
$sudoku->Solve();
print $sudoku->OutputString();



