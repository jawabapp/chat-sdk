<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 12/2/18
 * Time: 6:51 PM
 */

namespace ChatSDK\Support;

use ChatSDK\Facades\Client;

class Topic
{
    private $topic;

    /**
     * @return Topic
     * @throws \Exception
     */
    public function getTopic()
    {
        return $this->client()->isValid($this->topic);
    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @param $ref_id
     * @throws \Exception
     */
    public function generateTopic($ref_id)
    {
        $this->client()->setTopic(Client::get('topic_prefix') . '/' . uniqid($ref_id . '_'));
    }

    /**
     * @param $topic
     * @return mixed
     * @throws \Exception
     */
    private function isValid($topic) {
        if(strpos($topic, Client::get('topic_prefix')) === 0) {
            return $topic;
        }

        throw new \Exception('invalid topic prefix');
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function client() {
        if (!Client::hasFacadeRoot()) {
            Client::make();
        }
        return $this;
    }


}