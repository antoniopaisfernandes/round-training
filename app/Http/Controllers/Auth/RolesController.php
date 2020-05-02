<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Role[]
     */
    public function __invoke()
    {
        return Role::all();
    }
}
