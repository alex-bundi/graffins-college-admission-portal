<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'applicant';

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'phone_no',
        'nationality',
        'email',
        'residence',
        'marketing',
        'allergies',
        'allergy_description',
        'application_date',
        'application_no',
        'application_status',
        'country_name',
        'residence_description',
        'marketing_description',
        'passport_file_path',
        'student_image_file_path',
    ];

    protected $hidden = [
    'created_at', 'updated_at',
    ];
}
