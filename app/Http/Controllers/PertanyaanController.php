<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with('user')->withCount('answers')->latest()->paginate(10);
        return view('pertanyaan.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pertanyaan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $thread = new Question;
        $thread->user_id = auth()->user()->id;
        $thread->title = request('title');
        $thread->content = request('content');
        $thread->save();

        session()->flash('successMessage', 'Pertanyaan telah tersimpan');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($questionId)
    {
        $question = Question::with('user')->withCount('answers')->findOrFail($questionId);
        return view('pertanyaan.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit($questionId)
    {
        $question = Question::with('user')->findOrFail($questionId);
        if ($question->user_id !== auth()->user()->id) {
            return abort(403);
        }
        return view('pertanyaan.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $questionId)
    {
        $question = Question::findOrFail($questionId);
        if ($question->user_id !== auth()->user()->id) {
            return abort(403);
        }

        request()->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $question->title = request('title');
        $question->content = request('content');
        $question->save();

        session()->flash('successMessage', 'Pertanyaan telah diperbarui');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($questionId)
    {
        $question = Question::findOrFail($questionId);
        if ($question->user_id !== auth()->user()->id) {
            return abort(403);
        }

        // $question->answers()->delete();
        $question->delete();

        session()->flash('successMessage', 'Pertanyaan telah dihapus');
        return redirect()->route('pertanyaan.index');
    }
}
