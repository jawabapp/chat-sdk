# JawabChat App SDK

JawabChat is a chat application that you can integrate to your services easily to gain your structure a chat feature. This library will help you to integrate chat feature easily.

## Installation

you can use composer directly to install this library to your application:

```
composer require jawabapp/chat-sdk:dev-master
```

## Your Responsibilities

Before using JawabChat application as service, your service needs to provide some features.

#### Service Token

We are expecting a service token as a string to be able to reach your services. We will send this token in the header as "Accept-Token" field to reach your endpoints.

#### User API

We need a user API to get your user information to our side. We will create a user for you to chat with your clients with this user.
Your user phone will be our primary key of the service. You should send a unique user phone number.
You need to create an API endpoint which is working with a service token, have already provided by you.

We will send you a request to get user information like below:

```
$response = $client->request(
    'POST',
    '----user-enpoint----',                     // You need to provide us an user endpoint
    [
        'headers' => [
            'Accept-Token' => '--service-token--',
            'Accept-Language' => 'en'           // We get this language from topics
        ],
        'form_params' => [
            'topic_id' => 1                     // should be integer
        ]
    ]
);
```

The response of this API should be JSON and the structure should be like below:

```
{
    "user_id": integer,
    "user_name": string,
    "user_phone": string,
    "user_avatar": url
}
```

#### Topic List API

We need a topic endpoint to handle topics of the chats. We will send a request to get the topics while creating a chat for you:

```
$response = $client->request(
    'GET',
    '--- topic-endpoint ---',               // You need to give us a user endpoint
    [
        'headers' => [
            'Accept-Token' => '-- service-token --',
            'Accept-Language' => 'en'       // We will use this for the application language. You need to translate topics' name according to language.
        ]
    ]
);
```

This endpoint should return a JSON response and the structure should be like below :

```
{
    "items": [
        {
            "id": integer,
            "title": string,
            "avatar" => url-as-string,
            "description" => string
        },
        ...
    ]
}
```

## SDK Features

#### Send Channel

You can send a message to your users.

```
\ChatSDK\Facades\Config::make(array(
    'app_token' => 'token.token.token.token',
));

\ChatSDK\Channels\SendChannel::send(
    1,                  // $sender_id => Your message sender id
    'topic/1',          // $topic => Your group id which is coming after creating a UserChannel
    'text',             // $content_type => This can be text, image, etc
    'content'           // $content => Content of the messages as string
);
```

#### Receive Channel

You can receive the response of the user on JawabChat. For this, you only need a token:

```
\ChatSDK\Facades\Config::make(array(
    'app_token' => 'token.token.token.token',
));

\ChatSDK\Channels\ReceiveChannel::receive(function($message, $sender) {
    echo json_encode($message);
    echo json_encode($sender);
},true);
```

Response Message Structure

 - topic            string
 - message_id       string
 - content_type     [image, text]
 - content          url (string)

Response Sender Structure

 - phone            string
 - nickname         string

This feature will work forever. Maybe, you need to work this feature as a service.


## Supervisor
http://supervisord.org/index.html
```
sudo add-apt-repository universe
sudo apt-get update
sudo apt-get install supervisor
```

### Adding a Program
The program configuration files for Supervisor programs are found in the /etc/supervisor/conf.d directory, normally with one program per file and a .conf extension. 
A simple configuration for our script, saved at /etc/supervisor/conf.d/chat_receiver.conf, would look like so:

```
[program:chat_receiver]
command=php tests/receive.php
autostart=true
autorestart=true
user=root
numprocs=1
stderr_logfile=/var/log/chat_receiver.err.log
stdout_logfile=/var/log/chat_receiver.out.log
```
Once our configuration file is created and saved, we can inform Supervisor of our new program through the supervisorctl command. First we tell Supervisor to look for any new or changed program configurations in the /etc/supervisor/conf.d directory with:
```
supervisorctl reread
```
Followed by telling it to enact any changes with:
```
supervisorctl update
```
Any time you make a change to any program configuration file, running the two previous commands will bring the changes into effect. 

Managing Programs

Once our programs are running, there will undoubtedly be a time when we want to stop, restart, or see their status. The supervisorctl program, which we first used above, also has an interactive mode through which we can issue commands to control our programs.

To enter the interactive mode, start supervisorctl with no arguments:
```
$ supervisorctl
chat_receiver                      RUNNING    pid 12614, uptime 1:49:37
supervisor>
```
When started, supervisorctl will initially print the status and uptime of all programs, followed by showing a command prompt. Entering help will reveal all of the available commands that we can use:

supervisor> help
```
default commands (type help ):
=====================================
add    clear  fg        open  quit    remove  restart   start   stop  update
avail  exit   maintail  pid   reload  reread  shutdown  status  tail  version
```

To start in a simple manner, we can start, stop and restart a program with the associated commands followed by the program name:
```
supervisor> stop chat_receiver
chat_receiver: stopped
supervisor> start chat_receiver
chat_receiver: started
supervisor> restart chat_receiver
chat_receiver: stopped
chat_receiver: started
```

Using status we can view again the current execution state of each program after making any changes:
```
supervisor> status
chat_receiver                      STOPPED    Jul 21 01:07 AM
```
Finally, once we are finished, we can exit supervisorctl with Ctrl-C or by entering quit into the prompt:
```
supervisor> quit
```
And that's it! You've mastered the basics of managing persistent programs through Supervisor and extending this to your own programs should be a relatively simple task. If you have any questions or further advice, be sure to leave it in the comments section.