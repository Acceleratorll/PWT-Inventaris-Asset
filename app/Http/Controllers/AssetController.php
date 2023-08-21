<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\RoomRepository;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    protected $assetRepositories;
    protected $assetTypeRepositories;
    protected $roomRepositories;

    public function __construct(AssetRepository $assetRepositories, AssetTypeRepository $assetTypeRepositories, RoomRepository $roomRepositories)
    {
        $this->assetRepositories = $assetRepositories;
        $this->assetTypeRepositories = $assetTypeRepositories;
        $this->roomRepositories = $roomRepositories;
    }

    public function index()
    {
        $assets = $this->assetRepositories->all();
        return view('admin.asset.index', compact('assets'));
    }

    public function create()
    {
        $types = $this->assetTypeRepositories->all();
        $rooms = $this->roomRepositories->all();
        return view('admin.assets.create', compact(['types', 'rooms']));
    }

    public function store(AssetRequest $assetRequest)
    {
        $input = $assetRequest->validated();
        $this->assetRepositories->create($input);
        return redirect()->route('admin.assets.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->assetRepositories->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $asset = $this->assetRepositories->find($id);
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(AssetRequest $assetRequest, $id)
    {
        $input = $assetRequest->validated();
        $this->assetRepositories->update($id, $input);
        return redirect()->route('admin.asset.index');
    }

    public function delete($id)
    {
        $this->assetRepositories->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
