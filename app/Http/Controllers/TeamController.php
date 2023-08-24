<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamResource;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return TeamResource::collection(auth()->user()->currentTeam->allUsers()->load('todos'));
    }

    public function show(User $user)
    {
        return new TeamResource($user->load('todos'));
    }
}
