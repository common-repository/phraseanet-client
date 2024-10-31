<?php

//Every license method should return init method
interface LicenseInterface
{
    public function init();
}


//Every license class should implement this interface
//and implement the init method to return the license object
class PRO implements LicenseInterface
{
    public function init()
    {
        return $this->pwc_fs();//init the freemius license
    }


    // Create a helper function for easy freemius SDK access.
    private function pwc_fs()
    {
        global $pwc_fs;
        if (! isset($pwc_fs)) {
            require_once  dirname(__DIR__, 1).'/freemius.php';
            $pwc_fs = RegisterFreemius();
        }

        return $pwc_fs;
    }
}
