<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{

    public function index(): Response
    {
        $perPage = request('per_page', 2); // Отримуємо значення або дефолтне (2)
        $users = UserResource::collection(User::withTrashed()->paginate($perPage));

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    public function search(Request $request): Response
    {
        $perPage = $request->input('per_page', 2);
        $search = $request->input('search');

        $query = User::withTrashed();

        if ($search && strlen($search) >= 3) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = UserResource::collection($query->paginate($perPage));

        return Inertia::render('Admin/Users/Index', compact('users'));
    }


    public function create()
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Розділ успішно створено.');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
