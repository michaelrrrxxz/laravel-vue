<?php

namespace App\Http\Controllers\Api;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Batch\StoreBatchRequest;
use App\Http\Requests\Batch\UpdateBatchRequest;
class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batch = Batch::all();
        return response()->json($batch);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $batch = Batch::create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBatchRequest $request)
    {
        $validated = $request->validated();

        return Batch::where('status', 'active')->exists()
            ? response()->json(['result' => 'error', 'message' => 'Cannot add a new batch. An active batch already exists.'])
            : tap(Batch::create(array_merge($validated, ['access_key' => mt_rand(100000, 999999)])), function ($batch) {
                return response()->json(['message' => 'Batch Added', 'batch' => $batch], 201);
            }) ?? response()->json(['error' => 'Failed to add batch'], 500);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = Batch::find($id);
        return response()->json($batch);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $batch = Batch::find($id);
        return response()->json($batch);
    }

    /**
     * Update the specified resource in storage.
     */public function update(UpdateBatchRequest $request, string $id)
        {
            $validated = $request->validated();



            $batch = Batch::find($id);

            if (!$batch) {
                return response()->json(['error' => 'Batch not found'], 404);
            }

            $batch->update($validated);
            return response()->json('updated');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Batch::destroy($id);
        return response()->json(['message'=> 'Deleted'],200);
    }
}
