<?php
Doo::loadCore('db/DooModel');

class AssignedServices extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $services_id;

    /**
     * @var int Max length is 11.
     */
    public $virtualboxes_id;

    public $_table = 'assigned_services';
    public $_primarykey = 'id';
    public $_fields = array('id','services_id','virtualboxes_id');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'services_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'virtualboxes_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}