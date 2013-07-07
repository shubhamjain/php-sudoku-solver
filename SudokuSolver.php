<?php

require_once "Stack.php";

class SudokuSolver {
    protected $_iSudoku = array();
    protected $_oSudoku = array();
    protected $_stack;
    protected $_blocks;
    protected $_oSudoku90;
    protected $_compare;

    const NOT_SOLVABLE = 10;
    const NOT_UNIQUE = 11;

    public function __construct( $sudoku = NULL, $stack = NULL, $seedVal = 0 ) {
        $this->seedVal = $seedVal;

        if ( $stack === NULL )
            $this->_stack = new Stack();
        else
            $this->_stack = $stack;


        if ( $sudoku === NULL )
            $sudoku = str_repeat( "0", 81 );
       
        /** Creates an sudoku array from the supplied string or empty string */
        if ( is_string( $sudoku ) ):
            $sudoku = str_split( $sudoku, 9 );

        array_walk( $sudoku, function( &$arg ) {
                $arg = str_split( $arg );
            } );
        endif;

        $this->_iSudoku = $this->_oSudoku = $sudoku;

        $this->_compare = range(1, 9);
        $this->_constuctBlock();
    }

    // Constructs block of 3 x 3 array, and row wise block that can later be used for direct
    // finding of possibles instead of looping
    protected function _constuctBlock() {
        for ( $x = 0; $x < 9; $x++ ) {
            $this->_oSudoku90[$x] = array();
            for ( $y = 0; $y < 9; $y++ ) {
                $this->_oSudoku90[$x][$y] = &$this->_oSudoku[$y][$x];
            }
        }

        // create '_blocks'
        for ( $blockX = 0; $blockX < 3; $blockX++ ) {
            $this->_blocks[$blockX] = array();
            for ( $blockY = 0; $blockY < 3; $blockY++ ) {
                $this->_blocks[$blockX][$blockY] = array();
                $gridX = $blockX * 3;
                for ( $cellX = 0; $cellX < 3; $cellX++ ) {
                    $gridY = $blockY * 3;
                    for ( $cellY = 0; $cellY <3; $cellY++ ) {
                        $this->_blocks[$blockX][$blockY][] = &$this->_oSudoku[$gridX][$gridY++];
                    }
                    $gridX++;
                }
            }
        }
    }

    /** The following functions find the possibles for column, row and 3 x 3 block */

    public function missingColumn($m) {
        return array_diff($this->_compare, $this->_oSudoku[$m]);
    }
    
    public function missingRow($n) {
        return array_diff($this->_compare, $this->_oSudoku90[$n]);
    }
    
    public function missingBlock($m,$n) {
        return array_diff($this->_compare, $this->_blocks[$m][$n]);
    }
    
    /* An intersect of all the possibles finds the possibles obeying rules of sudoku */
    public function possibles( $m, $n ) {
        return array_intersect(
            $this->missingBlock((int)$m / 3, (int)$n / 3),
            $this->missingColumn($m),
            $this->missingRow($n)
        );
    }

    public function checkValid( $m, $n, $val ) {
        return in_array( $val, array_intersect(
            $this->missingBlock((int)$m / 3, (int)$n / 3),
            $this->missingColumn($m),
            $this->missingRow($n)
        ));
    }

    /**
     * Function checks if the sudoku has a unique solution
     */
    public function HasUnique() {

        while ( !$this->_stack->isEmpty() ) {
            $stack = new Stack();
            $oldSudoku = &$this->_oSudoku;

            list( $m, $n ) = $this->_stack->pop();
            $val = $oldSudoku[$m][$n];
            $oldSudoku[$m][$n] = 0;

            $sudoku = new SudokuSolver( $oldSudoku, $stack, $val );

            if ( $sudoku->Solve() !== self::NOT_SOLVABLE )
                return self::NOT_UNIQUE;

        }

        return TRUE;

    }

    public function Solve() {
        $m = $n = 0;

        fx: while ( $m !== 9 ): //Loop till 9 x 9 sudoku processed

            if ( ( (int)( $cell = &$this->_oSudoku[$m][$n] ) === 0 ) ) {
                foreach ( $this->possibles($m, $n) as  $val) {
                    $this->seedVal = 0;
                    
                    //If cell's value was less than value of $val means insertion
                    //must be done otherwise it means it has returned from backtracking 
                    if( $cell < $val )
                        $cell = $val;
                    else
                        continue;

                    $this->_stack->push( $m, $n ); //Record the insertion

                    if ( $n === 8 ):
                        $m += 1;
                        $n = 0;
                    else:
                        $n += 1;
                    endif;

                    goto fx; ///if insertion was valid continue while
                }

                $this->_oSudoku[$m][$n] = 0;

                if ( $this->_stack->isEmpty() ) //If backtracked till begining return NOT SOLVABLE
                    return self::NOT_SOLVABLE;
                else
                    list( $m, $n ) = $this->_stack->pop(); //backtrack

            } else {
                if ( $n === 8 ):
                    $m += 1;
                    $n = 0;
                else:
                    $n += 1;
                endif;
        }
        endwhile;
    }

    public function OutputArray() {
        return $this->_oSudoku;
    }

    public function OutputString() {
        array_walk( $this->_oSudoku, function( &$ele ) { $ele = implode( "", $ele ); } );

        return implode( "", $this->_oSudoku );
    }

    public function __toString() {
        return print_r( $this->_oSudoku, true );
    }

}
