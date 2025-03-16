<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Controllers\Controller;
use App\Models\Question;
class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::all();
        return response()->json($questions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {

        $validated = $request->validated();

        $question = Question::create($validated);
        return response()->json("successfully added");
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $question =  Question::find($id);
        return response()->json($question);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $validated = $request->validated();
        $question->update($validated);
        return response()->json('Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
