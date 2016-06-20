<?php

class Net_Gearman_Job_deploy extends Net_Gearman_Job_Common {

    public function run($arg) {

    	//check args
    	if (!isset($arg['vbox_id']) || !isset($arg['base_image_id'])) {
            throw new Net_Gearman_Job_Exception("Missing Job Parameters!");
        }

        //get vm
        $vm = Doo::db()->getOne('Virtualboxes', array('where' => 'id = ?', 'param' => array($arg['vbox_id'])));
        if(!isset($vm)){
        	throw new Net_Gearman_Job_Exception("Invalid Virtualbox!");
        }

        //get vbox
		$vbox_soap = Doo::db()->getOne('VboxSoapEndpoints', array('where' => 'id = ?', 'param' => array($vm->vbox_soap_endpoints_id)));
	 	if(!isset($vbox_soap)){
        	throw new Net_Gearman_Job_Exception("Invalid Virtualbox Soap Endpoint!");
        }

        //get github repo
		$github = Doo::db()->getOne('Github', array('where' => 'id = ?', 'param' => array($vm->github_id)));
	 	if(!isset($github)){
        	throw new Net_Gearman_Job_Exception("Invalid Github Repo!");
        }

        //get game 
		$game = Doo::db()->getOne('Games', array('where' => 'id = ?', 'param' => array($vm->games_id)));
	 	if(!isset($game)){
        	throw new Net_Gearman_Job_Exception("Invalid Game!");;
        }

        //get base image
        $image = Doo::db()->getOne('BaseImages', array('where' => 'id = ?', 'param' => array($arg['base_image_id'])));
	 	if(!isset($image)){
        	throw new Net_Gearman_Job_Exception("Invalid Base Image!");
        }

        //get assigned services
        $sql_services = "SELECT 
		    script_name, is_default
		FROM
		    assigned_services
		        JOIN
		    services ON assigned_services.services_id = services.id
		WHERE
		    virtualboxes_id = " . $arg['vbox_id'];
		$services = Doo::db()->fetchAll($sql_services);
		if(count($services) == 0){
			throw new Net_Gearman_Job_Exception("Service Error: Must have at least one assigned service!");
		}

		//update state
		$vm->deploy_status = "Connecting to Virtualbox...";
		$vm->update();

        //include phpvirtualbox soap wrapper
    	require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/config.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/utils.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/vboxconnector.php');
		global $_SESSION;

		//vbox soap config
		$conf = new phpVBoxConfigClass;
		$conf->location = $vbox_soap->url;
		$conf->username = $vbox_soap->username;
		$conf->password = $vbox_soap->password;
		$vbox = new vboxconnector(false, $conf);		

		//get image vm
		$machine = $vbox->remote_vboxGetMachines(array('vm' => $image->name));
		if(!isset($machine[0])){
			throw new Net_Gearman_Job_Exception("Base Image ({$$image->name}) does not exist on Virtualbox!");
		}

		//generate new vm hostname
		$hostname = $image->name . " - " . $vm->id;

		//save hostname
		$vm->hostname = $hostname;
		$vm->deploy_status = "Cloning...";
		$vm->update();

		//clone
		$clone = $vbox->remote_machineClone(array('name' => $hostname, "vmState" => "MachineState", "src" => $machine[0]['id'], "reinitNetwork" => true ));

		//wait for clone progress to complete
		$progress = 0;
		while($progress < 100) {

			$prog = $vbox->remote_progressGet(array("progress" => $clone["progress"]));
			$progress = $prog['info']['percent'];

			//update status
			$vm->deploy_status = "Cloning... {$progress}/100" ;
			$vm->update();

			//sleep for one second to avoid spinning
			sleep(1);

		}
		unset($vbox); //disconnect

		//update status
		$vm->deploy_status = "Cloning Complete!" ;
		$vm->update();

		//add cloned vm
		$vbox = new vboxconnector(false, $conf); //reconnect
		$machine_folder = $vbox_soap->machine_folder;
		if(!$vbox->remote_machineAdd(array("file" => "{$machine_folder}/{$hostname}/{$hostname}.vbox"))){
			throw new Net_Gearman_Job_Exception("Unable to add Cloned VM!");
		}

		//get new machine
		$machine = $vbox->remote_vboxGetMachines(array("vm" => $hostname));
		if(!isset($machine[0])){
			throw new Net_Gearman_Job_Exception("Cannot find Base Image on Virtualbox!");
		}
		$machine_id = $machine[0]['id'];

		//update status
		$vm->deploy_status = "Resizing VM!" ;
		$vm->update();

		$details = $vbox->remote_machineGetDetails(array("vm" => $machine_id));
		$medias = $vbox->remote_vboxGetMedia();

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

		//resize machine 
		$details['CPUCount'] = 2;//$vm->cpu;
		$details['memorySize'] = 1024;//$vm->memory_mb;
		$vbox->remote_machineSave($details);
		
		//update status
		$vm->deploy_status = "Starting VM!" ;
		$vm->update();

		//power on new vm
		if(!$vbox->machineSetState(array("vm" => $machine_id, "state" => "powerUp"))){
			throw new Net_Gearman_Job_Exception("Unable to Power Up new VM!");
		}
		unset($vbox); //disconnect

		//init vars
		$found = false;
		$ip = "";
		//wait for vm to get ipv4 address from DHCP
		while (!$found){
			$vbox = new vboxconnector(false, $conf); //reconnect

			//get network properties
			$network = $vbox->remote_machineEnumerateGuestProperties(array("vm" => $machine_id, "pattern" =>"/VirtualBox/GuestInfo/Net/0/V4/IP"));

			//ipv4 addy
			$ip = $network[1][0];

			//check for ip
			if (preg_match("/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/i", $ip)) {
				$found = true;
			}

			unset($vbox); //disconnect

			//update status
			$vm->deploy_status = "Waiting for IPv4 Address" ;
			$vm->update();

			sleep(2); //sleep for 2 seconds to avoid spinning
		}

		//update status
		$vm->deploy_status = "IP Address received!" ;
		$vm->ip = $ip;
		$vm->update();

		//include ssh lib
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(Doo::conf()->SITE_PATH)  . '/include/phpseclib');
		include_once('Net/SSH2.php');

		//ssh to vm
		$ssh = new Net_SSH2($ip, $vm->ssh_port);

		//use ssh password
		if(isset($vm->ssh_password)) {
			if(!$ssh->login($vm->ssh_username, $vm->ssh_password)){
				$vm->deploy_status = "SSH login with pw failed : " . $vm->ip . "@" . $vm->ssh_username;
				$vm->update();
				throw new Net_Gearman_Job_Exception("SSH login with pw failed : " . $vm->ip . "@" . $vm->ssh_username);
			}
		} else if(isset($vm->ssh_key)){ //use ssh key
			$key = new Crypt_RSA();
			$key->loadKey($vm->ssh_key);
			if (!$ssh->login($vm->ssh_username, $key)) {
			    $vm->deploy_status =  "SSH login with key failed : " . $vm->ip . "@" . $vm->ssh_username;
			    $vm->update();
				throw new Net_Gearman_Job_Exception("SSH login with key failed : " . $vm->ip . "@" . $vm->ssh_username);
			}
		}
		$ssh->setTimeout(0);

		//update status
		$vm->deploy_status = "Cloning Github Repo..";
		$vm->update();

		//clone github repo into home directory
		if(!isset($github->ssh_key)) { //https clone
			$ssh->exec("git clone -b {$github->branch} {$github->url}");
		}else { //ssh clone
			$ssh->exec("ssh -o StrictHostKeyChecking=no git@github.com"); //disable strict host key checking
			$ssh->exec("git clone -b {$github->branch} {$github->url}");
		}

		//update status
		$vm->deploy_status = "Installing Dependancies..";
		$vm->update();
		//echo $ssh->exec("sudo yum -y install tmux glibc.i686 libstdc++.i686");

		//update status
		$vm->deploy_status = "Installing Game..";
		$vm->update();

		//parse github folder
		$github_folder = substr($github->url, strrpos($github->url, '/') + 1, strrpos($github->url, '.')-strrpos($github->url, '/') - 1);
		$game_folder_name = $game->folder_name;

		//make sure scripts are executable
		foreach($services as $s){
			$script_name = $s['script_name'];
			echo $ssh->exec("cd {$github_folder}/{$game_folder_name}/ && chmod +x {$script_name}");
		}

		//pick default service to run auto-install
		$s = Doo::db()->getOne('Services', array('where' => 'games_id = ? AND is_default = 1', 'param' => array($game->id)));
		$install_script = $s->script_name;
		echo $ssh->exec("cd {$github_folder}/{$game_folder_name}/ && ./{$install_script} auto-install");

		//update status
		$vm->deploy_status = "Starting Services..";
		$vm->update();

		//start services
		foreach($services as $s){
			$game_folder_name = $game->folder_name;
			$script_name = $s['script_name'];
			echo $ssh->exec("cd {$github_folder}/{$game_folder_name} && ./{$script_name} start");
		}

		//update status
		$vm->deploy_status = "Deployment Complete!";
		$vm->update();

        return true;
    }
}

?>
