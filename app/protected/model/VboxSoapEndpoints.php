<?php
Doo::loadCore('db/DooModel');

class VboxSoapEndpoints extends DooModel{

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
    public $username;

    /**
     * @var varchar Max length is 45.
     */
    public $password;

    /**
     * @var varchar Max length is 100.
     */
    public $machine_folder;

    public $_table = 'vbox_soap_endpoints';
    public $_primarykey = 'id';
    public $_fields = array('id','url','username','password','machine_folder');

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

                'username' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'password' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'machine_folder' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                )
            );
    }

}