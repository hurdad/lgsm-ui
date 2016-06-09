<?php
// Author: Alex Hurd
// This script will:
// -create a mysql user 'lgsm-ui' if it doesnt exist. 
// -create the following databases if they dont exist : 
// 		-lgsm-ui


//only run via CLI
if(!defined('STDIN') ) exit;

//get auth
writeLine("Please enter local mysql administrator username: [root]");
$username = read_line();
writeLine("password:");
$password = read_line();

//connect via pdo
$dbh = null;
try{
	$dbh = new PDO('mysql:host=127.0.0.1;', $username, $password);
} catch (PDOException $e) {
	writeLine('Connection failed: ' . $e->getMessage());
	return;
}

//Check for lgsm-ui account
$sql  = "SELECT 
		COUNT(*) as count
	FROM
		mysql.user
	WHERE
		user = 'lgsm-ui'";
$stmt = $dbh->query($sql);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
if((int)$res['count'] == 0){
	writeLine("'lgsm-ui' user NOT found! Adding..");
	//add acount
	$sql = "GRANT USAGE ON *.* TO 'lgsm-ui'@'localhost' IDENTIFIED BY PASSWORD '*02238606FB806B897E19764DB06B5DD4C2C1253B';
			GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, LOCK TABLES, EXECUTE ON `lgsm-ui`.* TO 'lgsm-ui'@'localhost';";
	$stmt = $dbh->query($sql);		
}else{
	writeLine("'lgsm-ui' user found!");
}

//Create database + tables
writeLine("Installing 'lgsm-ui' database");
$stmt = $dbh->query("CREATE DATABASE lgsm-ui");
$cmd = "mysql -u {$username} -p{$password} -D lgsm-ui < lgsm-ui.sql";
$result = exec($cmd);
//print if we have error?
if(!empty($result)){
	writeLine($result);
}	

//insert data
writeLine("inserting data 'lgsm-ui' database");
$cmd = "mysql -u {$username} -p{$password} -D lgsm-ui < lgsm-ui-data.sql";
$result = exec($cmd);
//print if we have error?
if(!empty($result)){
	writeLine($result);
}	

//done
writeLine("Done!");

// Exit correctly
exit(0);

function writeLine($msg){
	fwrite(STDOUT, $msg . "\n");
}
function read_line(){
	return trim(fgets(STDIN));
}
?>