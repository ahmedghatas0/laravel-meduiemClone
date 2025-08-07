<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function followUnFollow(User $user){
        $user->followers()->toggle(Auth::user());

        return response()->json([
            'followersCount' => $user->followers()->count()
        ]);
    }
}
