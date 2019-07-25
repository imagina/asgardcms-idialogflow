<?php

namespace Modules\Idialogflow\Services;

use Modules\Idialogflow\Entities\Bot;
use Twilio\Rest\Client;
use Carbon\Carbon;

class MessageService
{
  /**
   * Get List Messages
   * @param $request
   * @return Response
   */
  public function getMessages($params)
  {
    // Get Bot Data Info
    $botId = $params->filter->botId;
    $bot = Bot::find($botId);

    // Connect to Twilio
    $sid    = $bot->twilio_account_sid;
    $token  = $bot->twilio_auth_token;
    $sender  = $bot->twilio_sender; // (Me 🤖)
    $twilio = new Client($sid, $token);

    // Get all messages sent from the bot to the client
    $criteriaMessagesFromBotToClient = [];
    $criteriaMessagesFromBotToClient['from'] = $sender;

    if (isset($params->filter->to)){
      $criteriaMessagesFromBotToClient['to'] = "whatsapp:+".$params->filter->to;
    }
    $messagesFromBotToClient = $this->formatedMessageData(
        $twilio->messages->read($criteriaMessagesFromBotToClient),
        $sender);

    // Get all messages sent from the client to the bot
    $criteriaMessagesFromClientToBot = [];
    if (isset($params->filter->to)){
      $criteriaMessagesFromClientToBot['from'] = "whatsapp:+".$params->filter->to;
    }
    $criteriaMessagesFromClientToBot['to'] = $sender;
    $messagesFromClientToBot = $this->formatedMessageData(
        $twilio->messages->read($criteriaMessagesFromClientToBot),
        $sender);

    // Concat And Sort messages chronologically
    $concat = collect($messagesFromClientToBot->merge($messagesFromBotToClient));
    $response = $concat->sortBy('date_sent');

    return $response->values()->all();
  }

  private function formatedMessageData ($messages, $sender) {
    $response = [];
    foreach ($messages as $record) {
      $responseTmp = [];
      $responseTmp['sid'] = $record->sid;
      $responseTmp['name'] = $record->from == $sender ? 'me' : 'Client';
      $responseTmp['status'] = $record->status;
      $responseTmp['from'] = $record->from;
      $responseTmp['to'] = $record->to;
      $responseTmp['body'] = $record->body;
      $responseTmp['date_sent'] = $record->dateSent;
      $responseTmp['date'] = $record->dateSent->format('Y/m/d H:i:s');
      $response[] = $responseTmp;
    }
    return collect($response);
  }
}
