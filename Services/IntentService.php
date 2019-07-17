<?php

namespace Modules\Idialogflow\Services;

// SDK Dialog Flow
use Google\Cloud\Dialogflow\V2\IntentsClient;
use Google\Cloud\Dialogflow\V2\Intent;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase\Part;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase;
use Google\Cloud\Dialogflow\V2\Intent\Message;
use Google\Cloud\Dialogflow\V2\Intent\Message\Text;
use Google\Protobuf\Internal\RepeatedField;
use Google\Cloud\Dialogflow\V2\IntentView;
use Google\Cloud\Dialogflow\V2\Intent_TrainingPhrase_Part;
use Google\Cloud\Dialogflow\V2\Intent_TrainingPhrase;
use Google\Cloud\Dialogflow\V2\Intent_Message_Text;
use Google\Cloud\Dialogflow\V2\Intent_Message;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Modules\Idialogflow\Entities\Bot;

class IntentService
{

  /**
   * Get List Intents
   * @param $request
   * @return Response
   */
  public function getIntents($request)
  {
    // Session Data
    $bot = Bot::find($request->filter->project);
    $credentialsData = json_decode($bot->credentials, true);
    $credentials = array('credentials' => $credentialsData);

    // get intents List
    $intentsClient = new IntentsClient($credentials);
    $parent = $intentsClient->projectAgentName($credentialsData['project_id']);
    $intents = $intentsClient->listIntents($parent, [
      'intentView' => IntentView::INTENT_VIEW_FULL
    ]);
    $response = [];

    foreach ($intents->iterateAllElements() as $intent) {
      $intentData = [];
      $intentData['name'] = $intent->getName();
      $intentData['displayName'] = $intent->getDisplayName();
      $intentData['WebhookState'] = $intent->getWebhookState();
      $intentData['priority'] = $intent->getPriority();
      $intentData['isFallback'] = $intent->getIsFallback();
      $intentData['mlDisabled'] = $intent->getMlDisabled();
      $intentData['action'] = $intent->getAction();
      $intentData['rootFollowupIntentName'] = $intent->getRootFollowupIntentName();
      $intentData['parentFollowupIntentName'] = $intent->getParentFollowupIntentName();
      $intentData['resetContexts'] = $intent->getResetContexts();
      $response[] = $intentData;
    }
    $intentsClient->close();
    return $response;
  }

  /**
   * Get an Intent
   * @param $intentId
   * @return Response
   */
  public function getIntent($intentId, $request)
  {
    // Session Data
    $bot = Bot::find($request->filter->project);
    $credentialsData = json_decode($bot->credentials, true);
    $credentials = array('credentials' => $credentialsData);

    $intentsClient = new IntentsClient($credentials);
    $intent = 'projects/'.$credentialsData['project_id'].'/agent/intents/'.$intentId;
    $intent = $intentsClient->getIntent($intent, [
      'intentView' => IntentView::INTENT_VIEW_FULL
    ]);
    $response['name'] = $intent->getName();
    $response['displayName'] = $intent->getDisplayName();
    $response['WebhookState'] = $intent->getWebhookState();
    $response['priority'] = $intent->getPriority();
    $response['isFallback'] = $intent->getIsFallback();
    $response['mlDisabled'] = $intent->getMlDisabled();
    $response['action'] = $intent->getAction();
    $response['rootFollowupIntentName'] = $intent->getRootFollowupIntentName();
    $response['parentFollowupIntentName'] = $intent->getParentFollowupIntentName();
    $response['resetContexts'] = $intent->getResetContexts();


    // Training phrases
    foreach ($intent->getTrainingPhrases() as $trainingPhrase) {
      foreach ($trainingPhrase->getParts() as $trainingPhrasePart){
        $response['trainingPhraseParts'][] = json_decode($trainingPhrasePart->serializeToJsonString())->text;
      }
    }

    // Messages
    foreach ($intent->getMessages() as $message) {
      $messagesData = [];
      $messagesData = json_decode($message->serializeToJsonString())->text->text;
      $response['messageTexts'] = $messagesData;
    }

    $intentsClient->close();
    return $response;
  }

  /**
   * Create an Intent
   * @param $intentId
   * @return Response
   */
  public function createIntent($data)
  {
    // Session Data
    $bot = Bot::find($data['project_id']);
    $credentialsData = json_decode($bot->credentials, true);
    $credentials = array('credentials' => $credentialsData);

    $intentsClient = new IntentsClient($credentials);

    // prepare parent
    $parent = $intentsClient->projectAgentName($credentialsData['project_id']);

    // prepare training phrases for intent
    if(isset($data['training_phrase_parts'])){
      $trainingPhrases = [];
      foreach ($data['training_phrase_parts'] as $trainingPhrasePart) {
        $part = new Intent_TrainingPhrase_Part;
        $part->setText($trainingPhrasePart);

        // create new training phrase for each provided part
        $trainingPhrase = new Intent_TrainingPhrase();
        $trainingPhrase->setParts([$part]);
        $trainingPhrases[] = $trainingPhrase;
      }
    }

    // prepare messages for intent
    if (isset($data['message_texts'])){
      $text = new Intent_Message_Text();
      $text->setText($data['message_texts']);
      $message = new Intent_Message();
      $message->setText($text);
    }

    // prepare intent
    $intent = new Intent();
    $intent->setDisplayName($data['display_name']);
    if(isset($data['training_phrase_parts'])){
      $intent->setTrainingPhrases($trainingPhrases);
    }
    if (isset($data['message_texts'])){
      $intent->setMessages([$message]);
    }

    // create intent
    $response = $intentsClient->createIntent($parent, $intent);

    $intentsClient->close();

    return $response;

  }

  /**
   * Update an Intent
   * @param $intentId
   * @param $languageCode
   * @return Response
   */
  public function updateIntent($intentId, $data)
  {
    try {
      // Session Data
      $bot = Bot::find($data['project_id']);
      $credentialsData = json_decode($bot->credentials, true);
      $credentials = array('credentials' => $credentialsData);

      $intentsClient = new IntentsClient($credentials);
      $intent = 'projects/'.$credentialsData['project_id'].'/agent/intents/'.$intentId;
      $intent = $intentsClient->getIntent($intent, [
        'intentView' => IntentView::INTENT_VIEW_FULL
      ]);

      // prepare training phrases for intent
      if(isset($data['training_phrase_parts'])){
        $trainingPhrases = [];
        foreach ($data['training_phrase_parts'] as $trainingPhrasePart) {
          $part = new Intent_TrainingPhrase_Part;
          $part->setText($trainingPhrasePart);

          // create new training phrase for each provided part
          $trainingPhrase = new Intent_TrainingPhrase();
          $trainingPhrase->setParts([$part]);
          $trainingPhrases[] = $trainingPhrase;
        }
      }

      // prepare messages for intent
      if (isset($data['message_texts'])){
        $text = new Intent_Message_Text();
        $text->setText($data['message_texts']);
        $message = new Intent_Message();
        $message->setText($text);
      }

      if(isset($data['display_name'])){
        $intent->setDisplayName($data['display_name']);
      }
      if(isset($data['training_phrase_parts'])){
        $intent->setTrainingPhrases($trainingPhrases);
      }
      if (isset($data['message_texts'])){
        $intent->setMessages([$message]);
      }

      $languageCode = '';
      $response = $intentsClient->updateIntent($intent, $languageCode);
    } finally {
      $intentsClient->close();
    }
    return $intent;
  }

  /**
   * Delete an Intent
   * @param $intentId
   * @param $project
   * @return Response
   */
  public function DeleteIntent($intentId, $project)
  {
    // Session Data
    $bot = Bot::find($project);
    $credentialsData = json_decode($bot->credentials, true);
    $credentials = array('credentials' => $credentialsData);

    $parent = 'projects/'.$credentialsData['project_id'].'/agent/intents/'.$intentId;
    $intentsClient = new IntentsClient($credentials);
    $intentData = $intentsClient->getIntent($parent);
    $intents = $intentsClient->deleteIntent($intentData->getName());
    $intentsClient->close();
    $response = [];
    return $response;
  }

}
