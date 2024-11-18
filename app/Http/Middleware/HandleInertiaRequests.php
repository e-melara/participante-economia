<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function version(Request $request): string|null
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
        $permissions = [];
        $user = $request->user();
        if(!is_null($user)) {
          $user->getPermissionsViaRoles();
        }

        $responseTo = array(
          'auth' => [
            'user' => $user
          ],
          'status' => [
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error'),
            'show' => $request->session()->get('error') || $request->session()->get('success'),
          ],
        );

        if(!empty($user->persona)) {
          $responseTo['auth']['avatar'] = @$user->persona->photo_url();
        }

        return $responseTo;
    }
}
