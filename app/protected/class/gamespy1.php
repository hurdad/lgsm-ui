<?php

class gamespy1 {
  
    public function query($ip, $query_port, $timeout) {

        //Open UDP socket to server
        $sock = fsockopen("udp://" . $ip, $query_port, $errno, $errstr, $timeout);

        //Check if we have a socket open, if not, display error message
        if (!$sock) {
            return array();
        }

        //      fputs($sock,"\\status\\\player_property\Health\\\game_property\ElapsedTime\\\game_property\RemainingTime\\");
        fputs($sock,"\\status\\");

        $gotfinal = False;
        $data = "";

        //Set starttime, for possible loop expiration, so the server doesn't get too much work.
        $starttime = Time();

        //Loop until final packet has been received.
        while(!($gotfinal == True || feof($sock))) {

            //Get data
            if(($buf = fgetc($sock)) == FALSE) {
                usleep(100); // wait for additional data? :S whatever
            }

            //Add to databuffer
            $data .= $buf;

            //Check if final item (queryid) has been received
            if (strpos($data,"final\\") != False) {
                $gotfinal = True;
            }

            //Protect webserver against massive loop.
            if ((Time() - $starttime) > 5) {
                echo "Data receiving took too long. Cancelled.<P>";
                $gotfinal = True;
            }

        }

        //Close socket
        fclose ($sock);

        //Split chunks by \
        return split('[\]', $data);
    }

    public function getiteminfo($itemname, $itemchunks) {

      $retval = "N/A";
      for ($i = 0; $i < count($itemchunks); $i++) {
         //Found this item

         if (strcasecmp($itemchunks[$i], $itemname) == 0) {
            $retval = $itemchunks[$i+1];

         }
      }

      //Return value
      return  $retval;
    }
}
