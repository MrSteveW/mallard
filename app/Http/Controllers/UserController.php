<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use App\Models\Employee;
use App\Http\Resources\UserResource;
use Inertia\Inertia;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Authorization
    public static function middleware(): array
    {
        return [
            new Middleware('can:viewAny,App\Models\User'),
        ];
    }


  public function index()
{
    $users = User::with('employee.grade')->get();

    return Inertia::render('Users/Index', [
        'users' => UserResource::collection($users),
        'totalCount' => $users->count(),
    ]);
}

    public function create()
    {
        return Inertia::render('Users/Create', [
        'roles' => array_column(UserRole::cases(), 'value'),
        'grades' => Grade::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>['required', 'string', 'max:255'],
            'email'=>['required', 'email', 'unique:users'],
            'password'=>['required'],
            'role'=>['required', Rule::enum(UserRole::class)],
            'grade_id'=>['required'],
            'training'=>['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => $validated['password'],
            ]);

            $user->employee()->create([
                'grade_id' => $validated['grade_id'],
                'training' => $validated['training'],
            ]);
        });
        
        return redirect('/users')->with('message', 'User created successfully.');
    }



    public function edit(User $user)
    {
        $user->load('employee.grade');
         return Inertia::render('Users/Edit', [
            'user' => new UserResource($user),
            'roles' => array_column(UserRole::cases(), 'value'),
            'grades' => Grade::select('id', 'name')->get(),
        ]);
    }



    public function update(User $user, Request $request)
    {
        $validated = $request->validate([
            'name'=>['required', 'string', 'max:255'],
            'email'=>[
                'required',
                'email', 
                Rule::unique('users')->ignore($user->id)],
            'grade_id'=>['required'],
            'role'=>['required', Rule::enum(UserRole::class)],
            'training'=>['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);

            $user->employee()->updateOrCreate(
            ['user_id' => $user->id],     
            [
                'grade_id' => $validated['grade_id'],
                'training' => $validated['training'],
            ]);
        });
        
        
        return redirect('/users')->with('message', 'User updated successfully.');;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/users');
    }
}