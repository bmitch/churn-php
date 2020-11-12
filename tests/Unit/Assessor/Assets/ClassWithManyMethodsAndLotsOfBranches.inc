<?php

class ClassWithWhileLoop
{
    public function foo()
    {
        for ($n = 0; $n < $h; $n++) {
            foobar();
        }
    }

    public function bar()
    {
        if ($true) {
            return true;
        } elseif ($false) {
            return false;
        }

        if ($true) {
            if ($true) {
                return true;
            }
        }

        return false;
    }

    public function baz()
    {
        if ($true) {
            return true;
        }

        return false;
    }

    public function uggh()
    {
        switch ($z) {
            case 1:
                foobar();
                break;
            case 2:
                foobar();
                break;
            case 3:
                foobar();
                break;
            default:
                foobar();
                break;
        }
        
        while ($c == $d) {
            foobar();
        }
    }
}
}
