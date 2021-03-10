<?php


namespace ChatSDK\Analytics;


class Events
{
    private $campaign_id;
    private $events = [];

    public function __construct($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    public function logEvent($event_name, $params = []) {
        if($event_name && is_array($params)) {
            $this->events[$event_name] = $params;
        }
    }

    private function scriptTag($arg) {

        $src = !empty($arg['src']) ? " src='{$arg['src']}'" : '';
        $content = $arg['content'] ?? '';

        return "<script{$src}>{$content}</script>" . PHP_EOL;
    }

    private function scriptTagWithSrc($src) {
        return $this->scriptTag(['src' => $src]);
    }

    private function scriptTagWithContent($content) {
        return $this->scriptTag(['content' => $content]);
    }

    private function scriptTagWithEvent($event) {

        $event_name = $event['name'] ?? '';

        if(empty($event_name)) {
            return '';
        }

        $jsEvent = "'{$event_name}'";

        $event_params = $event['params'] ?? [];

        if($event_params) {
            $jsEvent .= ", ";
            $jsEvent .= json_encode($event_params);
        }

        return $this->scriptTagWithContent("analytics.logEvent({$jsEvent});");
    }

    private function getFirebaseConfiguration() {
        return <<<INIT

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyCsDuCMzWQuTpt6rSe6ZnvP-aImom4S9Pc",
        authDomain: "jawabchat-30d89.firebaseapp.com",
        databaseURL: "https://jawabchat-30d89.firebaseio.com",
        projectId: "jawabchat-30d89",
        storageBucket: "jawabchat-30d89.appspot.com",
        messagingSenderId: "23864955066",
        appId: "1:23864955066:web:6fdd2e8e47a140c09f3283",
        measurementId: "G-PF8BX7Y65M"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const analytics = firebase.analytics();

INIT;
    }

    public function render($return = null) {

        if($this->campaign_id) {
            $html = $this->scriptTagWithSrc('https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js');
            $html .= $this->scriptTagWithSrc('https://www.gstatic.com/firebasejs/8.2.7/firebase-analytics.js');
            $html .= $this->scriptTagWithContent($this->getFirebaseConfiguration());

            foreach ($this->events as $event_name => $params) {
                $html .= $this->scriptTagWithEvent([
                    'name' => $event_name,
                    'params' => array_merge($params, [
                        'campaign_id' => $this->campaign_id
                    ]),
                ]);
            }
        } else {
            $html = "Error no campaign id";
        }

        if($return) {
            return $html;
        }

        echo $html;

    }

}
