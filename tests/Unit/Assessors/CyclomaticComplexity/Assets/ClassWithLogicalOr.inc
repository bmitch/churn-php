<?php

class ClassWithLogicalOr
{
    public function foobar()
    {
        return ($a == $b || $c == $d);
    }
}
