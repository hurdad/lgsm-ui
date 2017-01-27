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

		//get base image
        $image_id = $this->puts['image_id'];
        $image = Doo::db()->getOne('BaseImages', array('where' => 'id = ?', 'param' => array($image_id)));
        if(!$image){
        	$this->res->message = "Base Image doesnt Exist!";
        	$this->res->success = false;
        	return;
        }

        //get gearman job server
        $gearman_servers = array();
        foreach(Doo::db()->find('GearmanJobServers') as $s){
        	$gearman_servers[] = $s->hostname . ":" . $s->port;
        }

        //database transaction
        Doo::db()->beginTransaction();
		try {

		  	//init + insert vm
		  	$vm->deploy_status = "Initalizing..";
		  	$vm->ssh_username = $image->ssh_username;
		  	$vm->ssh_password = $image->ssh_password;
		  	$vm->ssh_key = $image->ssh_key;
		  	$vm->ssh_port = $image->ssh_port;
            $new_id = $vm->insert();

            //insert services
            foreach($this->puts['services'] as $s){
 				$as = new AssignedServices();
 				$as->services_id = $s;
 				$as->virtualboxes_id = $new_id;
 				$as->insert();
            }
                  
            //commit db
            Doo::db()->commit();
            $this->res->success = true;
            $this->res->data = $new_id;

            //send deploy request for vbox id
            set_include_path(get_include_path() . PATH_SEPARATOR .dirname(Doo::conf()->SITE_PATH)  . '/include/Net_Gearman');
			require_once 'Net/Gearman/Client.php';
			$client = new Net_Gearman_Client($gearman_servers);
			$client->deploy(array(
			    'vbox_id' => $new_id,
			    'base_image_id' => $image->id
			));
		}
		catch (Exception $e) {
            Doo::db()->rollBack();
            $this->res->success = false;
            $this->res->message .= "Unable to Create! ";
            $this->res->data = $e->getMessage();
            Doo::logger()->err("DB Create Error! " . $e->getMessage(), 'db');
        }

		$this->res->success = true;
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
		}

		$this->res->success = true;
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
			$this->res->success = $vbox_conn->machineSetState(array("vm" => $machine[0]["id"], "state"=>"powerUp"));
		} catch (Exception $e) {
		    $this->res->success = false;
		    $this->res->message = $e->getMessage();
		}
	}

	function resize() {

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

		//set vm details
		try{
			$vbox_conn = new vboxconnector(false, $conf);		
			$machine = $vbox_conn->remote_vboxGetMachines(array("vm"=> $vbox['hostname']));

			//check if running
			$vmState = (string)$machine[0]['state'];
			$vmRunning = ($vmState == 'Running' || $vmState == 'Paused' || $vmState == 'Saved');
			if($vmRunning){
 				$this->res->success = false;
		    	$this->res->message = "VM is not Powered Down!";
		    	return;
			}

			$machine_id = $machine[0]['id'];
			$details = $vbox_conn->remote_machineGetDetails(array("vm" => $machine_id));
			$medias = $vbox_conn->remote_vboxGetMedia();

			// Incoming list
			foreach($details['storageControllers'] as $sid => $sc){

				// Medium attachments
				foreach($sc['mediumAttachments'] as $ma) {

					$medium_id = $ma['medium']['id'];
					//scan medias
					foreach($medias as $m){

						//find matching medium
						if($m['id'] == $medium_id){
							$ma['medium']['hostDrive'] = $m['hostDrive'];
							$ma['medium']['location'] = $m['location'];

							//save hostDrive & location
							$details['storageControllers'][$sid]['mediumAttachments'][0]['medium']['hostDrive'] = $m['hostDrive'];
							$details['storageControllers'][$sid]['mediumAttachments'][0]['medium']['location'] = $m['location'];
						}
					}
				}
			}

			//update 
			$details['CPUCount'] = $this->params['cpu'];
			$details['memorySize'] = $this->params['mem'];

			//save
			$vbox_conn->remote_machineSave($details);

			//update vbox db
			$vm = Doo::db()->getOne('Virtualboxes', array('where' => 'id = ?', 'param' => array($vbox_id)));
			$vm->cpu = $this->params['cpu'];
			$vm->memory_mb = $this->params['mem'];
			$vm->update();

			//done
			$this->res->success = true;


		} catch (Exception $e) {
		    $this->res->success = false;
		    $this->res->message = $e->getMessage();
		}	

		
	}

	function delete() {
		
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

		//remove from virtualbox
		try{
			$vbox_conn = new vboxconnector(false, $conf);		
			$machine = $vbox_conn->remote_vboxGetMachines(array("vm"=> $vbox['hostname']));
			$machine_id = $machine[0]["id"];
			unset($vbox_conn); //disconnect

			//if running we must shutdown vm first
			if($machine[0]['state'] == 'Running'){
				$vbox_conn = new vboxconnector(false, $conf);		
				$machine = $vbox_conn->remote_machineSetState(array("vm" => $machine_id, "state" =>"powerButton"));
				unset($vbox_conn);

				//scan for sessionState to be not locked so we can remove
				$locked = true;
				while($locked){
					$vbox_conn = new vboxconnector(false, $conf);		
					$machine = $vbox_conn->remote_vboxGetMachines(array("vm"=> $vbox['hostname']));
					if($machine[0]['sessionState'] != "Locked"){
						$locked = false;
					}
					unset($vbox_conn); //disconnect
					sleep(2); //avoid spinning
				}
			}

			//remove
			$vbox_conn = new vboxconnector(false, $conf);		
			$vbox_conn->remote_machineRemove(array("vm" => $machine_id, "delete"=>"1"));
			unset($vbox_conn); //disconnect

		} catch (Exception $e) {
		    $this->res->success = false;
		    $this->res->message = $e->getMessage();
		    return;
		}

		//remove from db
		$vm = Doo::db()->getOne('Virtualboxes', array('where' => 'id = ?', 'param' => array($vbox_id)));
		$vm->delete();

		//done
		$this->res->success = true;
	}

	function refresh_ip() {

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

		$vm = Doo::db()->getOne('Virtualboxes', array('where' => 'id = ?', 'param' => array($vbox_id)));
        if(!isset($vm)){
        	throw new Net_Gearman_Job_Exception("Invalid Virtualbox!");
        }

		//vbox soap config
		$conf = new phpVBoxConfigClass;
		$conf->location = $vbox['url'];
		$conf->username = $vbox['username'];
		$conf->password = $vbox['password']; 

		//get ip from vbox connector and update db
		try{
			$vbox_conn = new vboxconnector(false, $conf);		
			$machine = $vbox_conn->remote_vboxGetMachines(array("vm"=> $vbox['hostname']));
			$machine_id = $machine[0]["id"];
			unset($vbox_conn); //disconnect

			$vbox = new vboxconnector(false, $conf); //reconnect

			//get network properties
			$network = $vbox->remote_machineEnumerateGuestProperties(array("vm" => $machine_id, "pattern" =>"/VirtualBox/GuestInfo/Net/0/V4/IP"));

			//ipv4 addy
			$ip = $network[1][0];

			//check for ip
			if (preg_match("/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/i", $ip)) {
				$vm->ip = $ip;
				$vm->update(); //update db
			}

			unset($vbox); //disconnect

		} catch (Exception $e) {
		    $this->res->success = false;
		    $this->res->message = $e->getMessage();
		    return;
		}

		//done
		$this->res->success = true;

	}
}
?>