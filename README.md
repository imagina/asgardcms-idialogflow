# Google Cloud Dialogflow for AsgardCms (PHP)

### Install
```sh
composer require google/cloud-dialogflow
```

Add .json file to .env with the service account credential downloaded from the Google cloud.
```
GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/file.json
```

##### Services
```php
Modules\Idialogflow\Services\IntentService.php
```
Method´s Service
* getIntents
* getIntent
* createIntent
* updateIntent
* DeleteIntent

End Points Api

Get all intents, add in filter the $projectId
```
GET: /api/idialogflow/intents
```

Get all Intents
```
GET: /api/idialogflow/intents/:intentId
```

Create an Intent
```
POST: /api/idialogflow/intents
```

Update an Intent
```
PUT: /api/idialogflow/intents/:intentId
```

Delete an Intent
```
DELETE: /api/idialogflow/intents/:intentId
```
