<?php

class Net_Gearman_Job_deploy extends Net_Gearman_Job_Common
{
    public function run($args)
    {
        var_dump($args);
        if (!isset($args['userid']) || !isset($args['action'])) {
            // Throw a Net_Gearman_Job_Exception to report back to the server that the job failed.
            throw new Net_Gearman_Job_Exception('Invalid/Missing arguments');
        }

        // Insert a record or something based on the $args

        return array(); // Results are returned to Gearman, except for
                        // background jobs like this one.
    }
}

?>
