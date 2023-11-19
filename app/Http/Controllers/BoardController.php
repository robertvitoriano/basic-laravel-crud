<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Http\Requests\CreateBoardMembershipRequest;
use App\Models\User;
class BoardController extends Controller
{
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $board = Auth::user()->boards()->create($validated);
        $board->boardMembers()->attach([$board->creator_id]);

        return new BoardResource($board);
    }

    public function index(Request $request)
    {
        return new BoardCollection(Board::all());
    }

    public function createBoardMembership(CreateBoardMembershipRequest $request)
    {
        $user_id = $request->input('user_id');
        $board_id = $request->input('board_id');
        $user = User::find($user_id);
        $user->boardMemberships()->attach([$board_id]);
        return response()->json(['message' => 'board membership created'], 201);
    }
}
