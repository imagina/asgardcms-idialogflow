<?php

namespace Modules\Idialogflow\Entities;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
  protected $table = 'idialogflow__bots';

  protected $fillable = [
    'user_id',
    'project_id',
    'credentials',
    'init_message',
    'twilio_auth_token',
    'twilio_account_sid',
    'twilio_sender',
  ];

  public function user()
  {
    $driver = config('asgard.user.config.driver');
    return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
  }

}
