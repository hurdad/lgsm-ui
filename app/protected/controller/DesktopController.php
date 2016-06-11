<?php

use xPaw\SourceQuery\SourceQuery;

class DesktopController extends DooController {

	function admin(){

		//get games
		$sql_games = "SELECT 
		    id, full_name, folder_name, hidden
		FROM
		    games
		ORDER BY full_name";
		$games = Doo::db()->fetchAll($sql_games);

		//get services
		$sql_services = "SELECT 
		    services.id, games.full_name, script_name, port
		FROM
		    services
		        JOIN
		    games ON services.games_id = games.id
		ORDER BY full_name";
		$services = array();
		foreach(Doo::db()->fetchAll($sql_services) as $s){
			$services[$s['full_name']][]= $s;
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
		    id, name, glibc_version, architecture, username, ssh_password, ssh_key
		FROM
		    base_images";
		$base_images = Doo::db()->fetchAll($sql_images);

		$sql_query_engines = "SELECT 
		    id, name, launch_uri
		FROM
		    query_engines";
		$query_engines = Doo::db()->fetchAll($sql_query_engines);

		//render view
        $this->renderc('admin', array('games' => $games, 'services' => $services, 'vbox_soap_endpoints' => $vbox_soap_endpoints, 'gits' => $gits, 'base_images' => $base_images, 'query_engines' => $query_engines));
	}
	
	function deploy(){

		require_once(dirname(Doo::conf()->SITE_PATH).'/phpvirtualbox/endpoints/lib/config.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/phpvirtualbox/endpoints/lib/utils.php');
		require_once(dirname(Doo::conf()->SITE_PATH).'/phpvirtualbox/endpoints/lib/vboxconnector.php');

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
			$games[$game['full_name']] = array();

			//get virtualboxes for each game
			$sql_vboxes = "SELECT 
			    virtualboxes.id, url, vbox_soap_endpoints.username, password, hostname, ip
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
				    //echo 'Caught exception: ',  $e->getMessage(), "\n";
				}

				//get services for vbox
				$sql_services ="SELECT port
					FROM
					    services
					WHERE
					    virtualboxes_id = " . $vbox['id'] . " AND games_id = " . $game['id'];

				$cnt = 0;
				foreach(Doo::db()->fetchAll($sql_services) as $service) {
					$cnt++;
				}

				$arr['cnt'] = $cnt;
				$arr['data'] = $vbox;
				$games[$game['full_name']][] = $arr; 
			}
		}

		//render view
        $this->renderc('deploy', array('games' => $games));
	}

	function status(){

		require dirname(Doo::conf()->SITE_PATH) . "/PHP-Source-Query/SourceQuery/bootstrap.php";
	
		$sql = "SELECT 
			    full_name, ip, port, query_engines.name AS query_engine_name
			FROM
			    virtualboxes
			        JOIN
			    games ON virtualboxes.games_id = games.id
			        JOIN
			    services ON virtualboxes.id = services.virtualboxes_id
			        JOIN
			    query_engines ON games.query_engines_id = query_engines.id
			WHERE
			    hidden = 0
			ORDER BY full_name, port";

		//query database
		$servers = array();
		foreach(Doo::db()->fetchAll($sql) as $service){

			$arr = array();
			if($service['query_engine_name'] == "SOURCE"){
				$sq = new SourceQuery( );
				$sq->Connect($service['ip'], $service['port'], 1, SourceQuery :: SOURCE);
				$arr['query'] = $sq->GetInfo();
				$sq->Disconnect();
			} else 	if($service['query_engine_name'] == "GOLDSOURCE"){
				$sq = new SourceQuery( );
				$sq->Connect($service['ip'], $service['port'], 1, SourceQuery :: GOLDSOURCE);
				$arr['query'] = $sq->GetInfo();
				$sq->Disconnect();
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