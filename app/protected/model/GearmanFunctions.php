<?php
Doo::loadCore('db/DooModel');

class GearmanFunctions extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 45.
     */
    public $function_name;

    /**
     * @var int Max length is 11.
     */
    public $worker_count;

    /**
     * @var tinyint Max length is 1.
     */
    public $enabled;

    public $_table = 'gearman_functions';
    public $_primarykey = 'id';
    public $_fields = array('id','function_name','worker_count','enabled');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'function_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'worker_count' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'enabled' => array(
                        array( 'integer' ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                )
            );
    }

}