<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                 // 'user' => $request->user(),
                'user' => session('user_data'),
                'applicant_data' => session('applicant_data'),
            ],
            'flash' => [
                'message' => session(key: 'message'),
                'success' => session(key: 'success'),
                'error' => session(key: 'error'),
            ],
        ];
    }
}
