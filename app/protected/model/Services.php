<?php
Doo::loadCore('db/DooModel');

class Services extends DooModel{

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
    public $script_name;

    /**
     * @var int Max length is 11.
     */
    public $port;

    /**
     * @var int Max length is 11.
     */
    public $query_port;

    /**
     * @var tinyint Max length is 1.
     */
    public $is_default;

    public $_table = 'services';
    public $_primarykey = 'id';
    public $_fields = array('id','games_id','script_name','port','query_port','is_default');

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

                'script_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'query_port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'is_default' => array(
                        array( 'integer' ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                )
            );
    }

}