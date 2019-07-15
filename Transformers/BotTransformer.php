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
      'projectId' => $this->when($this->project_id, $this->project_id),
      'credentials' => $this->when($this->credentials, $this->credentials),
    ];

    return $data;
  }
}
