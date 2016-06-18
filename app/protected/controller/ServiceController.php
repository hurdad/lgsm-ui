<?php

class ServiceController extends DooController {

    public function beforeRun($resource, $action)
    {
    	//include phpseclib
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(Doo::conf()->SITE_PATH)  . '/include/phpseclib');
		include('Net/SSH2.php');

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

	function startall() {

		//query db
		$vbox_id = $this->params['id'];

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

		//loop sevices
		foreach(Doo::db()->fetchAll($sql) as $service){

			$ssh = new Net_SSH2($service['ip'], $service['ssh_port']);
			//use ssh password
			if(isset($service['ssh_password'])) {
				if(!$ssh->login($service['ssh_username'], $service['ssh_password'])){
					$this->res->message = "SSH login with pw failed : " . $service['ip'] . "@" . $service['ssh_username'];
					return;
				}
			} else if(isset($service['ssh_key'])){ //use ssh key
				$key = new Crypt_RSA();
				$key->loadKey($service['ssh_key']);
				if (!$ssh->login('username', $key)) {
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
			$ssh->exec("cd {$github_folder}/{$game_folder_name} && ./{$script_name} start");
		}

		//sucess if no errors
		$this->res->success = true;
		
	}

	function stopall() {
	
		//query db
		$vbox_id = $this->params['id'];

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

		//loop sevices
		foreach(Doo::db()->fetchAll($sql) as $service){

			$ssh = new Net_SSH2($service['ip'], $service['ssh_port']);
			//use ssh password
			if(isset($service['ssh_password'])) {
				if(!$ssh->login($service['ssh_username'], $service['ssh_password'])){
					$this->res->message = "SSH login with pw failed : " . $service['ip'] . "@" . $service['ssh_username'];
					return;
				}
			} else if(isset($service['ssh_key'])){ //use ssh key
				$key = new Crypt_RSA();
				$key->loadKey($service['ssh_key']);
				if (!$ssh->login('username', $key)) {
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
			$ssh->exec("cd {$github_folder}/{$game_folder_name} && ./{$script_name} stop");
		}

		//sucess if no errors
		$this->res->success = true;
	}

	function update() {
		
		//get vbox id
		$vbox_id = $this->params['id'];

        //get gearman job server
        $gearman_servers = array();
        foreach(Doo::db()->find('GearmanJobServers') as $s){
        	$gearman_servers[] = $s->hostname . ":" . $s->port;
        }

		 //send update request for vbox id
        set_include_path(get_include_path() . PATH_SEPARATOR .dirname(Doo::conf()->SITE_PATH)  . '/include/Net_Gearman');
		require_once 'Net/Gearman/Client.php';
		$client = new Net_Gearman_Client($gearman_servers);
		$client->update(array(
		    'vbox_id' => $vbox_id
		));

		//sucess if no errors
		$this->res->success = true;
	}

}
?>