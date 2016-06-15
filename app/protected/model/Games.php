<?php
Doo::loadCore('db/DooModel');

class Games extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $query_engines_id;

    /**
     * @var varchar Max length is 45.
     */
    public $full_name;

    /**
     * @var varchar Max length is 45.
     */
    public $folder_name;

    /**
     * @var decimal Max length is 3. ,2).
     */
    public $glibc_version_min;

    /**
     * @var tinyint Max length is 1.
     */
    public $hidden;

    public $_table = 'games';
    public $_primarykey = 'id';
    public $_fields = array('id','query_engines_id','full_name','folder_name','glibc_version_min','hidden');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'query_engines_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'full_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'folder_name' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'glibc_version_min' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'hidden' => array(
                        array( 'integer' ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                )
            );
    }

}