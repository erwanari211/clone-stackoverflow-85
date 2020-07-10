@extends('layouts.app')

@push('css')
  <style>
    .vote-container {
      width: 80px;
    }

    .vote-count {
      display: block;
      text-align: center;
      font-size: 20px;
    }

    .upvote, .downvote {
      display: block;
      text-align: center;
      font-size: 30px;
      cursor: pointer;
      color: #ccc!important;
    }

    .upvote.active svg,
    .downvote.active svg{
      color: #3490dc!important;
    }

    @media only screen and (max-width: 575px) {
      .vote-container {
        width: 60px;
      }

      .vote-count {
        display: block;
        text-align: center;
        font-size: 16px;
      }
    }

    .question-content img {
      width: 100%;
    }
  </style>
@endpush

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <h1>List Pertanyaan</h1>

      @include('layouts.inc.messages')

      @php
        $isLoggedIn = false;
        $userId = null;
        if (auth()->check()) {
          $isLoggedIn = true;
          $userId = auth()->user()->id;
        }
      @endphp

      @if (count($questions))
        @foreach ($questions as $question)
          @php
            $upvoteQuestionId = 'form-upvote-question-' . $question->id;
            $downvoteQuestionId = 'form-downvote-question-' . $question->id;
          @endphp
          <div class="card mb-4">
            <div class="card-body">
              <div class="media mb-2 question">
                <div class="mr-3 vote-container">
                  @if (auth()->check())
                    <a class="upvote {{ $question->user_vote == 'UPVOTE' ? 'active' : '' }}"
                      onclick="event.preventDefault();document.getElementById('{{ $upvoteQuestionId }}').submit();"
                    >
                      <i class="fa fa-caret-up"></i>
                    </a>
                    <span class="vote-count">
                      {{ $question->vote }}
                    </span>
                    <a class="downvote {{ $question->user_vote == 'DOWNVOTE' ? 'active' : '' }}"
                      onclick="event.preventDefault();document.getElementById('{{ $downvoteQuestionId }}').submit();"
                    >
                      <i class="fa fa-caret-down"></i>
                    </a>
                  @else
                    <a class="upvote"
                      data-toggle="modal" data-target="#modal-please-login">
                      <i class="fa fa-caret-up"></i>
                    </a>
                    <span class="vote-count">
                      {{ $question->vote }}
                    </span>
                    <a class="downvote"
                      data-toggle="modal" data-target="#modal-please-login">
                      <i class="fa fa-caret-down"></i>
                    </a>
                  @endif

                  <form
                    style="display: none;"
                    id="{{ $upvoteQuestionId }}"
                    action="{{ route('pertanyaan.upvote', $question->id) }}"
                    method="POST">
                    @csrf
                  </form>
                  <form
                    style="display: none;"
                    id="{{ $downvoteQuestionId }}"
                    action="{{ route('pertanyaan.downvote', $question->id) }}"
                    method="POST">
                    @csrf
                  </form>
                </div>

                <div class="media-body">
                  <div class="media mb-2">
                    <img class="d-flex mr-3 img-thumbnail rounded-circle" src="https://api.adorable.io/avatars/50/{{ $question->user->email }}.png" alt="Generic placeholder image">
                    <div class="media-body">
                      <h5 class="my-0">
                        {{ $question->user->name }}
                        {!! $question->user->reputation_label !!}
                      </h5>
                      <small class="text-muted">{{ $question->created_at->diffForHumans() }}</small>
                    </div>
                  </div>

                  <h3 class="question-title">
                    <a href="{{ route('pertanyaan.show', $question->id) }}">
                      {{ $question->title }}
                    </a>
                  </h3>
                  <div class="question-content">
                    {!! $question->content !!}
                  </div>
                  <div class="question-tag mb-3">
                    {!! $question->tag_button !!}
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <span class="text-muted">
                        {{ $question->answers_count ?? 0 }}
                        Jawaban
                      </span>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"></div>
                  </div>

                  @if ($isLoggedIn && $question->user_id == $userId)
                    <div class="mt-4">
                      <a class="btn btn-success btn-sm" href="{{ route('pertanyaan.edit', $question->id) }}">Edit</a>

                      <form style="display: inline-block;" action="{{ route('pertanyaan.destroy', $question->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                      </form>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach

        {{ $questions->links() }}
      @else
        Belum ada pertanyaan. Silahkan <a href="{{ route('pertanyaan.create') }}">buat pertanyaan</a>
      @endif

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-please-login" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Silahkan login untuk melakukan vote
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@include('sweetalert::alert')
@endsection
