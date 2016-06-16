<?php

class UtilController extends DooController {

 	public function beforeRun($resource, $action)
    {
        //Init
        $this->res = new response();

        //set content type
        $this->contentType = 'json';
    }

    public function afterRun($routeResult)
    { 
    	//Display Results
        if (isset($this->contentType))
            $this->setContentType($this->contentType);
        
        echo $this->res->to_json();
    }

	function services(){

		//query db
		$game_id = $this->params['games_id'];

		$sql = "SELECT 
		    id, script_name, port, is_default
		FROM
		    services
		WHERE
		    games_id = {$game_id}";

		$this->res->data = Doo::db()->fetchAll($sql);
		$this->res->success = true;

	}
}
?>