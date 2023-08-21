<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Repositories\RoomRepository;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $roomRepositories;

    public function __construct(RoomRepository $roomRepositories)
    {
        $this->roomRepositories = $roomRepositories;
    }

    public function index()
    {
        $rooms = $this->roomRepositories->all();
        return view('admin.asset.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.assets.create', compact(['types', 'rooms']));
    }

    public function store(RoomRequest $roomRequest)
    {
        $input = $roomRequest->validated();
        $this->roomRepositories->create($input);
        return redirect()->route('admin.assets.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->roomRepositories->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $asset = $this->roomRepositories->find($id);
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(RoomRequest $roomRequest, $id)
    {
        $input = $roomRequest->validated();
        $this->roomRepositories->update($id, $input);
        return redirect()->route('admin.asset.index');
    }

    public function delete($id)
    {
        $this->roomRepositories->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
