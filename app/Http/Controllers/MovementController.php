<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Repositories\MovementRepository;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    protected $movementRepositories;

    public function __construct(MovementRepository $movementRepositories)
    {
        $this->movementRepositories = $movementRepositories;
    }

    public function index()
    {
        $movements = $this->movementRepositories->all();
        return view('admin.asset.index', compact('movements'));
    }

    public function create()
    {
        return view('admin.assets.create', compact(['types', 'movements']));
    }

    public function store(MovementRequest $movementRequest)
    {
        $input = $movementRequest->validated();
        $this->movementRepositories->create($input);
        return redirect()->route('admin.assets.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->movementRepositories->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $asset = $this->movementRepositories->find($id);
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(MovementRequest $movementRequest, $id)
    {
        $input = $movementRequest->validated();
        $this->movementRepositories->update($id, $input);
        return redirect()->route('admin.asset.index');
    }

    public function delete($id)
    {
        $this->movementRepositories->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
