<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/26/18
 * Time: 8:49 AM
 */

namespace ChatSDK\Support;


use Bluerhinos\phpMQTT;

class MyPhpMQTT extends phpMQTT
{
    protected $socket; 			/* holds the socket	*/
    protected $msgid = 1;		/* counter for message id */

    /* subscribe: subscribes to topics */
    function subscribe($topics, $qos = 0){
        $i = 0;
        $buffer = "";
        $id = $this->msgid;
        $buffer .= chr($id >> 8);  $i++;
        $buffer .= chr($id % 256);  $i++;

        foreach($topics as $key => $topic){
            $buffer .= $this->strwritestring($key,$i);
            $buffer .= chr($topic["qos"]);  $i++;
            $this->topics[$key] = $topic;
        }

        // fix on vernemq broker recursive loop on connect change from 0x80 to 0x82
        $cmd = 0x82;
        //$qos
        $cmd +=	($qos << 1);


        $head = chr($cmd);
        $head .= chr($i);

        fwrite($this->socket, $head, 2);
        fwrite($this->socket, $buffer, $i);
        $string = $this->read(2);

        $bytes = ord(substr($string,1,1));
        $string = $this->read($bytes);
    }
}