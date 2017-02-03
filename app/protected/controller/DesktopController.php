<?php

use xPaw\SourceQuery\SourceQuery;

class DesktopController extends DooController {

	function admin(){

		//get games
		$sql_games = "SELECT 
		    id, full_name, folder_name, glibc_version_min, hidden
		FROM
		    games
		ORDER BY full_name";
		$games = Doo::db()->fetchAll($sql_games);

		//get services
		$sql_services = "SELECT 
		    games.id as games_id , services.id as id, games.full_name, script_name, query_port, port
		FROM
		    services
		        JOIN
		    games ON services.games_id = games.id
		ORDER BY full_name";
		$services = array();
		foreach(Doo::db()->fetchAll($sql_services) as $s){
			$services[$s['full_name'] . "|" . $s['games_id']][]= $s;
		}

		//vbox soap endpoints
		$sql_vbox = "SELECT 
		    id, url, username, password, machine_folder
		FROM
		    vbox_soap_endpoints";
		$vbox_soap_endpoints = Doo::db()->fetchAll($sql_vbox);

		//git repos
		$sql_git = "SELECT 
		    id, url, branch, username, 'key'
		FROM
		    github";
		$gits = Doo::db()->fetchAll($sql_git);

		//base vbox images
		$sql_images = "SELECT 
		    id, name, glibc_version, architecture, ssh_username, ssh_password, ssh_key
		FROM
		    base_images";
		$base_images = Doo::db()->fetchAll($sql_images);

		//query images
		$sql_query_engines = "SELECT 
		    id, name, launch_uri
		FROM
		    query_engines";
		$query_engines = Doo::db()->fetchAll($sql_query_engines);

		//query gearman job servers 
		$sql_gearman_job_servers = "SELECT 
		    id, hostname, port, enabled
		FROM
		    gearman_job_servers";
		$query_gearman_job_servers = Doo::db()->fetchAll($sql_gearman_job_servers);

		//query gearman functions 
		$sql_gearman_functions = "SELECT 
		    id, function_name, worker_count, enabled
		FROM
		    gearman_functions;";
		$query_gearman_functions = Doo::db()->fetchAll($sql_gearman_functions);

	//	set_include_path(get_include_path() . PATH_SEPARATOR .dirname(Doo::conf()->SITE_PATH)  . '/include/Net_Gearman');
//		require_once 'Net/Gearman/Manager.php';
	//	$manager = new Net_Gearman_Manager("localhost:4730");
		//var_dump($manager->status());
		//return;

		//render view
        $this->renderc('admin', array('games' => $games, 'services' => $services, 'vbox_soap_endpoints' => $vbox_soap_endpoints, 'gits' => $gits, 'base_images' => $base_images, 'query_engines' => $query_engines, 'gearman_job_servers' => $query_gearman_job_servers, 'gearman_functions' => $query_gearman_functions));
	}
	
	function deploy(){

		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/config.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/utils.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/include/phpvirtualbox/endpoints/lib/vboxconnector.php');

		global $_SESSION;
	
		//get games
		$sql_games = "SELECT 
			    id, full_name
			FROM
			    games
			WHERE
			    hidden = 0
			ORDER BY full_name";

		$games = array();
		foreach(Doo::db()->fetchAll($sql_games) as $game){

			//init
			$games_key = $game['full_name'] . "|" . $game['id'];
			$games[$games_key] = array();

			//get virtualboxes for each game
			$sql_vboxes = "SELECT 
			    virtualboxes.id, url, vbox_soap_endpoints.username, password, hostname, ip, ssh_username, deploy_status
			FROM
			    virtualboxes
			        JOIN
			    vbox_soap_endpoints ON virtualboxes.vbox_soap_endpoints_id = vbox_soap_endpoints.id
			WHERE
			    games_id = " . $game['id'];

			//loop virtualboxes
			foreach(Doo::db()->fetchAll($sql_vboxes) as $vbox) {
				
				//vbox soap config
				$conf = new phpVBoxConfigClass;
				$conf->location = $vbox['url'];
				$conf->username = $vbox['username'];
				$conf->password = $vbox['password'];   
				
				//vbox soap query
				$arr = array();
				try{
					$vbox_conn = new vboxconnector(false, $conf);
					$arr['query'] = $vbox_conn->remote_vboxGetMachines(array('vm'=>$vbox['hostname']))[0];
				} catch (Exception $e) {
					//TODO
				}

				//get assiged services for vbox
				$sql_services ="SELECT 
				    port
				FROM
				    assigned_services
				        JOIN
				    services ON assigned_services.services_id = services.id
				WHERE
				    virtualboxes_id = " . $vbox['id'] . " AND games_id = " . $game['id'];

				$cnt = 0;
				foreach(Doo::db()->fetchAll($sql_services) as $service) {
					$cnt++;
				}

				$arr['cnt'] = $cnt;
				$arr['data'] = $vbox;
				$games[$games_key][] = $arr; 
			}
		}

		//vbox soap endpoints
		$sql_vbox = "SELECT 
		    id, url, username, password
		FROM
		    vbox_soap_endpoints";
		$vbox_soap_endpoints = Doo::db()->fetchAll($sql_vbox);

		//git repos
		$sql_git = "SELECT 
		    id, url, branch, username, 'key'
		FROM
		    github";
		$gits = Doo::db()->fetchAll($sql_git);

		//base vbox images
		$sql_images = "SELECT 
		    id, name, glibc_version, architecture, ssh_username, ssh_password, ssh_key
		FROM
		    base_images";
		$base_images = Doo::db()->fetchAll($sql_images);

		//render view
        $this->renderc('deploy', array('games' => $games, 'vbox_soap_endpoints' => $vbox_soap_endpoints, 'gits' => $gits, 'base_images' => $base_images));
	}

	function status(){

		require dirname(Doo::conf()->SITE_PATH) . "/include/PHP-Source-Query/SourceQuery/bootstrap.php";
	
		$sql = "SELECT 
		    full_name, ip, port, query_port, query_engines.name AS query_engine_name, launch_uri
		FROM
		    virtualboxes
		        JOIN
		    games ON virtualboxes.games_id = games.id
		        JOIN
		    assigned_services ON virtualboxes.id = assigned_services.virtualboxes_id
		        JOIN
		    services ON services.id = assigned_services.services_id
		        JOIN
		    query_engines ON games.query_engines_id = query_engines.id
		WHERE
		    hidden = 0
		ORDER BY full_name , port";

		//query database
		$servers = array();
		foreach(Doo::db()->fetchAll($sql) as $service){

			//var_dump($service);

			$arr = array();
			if($service['query_engine_name'] == "SOURCE"){
				try {
					$sq = new SourceQuery( );
					$sq->Connect($service['ip'], !empty($service['query_port']) ? $service['query_port'] : $service['port'] , 1, SourceQuery :: SOURCE);
					$arr['query'] = $sq->GetInfo();
					$sq->Disconnect();
				} catch (Exception $e) {
    				//echo 'Caught exception: ',  $e->getMessage(), "\n";
    			}
			} else if($service['query_engine_name'] == "GOLDSOURCE"){
				try {
					$sq = new SourceQuery( );
					$sq->Connect($service['ip'], $service['port'], 1, SourceQuery :: GOLDSOURCE);
					$arr['query'] = $sq->GetInfo();
					$sq->Disconnect();
				} catch (Exception $e) {
    				//echo 'Caught exception: ',  $e->getMessage(), "\n";
    			}
			} else if($service['query_engine_name'] == "gamespy1"){
				$gs1 = new gamespy1();
				$result = $gs1->query($service['ip'], $service['query_port'], 500);

				//extract query data
				$arr['query']['HostName'] = $gs1->getiteminfo('hostname', $result);
				$arr['query']['Map'] = $gs1->getiteminfo('mapname', $result);
				$arr['query']['Players'] = $gs1->getiteminfo('numplayers', $result);
				$arr['query']['MaxPlayers'] =  $gs1->getiteminfo('maxplayers', $result);
			} else if($service['query_engine_name'] == "quake3"){
				$result = GameServerQuery::Quake3($service['ip'], $service['port']); 
				
				//extract query data
				$arr['query']['HostName'] = $result['sv_hostname'];
				$arr['query']['Map'] = $result['mapname'];
				$arr['query']['Players'] = isset($result['players']) ? count($result['players']) : '0';
				$arr['query']['MaxPlayers'] = $result['sv_maxclients'];
			}

			$arr['data'] = $service;
			$servers[$service['full_name']][]= $arr;
		}

		//calculate user counts
		$counts = array();
		foreach($servers as $game => $services){
			$playercnt = 0;
			$maxcnt = 0;
			foreach($services as $s){
				if(isset($s['query'])){
					$playercnt += intval($s['query']['Players']);
					$maxcnt += intval($s['query']['MaxPlayers']);
				}
			}

			$counts[$game]['Players'] = $playercnt;
			$counts[$game]['MaxPlayers'] = $maxcnt;
		}

		//render view
        $this->renderc('status', array('servers' => $servers, 'counts' => $counts));
	}

}

?>