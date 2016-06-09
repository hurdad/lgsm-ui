<?php
Doo::loadCore('db/DooModel');

class Games extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 45.
     */
    public $full_name;

    /**
     * @var varchar Max length is 45.
     */
    public $folder_name;

    /**
     * @var varchar Max length is 45.
     */
    public $default_script_name;

    /**
     * @var decimal Max length is 3. ,2).
     */
    public $glibc_version_min;

    /**
     * @var tinyint Max length is 1.
     */
    public $steamworks;

    /**
     * @var tinyint Max length is 1.
     */
    public $hidden;

    public $_table = 'games';
    public $_primarykey = 'id';
    public $_fields = array('id','full_name','folder_name','default_script_name','glibc_version_min','steamworks','hidden');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'full_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'folder_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'default_script_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'glibc_version_min' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'steamworks' => array(
                        array( 'integer' ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                ),

                'hidden' => array(
                        array( 'integer' ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                )
            );
    }

}