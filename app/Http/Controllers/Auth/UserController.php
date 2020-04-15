<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\EnsureAtLeastOneAdmin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['id', 'name', 'email'])
            ->allowedIncludes(['roles'])
            ->defaultSort('name')
            ->allowedSorts(['id', 'name'])
            ->get();

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($request, $validated) {
            $user = User::create($validated);
            $user->assignRole($request->get('roles') ?: []);
            $user->givePermissionTo($request->get('permissions') ?: []);

            return $user;
        });

        return $this->show($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'roles' => ['sometimes', 'array', new EnsureAtLeastOneAdmin($user)],
            'permissions' => ['sometimes', 'array'],
        ]);

        $user = DB::transaction(function () use ($request, $user) {
            $user->update($request->only(['name', 'email', 'password']));
            $user->assignRole($request->get('roles') ?: []);
            $user->givePermissionTo($request->get('permissions') ?: []);

            return $user;
        });

        return $this->show($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if (auth()->user()->is($user)) {
            abort(403, 'Cannot delete itself');
        }

        $user->delete();

        return response()->json();
    }
}
