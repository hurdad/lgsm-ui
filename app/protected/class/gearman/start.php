<?php

class Net_Gearman_Job_start extends Net_Gearman_Job_Common
{
    public function run($arg)
    {
        echo 'run';

        return true;
    }
}

?>
