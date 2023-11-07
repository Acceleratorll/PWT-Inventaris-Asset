<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetTypeRequest;
use App\Repositories\AssetTypeRepository;
use App\Services\AssetTypeService;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    protected $assetTypeRepositories;
    protected $assetTypeService;

    public function __construct(
        AssetTypeRepository $assetTypeRepositories,
        AssetTypeService $assetTypeService,
    ) {
        $this->assetTypeRepositories = $assetTypeRepositories;
        $this->assetTypeService = $assetTypeService;
    }

    public function index()
    {
        $types = $this->assetTypeRepositories->all();
        return view('type.index', compact('types'));
    }

    public function selectAll(Request $request)
    {
        $term = $request->term;
        return $this->assetTypeService->selectAll($term);
    }

    public function create()
    {
        return view('type.create', compact(['types', 'rooms']));
    }

    public function store(AssetTypeRequest $assetTypeRequest)
    {
        $input = $assetTypeRequest->validated();
        $this->assetTypeRepositories->create($input);
        return redirect()->route('admin.types.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->assetTypeRepositories->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $type = $this->assetTypeRepositories->find($id);
        return view('type.edit', compact('type'));
    }

    public function update(AssetTypeRequest $assetTypeRequest, $id)
    {
        $input = $assetTypeRequest->validated();
        $this->assetTypeRepositories->update($id, $input);
        return redirect()->route('admin.types.index');
    }

    public function delete($id)
    {
        $this->assetTypeRepositories->delete($id);
        return redirect()->route('admin.types.index');
    }
}
