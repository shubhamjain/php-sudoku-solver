<?php

    class GeneratorTest extends PHPUnit_Framework_TestCase
    {

        public function SetUp()
        {
            include_once "../SudokuGenerator.php";
        }

        public function testEleArray()
        {

            $sudoku = new SudokuGenerator(); //called with constructor
            
            $this->assertEquals(count($sudoku->EleArray), 81);
        }

        /*
         *  @depends testEleArray
         *  @covers SudokuGenerator::FillRandomValue()
         */
        public function testFillRandomValue()
        {

            $sudoku = new SudokuGenerator();

            $sudoku->FillRandomValue();
            $sudoku->Solve();

            $this->assertEquals($sudoku->HasUnique(), TRUE);
            $this->assertEquals(count($sudoku->EleArray), 80);
        }
}