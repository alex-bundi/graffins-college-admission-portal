<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
     protected $table = 'emergency_contacts';

    protected $fillable = [
        'full_name',
        'phone_no',
        'relationship',
        'applicant_id',
    ];

    protected $hidden = [
    'created_at', 'updated_at',
    ];
}
