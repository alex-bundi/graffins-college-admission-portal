<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralQueries extends Model
{
    

    public function applicantsQuery(){
        return 'QyApplicants';
    }

    public function departmentsQuery(){
        return 'QyDimensions';
    }

    public function coursesQuery(){
        return 'QyCourses';
    }

    public function unitsQuery(){
        return 'QyUnits';
    }
    public function unitFeesQuery(){
        return 'QyUnitFees';
    }
    public function classTimeQuery(){
        return 'QyClassTime';
    }
    public function countriesQuery(){
        return 'QyCountries';
    }
    public function residenceQuery(){
        return 'QyAcadCentralSetups';
    }
    public function marketingQuery(){
        return 'QyMarketingArea';
    }
     public function courseLevelsQuery(){
        return 'QyCourseLevels';
    }

    public function studentUnitsQuery(){
        return 'QyStudentUnits';
    }

    public function studentPaymentsQuery(){
        return 'QyStudentPayments';
    }

    public function studentsQuery(){
        return 'QyStudentDetails';
    }

    

}