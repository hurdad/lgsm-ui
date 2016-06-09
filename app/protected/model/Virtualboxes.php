<?php
Doo::loadCore('db/DooModel');

class Virtualboxes extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $games_id;

    /**
     * @var varchar Max length is 45.
     */
    public $hostname;

    /**
     * @var varchar Max length is 45.
     */
    public $ip;

    /**
     * @var int Max length is 11.
     */
    public $cpu;

    /**
     * @var float
     */
    public $memory_mb;

    public $_table = 'virtualboxes';
    public $_primarykey = 'games_id';
    public $_fields = array('id','games_id','hostname','ip','cpu','memory_mb');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'games_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'hostname' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'ip' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'cpu' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'memory_mb' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                )
            );
    }

}