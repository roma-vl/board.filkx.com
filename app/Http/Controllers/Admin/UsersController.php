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
    const int PER_PAGE = 2;
    const int SEARCH_MIN_LENGTH = 1;

    public function index(): Response
    {
        $perPage = request('per_page', self::PER_PAGE);
        $users = UserResource::collection(User::withTrashed()->paginate($perPage));

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    public function search(Request $request): Response
    {
        $perPage = $request->input('per_page', self::PER_PAGE);
        $search = $request->input('search');

        $query = User::withTrashed();

        if ($search && strlen($search) >= self::SEARCH_MIN_LENGTH) {
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
