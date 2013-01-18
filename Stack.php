<?php

class Stack {

    private $stk = array();

    public function push($m, $n) {
        array_push( $this->stk, array($m, $n) );
    }

    public function pop() {
        return array_pop($this->stk);
    }

    public function isEmpty()
    {
        return count($this->stk) === 0;
    }

    public function stackHeight()
    {
        return count( $this->stk );
    }

    public function emptyStack()
    {
        while( !$this->isEmpty() )
            $this->pop();

    }

}