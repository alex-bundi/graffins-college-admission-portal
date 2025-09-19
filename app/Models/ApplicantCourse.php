<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCourse extends Model
{
    protected $table = 'applicant_course';

    protected $fillable = [
        'applicant_id',
        'academic_year',
        'intake',
        'department_code',
        'mode_of_study',
        'course_code',
        'course_level',
        'start_date',
        'end_date',
        'application_date',
        'unit_status',
        'unit_code',
        'class_time',
    ];

    protected $hidden = [
    'created_at', 'updated_at',
    ];
}
