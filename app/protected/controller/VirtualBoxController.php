<?php

class VirtualBoxController extends DooController {

    public function beforeRun($resource, $action)
    {

    	//include phpvirtualbox soap wrapper
    	require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/config.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/utils.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/vboxconnector.php');

		global $_SESSION;

        //Init
        $this->res = new response();

        //set content type
        $this->contentType = 'json';
    }

    public function afterRun($routeResult)
    {
        //Check for success Result
        if ($routeResult == null || $routeResult == 200) {
            //Display Results
            if (isset($this->contentType))
                $this->setContentType($this->contentType);
            
            echo $this->res->to_json();
        }
    }

	function add() {

		//init db object	
		$this->initPutVars();
        $vm = new Virtualboxes($this->puts);

        $image_id = $this->puts['image_id'];
        $image = Doo::db()->getOne('BaseImages', 'where' => 'id = ?', 'params' => array($image_id));
        if(!$image){
        	$this->res->message = "Base Image doesnt Exist!";
        	$this->res->success = false;
        	return;
        }

        //$image->name

        $vm->deploy_status = "Initalizing..";

		try {

			Doo::db()->beginTransaction();

		  	//insert vm
            $new_id = $vm->insert();

            //insert services
                  
            //commit
            Doo::db()->commit();
            $this->res->success = true;
            $this->res->data = $new_id;
		}
		catch (Exception $e) {
            Doo::db()->rollBack();
            $this->res->success = false;
            $this->res->message .= "Unable to Create! ";
            $this->res->data = $e->getMessage();
            Doo::logger()->err("DB Create Error! " . $e->getMessage(), 'db');
        }

	}

	function stop() {
		
		//query db
		$vbox_id = $this->params['id'];
		$sql_vboxes = "SELECT 
			    virtualboxes.id, url, vbox_soap_endpoints.username, password, hostname, ip, deploy_status
			FROM
			    virtualboxes
			        JOIN
			    vbox_soap_endpoints ON virtualboxes.vbox_soap_endpoints_id = vbox_soap_endpoints.id
			WHERE
			    virtualboxes.id = " . $vbox_id;
		$vbox = Doo::db()->fetchRow($sql_vboxes);
		if(isset($vbox) == 0){
			$this->res->message = "VM doesnt exist!";
			return;
		}

		//vbox soap config
		$conf = new phpVBoxConfigClass;
		$conf->location = $vbox['url'];
		$conf->username = $vbox['username'];
		$conf->password = $vbox['password'];

		//set vm machine state
		try{
			$vbox_conn = new vboxconnector(false, $conf);		
			$machine = $vbox_conn->remote_vboxGetMachines(array("vm"=> $vbox['hostname']));
			$this->res->success = $vbox_conn->machineSetState(array("vm" => $machine[0]["id"], "state"=>"powerDown"));
		} catch (Exception $e) {
		    $this->res->success = false;
		    $this->res->message = $e->getMessage();
		}$this->res->success = true;
	}

	function start() {

		//query db
		$vbox_id = $this->params['id'];
		$sql_vboxes = "SELECT 
			    virtualboxes.id, url, vbox_soap_endpoints.username, password, hostname, ip, deploy_status
			FROM
			    virtualboxes
			        JOIN
			    vbox_soap_endpoints ON virtualboxes.vbox_soap_endpoints_id = vbox_soap_endpoints.id
			WHERE
			    virtualboxes.id = " . $vbox_id;
		$vbox = Doo::db()->fetchRow($sql_vboxes);
		if(isset($vbox) == 0){
			$this->res->message = "VM doesnt exist!";
			return;
		}

		//vbox soap config
		$conf = new phpVBoxConfigClass;
		$conf->location = $vbox['url'];
		$conf->username = $vbox['username'];
		$conf->password = $vbox['password']; 

		//set vm machine state
		try{
			$vbox_conn = new vboxconnector(false, $conf);		
			$machine = $vbox_conn->remote_vboxGetMachines(array("vm"=> $vbox['hostname']));
			var_dump($machine);
			$this->res->success = $vbox_conn->machineSetState(array("vm" => $machine[0]["id"], "state"=>"powerUp"));
		} catch (Exception $e) {
		    $this->res->success = false;
		    $this->res->message = $e->getMessage();
		}
	}

	function resize() {
		echo 'You are visiting '.$_SERVER['REQUEST_URI'];
	}
}
?>