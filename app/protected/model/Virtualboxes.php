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
    public $vbox_soap_endpoints_id;

    /**
     * @var int Max length is 11.
     */
    public $games_id;

    /**
     * @var int Max length is 11.
     */
    public $github_id;

    /**
     * @var varchar Max length is 45.
     */
    public $deploy_status;

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

    /**
     * @var varchar Max length is 45.
     */
    public $ssh_username;

    /**
     * @var longtext
     */
    public $ssh_key;

    /**
     * @var varchar Max length is 45.
     */
    public $ssh_password;

    /**
     * @var int Max length is 11.
     */
    public $ssh_port;

    public $_table = 'virtualboxes';
    public $_primarykey = 'id';
    public $_fields = array('id','vbox_soap_endpoints_id','games_id','github_id','deploy_status','hostname','ip','cpu','memory_mb','ssh_username','ssh_key','ssh_password','ssh_port');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'vbox_soap_endpoints_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'games_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'github_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'deploy_status' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'hostname' => array(
                        array( 'maxlength', 45 ),
                        array( 'optional' ),
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
                ),

                'ssh_username' => array(
                        array( 'maxlength', 45 ),
                        array( 'optional' ),
                ),

                'ssh_key' => array(
                        array( 'optional' ),
                ),

                'ssh_password' => array(
                        array( 'maxlength', 45 ),
                        array( 'optional' ),
                ),

                'ssh_port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}