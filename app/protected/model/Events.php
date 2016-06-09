<?php
Doo::loadCore('db/DooModel');

class Events extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $virtualboxes_id;

    /**
     * @var varchar Max length is 45.
     */
    public $title;

    /**
     * @var longtext
     */
    public $details;

    /**
     * @var varchar Max length is 45.
     */
    public $timestamp;

    public $_table = 'events';
    public $_primarykey = 'virtualboxes_id';
    public $_fields = array('id','virtualboxes_id','title','details','timestamp');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'virtualboxes_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'title' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'details' => array(
                        array( 'optional' ),
                ),

                'timestamp' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                )
            );
    }

}