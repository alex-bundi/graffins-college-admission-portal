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
        'course_description',
        'level_description',
        'department_description',
        'application_status',
        'academic_year',
        'intake_code',
        'intake_description',
        'end_date',
        'tutor_code',
        'tutor_name',
        'unit_description',
    ];

    protected $hidden = [
    'created_at', 'updated_at',
    ];
}
