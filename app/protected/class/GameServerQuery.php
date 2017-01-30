<?php 
/*  
 * Game Sever Query 
 * Copyright (c) 2014, Alex Duchesne <alex@alexou.net>. 
 * 
 * Licensed under the ISC License: 
 *     http://opensource.org/licenses/ISC 
 */ 
  

class GameServerQuery { 

    private static function getByte(&$chaine) 
    { 
        $data = ord($chaine{0}); 
        $chaine = substr($chaine, 1); 

        return $data; 
    } 

     
    private static function getString(&$chaine, $chr = "\x00") 
    { 
        $data = strstr($chaine, $chr, true); 
        $chaine = substr($chaine, strlen($data) + 1); 
         
        return $data; 
    } 
     

    private static function getLong(&$chaine) 
    { 
        $long = unpack('l', substr($chaine, 0, 4)); 
        $chaine = substr($chaine, 4); 
         
        return $long[1]; 
    } 
     
     
    private static function getInteger16(&$chaine) 
    { 
        $int = unpack('Sint', substr($chaine, 0, 2)); 
        $chaine = substr($chaine, 2); 
         
        return $int['int']; 
    } 
     
     
    private static function getInteger32(&$chaine) 
    { 
        $int = unpack('Sint', substr($chaine, 0, 4)); 
        $chaine = substr($chaine, 4); 
         
        return $int['int']; 
    } 

     
    private static function getInteger8(&$chaine) 
    { 
        $chaine = substr($chaine, 1); 
        return ord($chaine{0}); 
    } 
     
     
    private static function getVarInt(&$socket) 
    { 
        $int = 0; 
        $i = 0; 
         
        do { 
            $byte = @fgetc($socket); 
             
            if($byte === false) { 
                return 0; 
            } 
             
            $byte = ord($byte); 
             
            $int |= ($byte & 0x7F) << $i++ * 7; 
         
        } while ($i < 6 && ($byte & 0x80) == 128); 
         
        return $int; 
    } 
     
     
    private static function ping($host, $port, $command) 
    { 
        $socket = @stream_socket_client('udp://'.$host.':'.$port, $errno, $errstr, 500); 
        if (!$errno && $socket) { 
            stream_set_timeout($socket, 2); 
            fwrite($socket, $command); 
            $buffer = @fread($socket, 1500); 
            fclose($socket); 
            return $buffer; 
        } 
        return false; 
    } 

     
         
    public static function __callStatic($call, $args) 
    { 
        $args[] = $call; 
        return call_user_func_array('self::query', $args); 
    } 
     
     
    public static function query ($host, $port, $type) 
    { 
        if (method_exists('GameServerQuery', 'query'.$type)) { 
            return call_user_func('self::query'.$type, $host, $port); 
        } else { 
            throw new exception('Typpe de serveur invalide!'); 
        } 
    } 
     
     
    public static function isOnline ($host, $port, $type) 
    { 
        if ($type == 'minecraft') { // No need for the full ping 
                return @fclose (@fsockopen ( $host , $port , $err , $errstr , 2 )); 
        } 
         
        if (method_exists('GameServerQuery', 'query'.$type)) { 
            return self::{'query'.$type}($host , $port); 
        } 
         
        return @fclose (@fsockopen ( $host , $port , $err , $errstr , 2 )); 
    } 
     
     
    public static function querySource($host, $port) 
    { 
        if ($reponse = self::ping($host, $port, "\xFF\xFF\xFF\xFFTSource Engine Query")) { 
             
            $cs15 = false; 
            $info = array(); 
            $header = substr($reponse, 0, 5); 
             
            if ($header !== "\xFF\xFF\xFF\xFF\x6D" && $header !== "\xFF\xFF\xFF\xFF\x49") { 
                return false; 
            } 
         
            if ($reponse[4] == 'm') { //Le moteur CS1.5 
                $reponse = substr($reponse, 5); 
                $reponse = strstr($reponse, chr(0)); 
                $cs15 = true; 
            } 
            elseif ($reponse[4] == 'I') { // Le moteur source 
                $reponse = substr($reponse, 5); 
            } 
            else { 
                return false; 
            } 
             
            $info['version']        = self::getByte($reponse); 
            $info['name']            = trim(self::getString($reponse)); 
            $info['mapname']        = self::getString($reponse); 
            $info['gamedir']        = self::getString($reponse); 
            $info['gamedesc']        = self::getString($reponse); 
            if (!$cs15) $reponse = substr($reponse, 2); 
            $info['numplayers']    = self::getByte($reponse); 
            $info['maxplayers']    = self::getByte($reponse); 
            $info['bot']            = self::getByte($reponse); 
            $info['dedicated']    = (chr(self::getByte($reponse)) === 'd') ? 1 : 0; 
            $info['os']                = chr(self::getByte($reponse)); 
            $info['password']        = self::getByte($reponse); 
            $info['secure']        = self::getByte($reponse); 
             
            return $info; 
        } 
         
        return false; 
    } 

     
    public static function queryGS2($host, $port) 
    { 
        $reponse = self::ping($host, $port, "\xFE\xFD\x00PiNG\xFF\x00\x00"); 
         
        if ($reponse === false || substr($reponse, 0, 5) !== "\x00PiNG") { 
            return false; 
        } 
         
        $info = array(); 
        $reponse=substr($reponse, 5); 
         
        while($reponse != '') { 
            $info[self::getString($reponse)] = self::getString($reponse); 
        } 
         
        return $info; 
    } 

     
    public static function queryQuake3($host, $port) 
    { 
        $reponse = self::ping($host, $port, "\xFF\xFF\xFF\xFFgetstatus\x00"); 
         
        if ($reponse === false || substr($reponse, 0, 5) !== "\xFF\xFF\xFF\xFFs") { 
            return false; 
        } 
         
        $reponse = substr($reponse, strpos($reponse, chr(10))+2); 
             
        $info = array(); 
        $joueurs = substr($reponse, strpos($reponse,chr(10))+2); 
        $reponse = substr($reponse, 0, strpos($reponse, chr(10))); 
         
        while($reponse != ''){ 
            $info[self::getString($reponse, '\\')] = self::getString($reponse, '\\'); 
        } 
         
        if (!empty($joueurs)) { 
            $info['players'] = array(); 
            while ($joueurs != ''){ 
                $details = self::getString($joueurs, chr(10)); 
                $info['players'][] = array('frag' => self::getString($details, ' '), 
                                                         'ping' => self::getString($details, ' '), 
                                                         'name' => $details); 
            } 
        } 
        return $info; 
    } 

     
    public static function queryDoom3($host, $port) 
    { 
        $reponse = self::ping($host, $port, "\xFF\xFFgetInfo\x00PiNGPoNG\x00"); 
         
        if ($reponse === false || substr($reponse, 0, 5) !== "\xFF\xFFinf") { 
            return false; 
        } 
         
        $reponse = substr($reponse, strpos($reponse, chr(0).chr(0))+2); 
         
        while($reponse != '') { 
            $variable = self::getString($reponse); 
            $valeur = self::getString($reponse); 
            if (empty($variable) && empty($valeur)) break; 
            $info[$variable] = $valeur; 
        } 
         
        $info['players'] = array(); 
        while (self::getInteger8($reponse) != 32 && $reponse != '') { 
            $info['players'][] = array('ping' => self::getInteger16($reponse),  
                                                     'frag' => self::getInteger32($reponse),  
                                                     'name' => htmlentities(self::getString($reponse))); 
            $reponse = substr($reponse, 1); 
        } 
         
        return $info; 
    } 
     
     
    public static function queryMinecraft($host, $port) 
    { 
        $socket = @fsockopen( $host, $port, $errno, $errstr, 2); 
         
        if (!$socket) { 
            return false; 
        } 
         
        $header = "\x00"; // Packet ID 
        $header .= "\x04"; // Protocol Version 
        $header .= pack('c', strlen($host)) . $host; // server host + its length 
        $header .= pack('n', $port); // server port 
        $header .= "\x01"; // status 
         
        $payload = pack('c', strlen($header)) . $header . "\x01\x00"; 
         
        fwrite($socket, $payload); // handshake 
         
        $length = self::getVarInt($socket); 
         
        if($length < 15) { 
            return false; 
        } 
         
        fgetc($socket); // Ping 
         
        $length = self::getVarInt($socket); //payload 
         
        $data = ''; 
         
        while ($length != strlen($data) && $block = fread($socket, $length)) { 
            $data .= $block; 
        } 
         
        if (strlen($data) < $length || $data === false) { 
            return false; //Oh oh... 
        } 
         
        return json_decode($data, true); 
    } 
} 



function parse_minecraft_string ($string) 
{ 
    $colors = array (  
                             1 => '#4c00c8', 
                             2 => '#00d400', 
                             3 => '#00c1c1', 
                             4 => '#be0000', 
                             5 => '#c900c3', 
                             6 => '#d2ac00', 
                             7 => '#bcbcbc', 
                             8 => '#414141', 
                             9 => '#7400ff', 
                             0 => '#000000', 
                            'a' => '#00ff00', 
                            'b' => '#34ffff', 
                            'c' => '#ff0000', 
                            'd' => '#ff00ff', 
                            'e' => '#eeff00', 
                            'f' => '#ffffff', 
                            'r' => '#ffffff', 
                            ); 
     
    $format = array(     
                            'l' => 'font-weight:bold;', 
                            'm' => 'text-decoration: line-through', 
                            'n' => 'text-decoration: underline', 
                            'o' => 'font-style:italic', 
                            'r' => '', 
                            ); 
     
    foreach($colors as $a => $color) { 
        $search[] = chr(194) . chr(167) . $a; 
        $replace[] = '</span><span style="color: ' . $color . ';">'; 
    } 
     
    foreach($format as $a => $style) { 
        $search[] = chr(194) . chr(167) . $a; 
        $replace[] = ''; 
    } 
     
    return '<span>' . str_replace($search, $replace, $string) . '</span>'; 
}