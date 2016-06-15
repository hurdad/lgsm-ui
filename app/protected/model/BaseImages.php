<?php
Doo::loadCore('db/DooModel');

class BaseImages extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $vbox_soap_endpoints_id;

    /**
     * @var varchar Max length is 45.
     */
    public $name;

    /**
     * @var decimal Max length is 3. ,2).
     */
    public $glibc_version;

    /**
     * @var enum '32 bit','64 bit').
     */
    public $architecture;

    /**
     * @var varchar Max length is 45.
     */
    public $username;

    /**
     * @var longtext
     */
    public $ssh_key;

    /**
     * @var varchar Max length is 45.
     */
    public $ssh_password;

    public $_table = 'base_images';
    public $_primarykey = 'id';
    public $_fields = array('id','vbox_soap_endpoints_id','name','glibc_version','architecture','username','ssh_key','ssh_password');

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

                'name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'glibc_version' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'architecture' => array(
                        array( 'notnull' ),
                ),

                'username' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'ssh_key' => array(
                        array( 'optional' ),
                ),

                'ssh_password' => array(
                        array( 'maxlength', 45 ),
                        array( 'optional' ),
                )
            );
    }

}