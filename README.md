## Solver Database Table

#### AppTable
```
id, name, label, icon, app_token, service_token, service_endpoint, topics_endpoint
```

#### UserSolverTable
```
jawab_chat_user_id, jawab_chat_account_id, service_user_id, service_name
1, 1, user, 1002, jawabkom
1, 1, user, 1225, jawabmehan
```

#### TopicSolverTable
```
jawab_chat_topic, service_object_id, service_name
1_22333, 3322, jawabkom
1_22332, 211, jawabmehan
```

## User Asking Question Flow

- After clicking plus button we show the service list to user 
- Mobile device connect to JawabChat API to get the services.

```
[
    {
        "id": "",
        "name": "jawabkom",
        "label": "Jawabmehan",
        "icon": "",
        "categoy_endpoint": "http://api.jawabkom.com/categories"
    },
    {
        "id": "",
        "name": "jawabmehan",
        "label": "Jawabmehan",
        "icon": "",
        "categoy_endpoint": "http://api.jawabmehan.com/categories"
    }
]
```

- Mobile fetch the categories to display the categories, which are coming from `categoy_endpoint`, to users.

```
{
    "id": "1",
    "name": "Doctor",
    "image": "?"
}
```

- The user select the category. The mobile send a request to JawabChatAPI to get the participants.

``` 
{
    "account_id": "sender-account-id",
    "app_id": "app-id",
    "category_id": "service-category-id",
    "category_name": "Doctor",
    "category_image": "http://"
}
```

- JawabChat application will request a participant from SDK if not ready JawabChat create a user for this category. Jawabkom return like below:

```
{
    ???????? - We need some solution on jawabkom side. We guess something this.
    "id": "",
    "name": "",
    "avatar": "",
    "uuid" : "jawabkom-user-id",
}
```
- JawabChat application will send the user information to Jawabkom services. Because Jawabkom services can listen this user messages over MQTT, by the way.
- Jawabchat application will create a group chat with following datas:
```
{
    "account_id": "creator account id", // coming from mobile side
    "topic": "String {senderAccountId + _ + timestamp()}" // Generate the application,
    "name": "{{jawabkom expert name or category_name}} John Red - Doctor",
    "avatar": "{{jawabkom expert image or category_image}}",
    "participants": [
        {"account_id": "coming from participant request"}
    ]
}
```
- Then JawabChat application return a participants list and group information to mobile side.
- Mobile will create a group chat page and show the user. And subscribe the topic.
- The user start typing for question. Then mobile side send the message to MQTT.
```
"grp/{chat_id}" qos1
{
    "chat_id": "String (grp/app-{id}/{creatorAccountId}_{now})",
    "content": String (base64 if image) (location: encoded string) (video: url)(audio: url)
    "content_type": String {text, image, video, audio, location}
    "sender_id": Int
    "account_sender_id": Int,
    "type": "message"
}
```
- After sending messages, the user start waiting for answers.

## Jawabkom Service Side

- Jawabkom receive user information over SDK. Save the information to SDK side to use subscribe MQTT.
 (Note: We need to create a record for UserSolverTable)

- Jawabkom SDK receive a message from MQTT. 

```
{
    "chat_id": "1_1222333",
    "content": "foo bar"
    "content_type": "text"
    "sender_id": 1
    "account_sender_id": 1,
    "type": "message"
}
```
- We need to check TopicSolverTable first if the question already created
  - if created 
    - SDK will resolve the object_id and create an answer for the solved question.
  - if not create
    - SDK need to solve over jawabkom side to create a question for sender_id and account_sender_id
    - SDK create question for this message
    - SDK need to create a record in the TopicSolverTable with chat_id (grp/app-{id}/{creatorAccountId}_{now})

- Some experts reply the messages.
  - Jawabkom send the message to SDK 
```
{
    "object_id": "",
    "content": "",
    "content_type": "",
    "sender_id": ""
}
```
- After SDK getting the message
  - SDK need to solve mqtt_sender_id and mqtt_sender_account_id for the mqtt message from sender_id.
  - SDK need to solve the topic for the mqtt from object_id. 
- SDK will publish the message to topic over mqtt.