<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Repositories\RoomRepository;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $roomRepositories;
    protected $roomService;

    public function __construct(
        RoomRepository $roomRepositories,
        RoomService $roomService,
    ) {
        $this->roomRepositories = $roomRepositories;
        $this->roomService = $roomService;
    }

    public function tableAll()
    {
        return $this->roomService->tableAll();
    }

    public function selectAll(Request $request)
    {
        $term = $request->term;
        return $this->roomService->selectAll($term);
    }

    public function index()
    {
        $rooms = $this->roomRepositories->all();
        return view('room.index', compact('rooms'));
    }

    public function create()
    {
        return view('room.create');
    }

    public function store(RoomRequest $roomRequest)
    {
        $input = $roomRequest->validated();
        $this->roomRepositories->create($input);
        return redirect()->route('admin.rooms.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->roomRepositories->search($term);
        return response()->json($results);
    }

    public function show($id): JsonResponse
    {
        $room = $this->roomService->find($id);
        $room = $room->assetRoomConditions ?? $room;
        return response()->json($room);
    }

    public function edit($id)
    {
        $room = $this->roomRepositories->find($id);
        return view('room.edit', compact('room'));
    }

    public function update(RoomRequest $roomRequest, $id)
    {
        $input = $roomRequest->validated();
        $this->roomRepositories->update($id, $input);
        return redirect()->route('admin.rooms.index');
    }

    public function destroy($id)
    {
        $this->roomRepositories->delete($id);
        return redirect()->route('admin.rooms.index');
    }
}
