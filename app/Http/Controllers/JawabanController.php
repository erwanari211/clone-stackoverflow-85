<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use App\Models\Question;
use RealRashid\SweetAlert\Facades\Alert;


class JawabanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($threadId)
    {
        $thread = Thread::with('user', 'replies')->findOrFail($threadId);
        return view('jawaban.index', compact('thread'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $threadId)
    {
        $question = Question::findOrFail($threadId);

        request()->validate([
            'content' => 'required',
        ]);

        $answer = new Answer;
        $answer->question_id = $question->id;
        $answer->user_id = auth()->user()->id;
        $answer->content = request('content');
        $answer->save();

        session()->flash('success', 'Jawaban telah tersimpan');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }

    public function upvote($answerId)
    {
        $answer = Answer::findOrFail($answerId);
        $answer->upvote();

        return redirect()->back();
    }

    public function downvote($answerId)
    {
        $answer = Answer::findOrFail($answerId);

        $user = auth()->user();
        $isAllowedToDownvote = $user->isAllowedToDownvote();
        if (!$isAllowedToDownvote) {
            Alert::toast('Maaf anda tidak bisa melakukan downvote', 'error');
            return redirect()->back();
        }

        $answer->downvote();
        return redirect()->back();
    }

    public function setAsBestAnswer($answerId)
    {
        $answer = Answer::findOrFail($answerId);
        $user = auth()->user();

        $question = $answer->question;
        if (!$question->isOwnedByUser($user->id)) {
            Alert::toast('Maaf anda tidak bisa memilih sebagai jawaban terbaik', 'error');
            return redirect()->back();
        }

        $answer->setAsBestAnswer();
        session()->flash('success', 'Jawaban telah dipilih sebagai jawaban terbaik');
        return redirect()->back();
    }
}
