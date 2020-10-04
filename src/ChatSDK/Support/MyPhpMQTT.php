<?php
/**
 * https://github.com/bluerhinos/phpMQTT/blob/master/phpMQTT.php
 *
 * coped by Qanah to make it compatible with php 5.5
 */

namespace ChatSDK\Support;

use Bluerhinos\phpMQTT;

class MyPhpMQTT extends phpMQTT
{
    public function reconnect() {

        if (feof($this->socket)) {
            $this->_debugMessage('eof receive going to reconnect for good measure');
            fclose($this->socket);
            $this->connect_auto(false);
            if (count($this->topics)) {
                $this->subscribe($this->topics);
            }
        }

    }
}
