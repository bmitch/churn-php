<?php

class ClassWithOneMethodWithNestedIf
{
    public function foobar()
    {
        if ($true) {
            if ($true) {
                return true;
            }
        }

        return false;
    }
}
