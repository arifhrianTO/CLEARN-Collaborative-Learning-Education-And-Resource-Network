<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->query('role');
        $search = $request->query('search');

        $usersQuery = User::query();

        if ($role && in_array($role, ['admin', 'mentor', 'student'])) {
            $usersQuery->where('role', $role);
        }

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->latest()->paginate(5)->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'search'));
    }
}
