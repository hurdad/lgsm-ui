<?php

class Net_Gearman_Job_update extends Net_Gearman_Job_Common
{
    public function run($arg) {
     
       	//check args
    	if (!isset($arg['vbox_id'])) {
            throw new Net_Gearman_Job_Exception("Missing Job Parameters!");
        }

        //include ssh lib
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(Doo::conf()->SITE_PATH)  . '/include/phpseclib');
		include_once('Net/SSH2.php');

		//query db
		$vbox_id = $arg['vbox_id'];

		$vm = Doo::db()->getOne('Virtualboxes', array('where' => 'id = ?', 'param' => array($vbox_id)));
		if(!isset($vm)){
			throw new Net_Gearman_Job_Exception("Virtualbox Doesnt exist!");
		}

		$sql = "SELECT 
			    ip,
			    virtualboxes.ssh_username AS ssh_username,
			    ssh_password,
			    virtualboxes.ssh_key,
			    ssh_port,
			    folder_name,
			    script_name,
			    url
			FROM
			    services
			        JOIN
			    assigned_services ON services.id = assigned_services.services_id
			        JOIN
			    virtualboxes ON assigned_services.virtualboxes_id = virtualboxes.id
			        JOIN
			    games ON virtualboxes.games_id = games.id
			        JOIN
			    github ON virtualboxes.github_id = github.id
			WHERE
			    virtualboxes.id = {$vbox_id}";

		//get assigned services for vbox
		$services = Doo::db()->fetchAll($sql);
		if(count($services) == 0){
			throw new Net_Gearman_Job_Exception("Service Error: Must have at least one assigned service!");
		}

		//update estatus
		$vm->deploy_status = "Stopping Services..";
		$vm->update();

		//loop sevices
		foreach($services as $service){

			$ssh = new Net_SSH2($service['ip'], $service['ssh_port']);
			//use ssh password
			if(isset($service['ssh_password']) && !empty($service['ssh_password'])){
				if(!$ssh->login($service['ssh_username'], $service['ssh_password'])){
					$this->res->message = "SSH login with pw failed : " . $service['ip'] . "@" . $service['ssh_username'];
					return;
				}
			} else if(isset($service['ssh_key']) && !empty($service['ssh_key'])){ //use ssh key
				include_once('Crypt/RSA.php');
				$key = new Crypt_RSA();
				$key->loadKey($service['ssh_key']);
				if (!$ssh->login($service['ssh_username'], $key)) {
				    $this->res->message = "SSH login with key failed : " . $service['ip'] . "@" . $service['ssh_username'];
					return;
				}
			}
			$ssh->setTimeout(0);

			//parse github folder
			$github_url = $service['url'];
			$github_folder = substr($github_url, strrpos($github_url, '/') + 1, strrpos($github_url, '.')-strrpos($github_url, '/') - 1);
			$game_folder_name = $service['folder_name'];
			$script_name = $service['script_name'];

			//run ssh command
			echo $ssh->exec("cd {$github_folder}/{$game_folder_name} && ./{$script_name} stop");
		}

		//update estatus
		$vm->deploy_status = "Applying Update..";
		$vm->update();

		//apply update
		$ssh = new Net_SSH2($services[0]['ip'], $services[0]['ssh_port']);
		//use ssh password
		if(isset($services[0]['ssh_password']) && !empty($services[0]['ssh_password'])){
			if(!$ssh->login($services[0]['ssh_username'], $services[0]['ssh_password'])){
				$this->res->message = "SSH login with pw failed : " . $services[0]['ip'] . "@" . $services[0]['ssh_username'];
				return;
			}
		} else if(isset($services[0]['ssh_key']) && !empty($services[0]['ssh_key'])){ //use ssh key
			include_once('Crypt/RSA.php');
			$key = new Crypt_RSA();
			$key->loadKey($services[0]['ssh_key']);
			if (!$ssh->login($services[0]['ssh_username'], $key)) {
			    $this->res->message = "SSH login with key failed : " . $services[0]['ip'] . "@" . $services['ssh_username'];
				return;
			}
		}
		$ssh->setTimeout(0);

		//run ssh command
		$ssh->exec("cd {$github_folder}/{$game_folder_name} && ./{$script_name} update");

		//update estatus
		$vm->deploy_status = "Update Complete!";
		$vm->update();

		return true;
    }
}

?>
