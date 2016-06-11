<?php
Doo::loadCore('db/DooModel');

class QueryEngines extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 45.
     */
    public $name;

    /**
     * @var varchar Max length is 45.
     */
    public $launch_uri;

    public $_table = 'query_engines';
    public $_primarykey = 'id';
    public $_fields = array('id','name','launch_uri');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'launch_uri' => array(
                        array( 'maxlength', 45 ),
                        array( 'optional' ),
                )
            );
    }

}