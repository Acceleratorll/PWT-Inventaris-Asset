<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Repositories\MovementRepository;
use App\Services\MovementService;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    protected $movementService;

    public function __construct(
        MovementService $movementService,
    ) {
        $this->movementService = $movementService;
    }

    public function tableAll()
    {
        return $this->movementService->tableAll();
    }

    public function index()
    {
        return view('movement.index');
    }

    public function create()
    {
        return view('movement.create');
    }

    public function store(MovementRequest $movementRequest)
    {
        $input = $movementRequest->validated();
        $move = $this->movementService->move($input);
        if ($move) {
            $this->movementService->create($input);
        }
        return redirect()->route('admin.movements.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->movementService->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $movement = $this->movementService->find($id);
        return view('movement.edit', compact('movement'));
    }

    public function update(MovementRequest $movementRequest, $id)
    {
        $input = $movementRequest->validated();
        $this->movementService->update($id, $input);
        return redirect()->route('admin.movements.index');
    }

    public function destroy($id)
    {
        $this->movementService->delete($id);
        return redirect()->route('admin.movements.index');
    }
}
