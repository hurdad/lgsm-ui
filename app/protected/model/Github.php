<?php
Doo::loadCore('db/DooModel');

class Github extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $url;

    /**
     * @var varchar Max length is 45.
     */
    public $branch;

    /**
     * @var varchar Max length is 45.
     */
    public $username;

    /**
     * @var tinyint Max length is 1.
     */
    public $use_ssh;

    public $_table = 'github';
    public $_primarykey = 'id';
    public $_fields = array('id','url','branch','username','use_ssh');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'url' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'branch' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'username' => array(
                        array( 'maxlength', 45 ),
                        array( 'optional' ),
                ),

                'use_ssh' => array(
                        array( 'integer' ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                )
            );
    }

}