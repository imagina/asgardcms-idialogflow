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

class IntentService
{

  // ProjectId get from credentials (google api cloud for dialog flow)
  private $projectId;

  public function __construct()
  {
    // Init projectId
    $file = file_get_contents(env('GOOGLE_APPLICATION_CREDENTIALS'));
    $json_a = json_decode($file, true);
    $this->projectId = $json_a['project_id'];
  }

  /**
   * Get List Intents
   * @param $request
   * @return Response
   */
  public function getIntents($request)
  {
    // get intents List
    $intentsClient = new IntentsClient();
    $parent = $intentsClient->projectAgentName($this->projectId);
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

      // Training phrases
      foreach ($intent->getTrainingPhrases() as $trainingPhrase) {
        $trainingPhrasetData = [];
        $trainingPhrasetData[] = json_decode($trainingPhrase->serializeToJsonString());
        $intentData['trainingPhrases'] = $trainingPhrasetData;
      }

      // Messages
      foreach ($intent->getMessages() as $message) {
        $messagesData = [];
        $messagesData[] = json_decode($message->serializeToJsonString());
        $intentData['messages'] = $messagesData;
      }

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
  public function getIntent($intentId)
  {
    $intent = 'projects/'.$this->projectId.'/agent/intents/'.$intentId;
    $intentsClient = new IntentsClient();
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
      $trainingPhrasetData = [];
      $trainingPhrasetData[] = json_decode($trainingPhrase->serializeToJsonString());
      $response['trainingPhrases'] = $trainingPhrasetData;
    }

    // Messages
    foreach ($intent->getMessages() as $message) {
      $messagesData = [];
      $messagesData[] = json_decode($message->serializeToJsonString());
      $response['messages'] = $messagesData;
    }

    $intentsClient->close();
    return $response;
  }

  /**
   * Create an Intent
   * @param $intentId
   * @return Response
   */
  public function createIntent($request)
  {
    $intentsClient = new IntentsClient();
    $intent = new Intent();

    // Name
    if (isset($request['display_name'])){
      $intent->setDisplayName($request['display_name']);
    }

    //dd(is_array($request['training_phrases']));
    // Training Phrases
    if (isset($request['training_phrases'])){
      $intent->setTrainingPhrases($request['training_phrases']);
    }

    // Responses Or Messages

    $parent = 'projects/'.$this->projectId.'/agent';
    $intents = $intentsClient->createIntent($parent, $intent);
    // ...
    $intentsClient->close();
    $response = [];
    return $response;
  }

  /**
   * Update an Intent
   * @param $intentId
   * @param $languageCode
   * @return Response
   */
  public function updateIntent($intent, $languageCode)
  {
    $intentsClient = new IntentsClient();
    $intents = $intentsClient->updateIntent($intent, $languageCode);

    $intentsClient->close();
    $response = [];
    return $response;
  }

  /**
   * Delete an Intent
   * @param $intentId
   * @return Response
   */
  public function DeleteIntent($intentId)
  {
    $parent = 'projects/'.$this->projectId.'/agent/intents/'.$intentId;
    $intentsClient = new IntentsClient();
    $intentData = $intentsClient->getIntent($parent);
    $intents = $intentsClient->deleteIntent($intentData->getName());
    $intentsClient->close();
    $response = [];
    return $response;
  }

}
