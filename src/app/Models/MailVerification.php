<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailVerification extends Model
{
    protected $table = 'mail_verifications';

    protected $guarded = array('id');

    public $timestamps = true;

    protected $fillable =[
        'mail_authentication',
        'mail'
    ];
}
