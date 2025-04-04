<?php

namespace App\Http\Middleware;

use App\Models\Workplace;
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
                'hashed_email' => hash( 'sha256', $request->user() ? $request->user()->email : '' ),
                'user' => $request->user(),
            ],
            'current_workplace' => Workplace::find(session('workplace', 1))
        ];
    }
}
