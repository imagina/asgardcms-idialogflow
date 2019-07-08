<?php

namespace Modules\Idialogflow\Services;

// SDK Dialog Flow
use Google\Cloud\Dialogflow\V2\IntentsClient;
use Google\Cloud\Dialogflow\V2\Intent;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase\Part;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase;
use Google\Cloud\Dialogflow\V2\Intent\Message;
use Google\Cloud\Dialogflow\V2\Intent\Message\Text;

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
    $intents = $intentsClient->listIntents($parent);
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
  public function getIntent($intentId)
  {
    $intent = 'projects/'.$this->projectId.'/agent/intents/'.$intentId;
    $intentsClient = new IntentsClient();
    $intent = $intentsClient->getIntent($intent);
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

    // Parameters
    foreach ($intent->getParameters() as $parameters) {
      $parameterData = [];
      $parameterData['name'] = $parameters->getName();
      $parameterData['required'] = $parameters->getMandatory();
      $parameterData['displayName'] = $parameters->getDisplayName();
      $parameterData['value'] = $parameters->getValue();
      $parameterData['is_list'] = $parameters->getIslist();

      // Prompts
      foreach ($parameters->getPrompts() as $prompt) {
        $promptData = [];
        $promptData[] = $prompt;
        $parameterData['prompts'] = $promptData;
      }
      $response['parameters'] = $parameterData;
    }

    // Events
    foreach ($intent->getEvents() as $event) {
      $eventData = [];
      $eventData[] = $event;
      $response['events'] = $eventData;
    }

    // Input Context Names
    foreach ($intent->getInputContextNames() as $inputContext) {
      $InputContextData = [];
      $InputContextData[] = $inputContext;
      $response['inputContexts'] = $InputContextData;
    }

    // Output Contexts
    foreach ($intent->getOutputContexts() as $outContext) {
      $OutputContexttData = [];
      $OutputContexttData[] = $outContext->getName();
      $response['outputContexts'] = $OutputContexttData;
    }

    // Training phrases
    foreach ($intent->getTrainingPhrases() as $trainingPhrase) {
      $trainingPhrasetData = [];
      $trainingPhrasetData[] = $trainingPhrase;
      $response['trainingPhrases'] = $trainingPhrasetData;
    }

    // Default Response Platform
    foreach ($intent->getDefaultResponsePlatforms() as $defaultResponsePlatform ){
      $ResponsePlatformData = [];
      $ResponsePlatformData[] = $defaultResponsePlatform;
      $response['defaultResponsePlatform'] = $ResponsePlatformData;
    }

    // Messages
    foreach ($intent->getMessages() as $message) {
      $messagesData = [];
      $messagesData[] = $message;
      $intentData['messages'] = $messagesData;
    }

    // Messages
    foreach ($intent->getFollowupIntentInfo() as $followupIntentInfo) {
      $followupIntentInfoData = [];
      $followupIntentInfoData[] = $followupIntentInfo;
      $response['followupIntentInfo'] = $followupIntentInfoData;
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
    $intent->setDisplayName($request['display_name']);
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
    $intentsClient = new IntentsClient();
    $intents = $intentsClient->deleteIntent($intentId);

    $intentsClient->close();
    $response = [];
    return $response;
  }

}
