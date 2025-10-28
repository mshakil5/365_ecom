<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guideline;
use Intervention\Image\Facades\Image;

class GuidelineController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Guideline::latest()->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => '<img src="' . asset('images/guidelines/' . $row->image) . '" width="60">')
                ->addColumn('action', fn($row) => '<button data-id="' . $row->id . '" class="btn btn-sm btn-primary EditBtn">Edit</button>')
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        $allPositions = [
            'Right Chest','Center Chest','Left Chest','Top Chest',
            'Center Back','Top Back','Shoulder Blade','Bottom Back',
            'Left Sleeve','Right Sleeve'
        ];

        $used = Guideline::pluck('position')->toArray();
        $availablePositions = array_diff($allPositions, $used);

        return view('admin.guidelines.index', compact('availablePositions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|unique:guidelines,position',
            'image' => 'required|image|max:2048'
        ]);

        $file = $request->file('image');
        $name = mt_rand(100000, 999999) . '.webp';
        $path = public_path('images/guidelines/');
        if (!file_exists($path)) mkdir($path, 0755, true);

        Image::make($file)
            ->resize(800, null, fn($c) => $c->aspectRatio())
            ->encode('webp', 50)
            ->save($path . $name);

        Guideline::create([
            'position' => $request->position,
            'image' => $name
        ]);

        return response()->json(['message' => 'Guideline created successfully!'], 200);
    }

    public function edit($id)
    {
        $guideline = Guideline::findOrFail($id);
        return response()->json($guideline);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:guidelines,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $guideline = Guideline::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $oldPath = public_path('images/guidelines/' . $guideline->image);
            if (file_exists($oldPath)) @unlink($oldPath);

            $file = $request->file('image');
            $name = mt_rand(100000, 999999) . '.webp';
            $path = public_path('images/guidelines/');
            if (!file_exists($path)) mkdir($path, 0755, true);

            Image::make($file)
                ->resize(800, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($path . $name);

            $guideline->image = $name;
        }

        $guideline->save();

        return response()->json(['message' => 'Guideline updated successfully!'], 200);
    }

    public function availablePositions()
    {
        $allPositions = [
            'Right Chest','Center Chest','Left Chest','Top Chest',
            'Center Back','Top Back','Shoulder Blade','Bottom Back',
            'Left Sleeve','Right Sleeve'
        ];

        $used = Guideline::pluck('position')->toArray();
        $availablePositions = array_values(array_diff($allPositions, $used));

        return response()->json($availablePositions);
    }

}