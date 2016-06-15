<?php
Doo::loadCore('db/DooModel');

class GearmanWorkers extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $pid;

    /**
     * @var varchar Max length is 50.
     */
    public $function_name;

    public $_table = 'gearman_workers';
    public $_primarykey = 'id';
    public $_fields = array('id','pid','function_name');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'pid' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'function_name' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                )
            );
    }

}