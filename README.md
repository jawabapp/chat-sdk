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
