<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;


class CourseController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
    }

    public function getCourseList(){
        $email = session('user_data')['email'];
        $applicantsQuery = $this->generalQueries->applicantsQuery();
        $applicantsURL = config('app.odata') . "{$applicantsQuery}?". '$filter=' . rawurlencode("Email eq '{$email}'");
        $applicantsData = $this->getOdata($applicantsURL);
        return Inertia::render('CourseList',[
            'applications' => $applicantsData['value'],
        ]);
    }
}
