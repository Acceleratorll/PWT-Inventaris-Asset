<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetTypeRequest;
use App\Repositories\AssetTypeRepository;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    protected $assetTypeRepositories;

    public function __construct(AssetTypeRepository $assetTypeRepositories)
    {
        $this->assetTypeRepositories = $assetTypeRepositories;
    }

    public function index()
    {
        $types = $this->assetTypeRepositories->all();
        return view('admin.asset.index', compact('types'));
    }

    public function create()
    {
        return view('admin.assets.create', compact(['types', 'rooms']));
    }

    public function store(AssetTypeRequest $assetTypeRequest)
    {
        $input = $assetTypeRequest->validated();
        $this->assetTypeRepositories->create($input);
        return redirect()->route('admin.assets.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->assetTypeRepositories->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $asset = $this->assetTypeRepositories->find($id);
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(AssetTypeRequest $assetTypeRequest, $id)
    {
        $input = $assetTypeRequest->validated();
        $this->assetTypeRepositories->update($id, $input);
        return redirect()->route('admin.asset.index');
    }

    public function delete($id)
    {
        $this->assetTypeRepositories->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
