<?php

namespace Modules\Idialogflow\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class BotTransformer extends Resource
{
  public function toArray($request)
  {
    $data = [
      'id' => $this->when($this->id, $this->id),
      'userId' => $this->when($this->user_id, $this->user_id),
      'userName' => $this->when($this->user_id, $this->user->first_name.' '.$this->user->last_name),
      'projectId' => $this->when($this->project_id, $this->project_id),
      'credentials' => $this->when($this->credentials, $this->credentials),
      'initMessage' => $this->when($this->init_message, $this->init_message),
      'twilioAuthToken' => $this->when($this->twilio_auth_token, $this->twilio_auth_token),
      'twilioAccountSid' => $this->when($this->twilio_account_sid, $this->twilio_account_sid),
      'twilioSender' => $this->when($this->twilio_sender, $this->twilio_sender),
    ];

    return $data;
  }
}
