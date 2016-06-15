<?php

class ServiceController extends DooController {

    public function beforeRun($resource, $action)
    {
    	//include phpseclib
		set_include_path(dirname(Doo::conf()->SITE_PATH)  . '/include/phpseclib');
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

	function start() {
		echo 'You are visiting '.$_SERVER['REQUEST_URI'];
	}

	function startall() {

		//query db
		$vbox_id = $this->params['id'];

		$sql = "SELECT 
			    ip, virtualboxes.username as ssh_username, ssh_password, virtualboxes.ssh_key, folder_name, script_name, url
			FROM
			    services
			    JOIN virtualboxes
			    ON services.virtualboxes_id = virtualboxes.id
			    JOIN games
			    ON virtualboxes.games_id = games.id
			   	JOIN github
    			ON virtualboxes.github_id = github.id
			WHERE
			    virtualboxes.id = {$vbox_id}";

		//loop sevices
		foreach(Doo::db()->fetchAll($sql) as $service){

			$ssh = new Net_SSH2($service['ip'], 22);
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

			$github_url = $service['url'];
			$github_folder = substr($github_url, strrpos($github_url, '/'), strrpos($github_url, '.')-strrpos($github_url, '/'));
			$game_folder_name = $service['folder_name'];
			$script_name = $service['script_name'];

			$ssh->exec('cd {$github_folder}/{$game_folder_name}/ && ./{$script_name} start');
		}
		
	}

	function stop() {
		echo 'You are visiting '.$_SERVER['REQUEST_URI'];
	}

	function stopall() {
		
		//query db
		$vbox_id = $this->params['id'];

		$sql = "SELECT 
			    ip, virtualboxes.username as ssh_username, ssh_password, virtualboxes.ssh_key, folder_name, script_name, url
			FROM
			    services
			    JOIN virtualboxes
			    ON services.virtualboxes_id = virtualboxes.id
			    JOIN games
			    ON virtualboxes.games_id = games.id
			   	JOIN github
    			ON virtualboxes.github_id = github.id
			WHERE
			    virtualboxes.id = {$vbox_id}";
		
		//loop sevices
		foreach(Doo::db()->fetchAll($sql) as $service){

			$ssh = new Net_SSH2($service['ip'], 22);
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

			$github_url = $service['url'];
			$github_folder = substr($github_url, strrpos($github_url, '/'), strrpos($github_url, '.')-strrpos($github_url, '/'));
			$game_folder_name = $service['folder_name'];
			$script_name = $service['script_name'];

			$ssh->exec('cd {$github_folder}/{$game_folder_name}/ && ./{$script_name} stop');
		}
	}

	function update() {
		echo 'You are visiting '.$_SERVER['REQUEST_URI'];
	}

}
?>