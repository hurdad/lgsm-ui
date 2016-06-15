<?php
Doo::loadCore('db/DooModel');

class GearmanJobServers extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 50.
     */
    public $hostname;

    /**
     * @var smallint Max length is 5.
     */
    public $port;

    /**
     * @var tinyint Max length is 1.
     */
    public $enabled;

    public $_table = 'gearman_job_servers';
    public $_primarykey = 'id';
    public $_fields = array('id','hostname','port','enabled');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'hostname' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 5 ),
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