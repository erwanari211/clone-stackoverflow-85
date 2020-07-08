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

    .vote-up, .vote-down {
      display: block;
      text-align: center;
      font-size: 30px;
      cursor: pointer;
      color: #ccc!important;
    }

    .vote-up.active svg,
    .vote-down.active svg{
      color: #3490dc!important;
    }
  </style>
@endpush

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <h1>Detail Pertanyaan</h1>

      @include('layouts.inc.messages')

      @php
        $isLoggedIn = false;
        $userId = null;
        if (auth()->check()) {
          $isLoggedIn = true;
          $userId = auth()->user()->id;
        }
      @endphp
      @php
        $voteUpId = 'form-vote-up-' . $question->id;
        $voteDownId = 'form-vote-down-' . $question->id;
      @endphp

      <div class="card mb-4">
        <div class="card-body">
          <div class="media mb-2">
            <div class="mr-3 vote-container">
              @if (auth()->check())
                <a class="vote-up {{ $question->user_vote == 'VOTE UP' ? 'active' : '' }}"
                  onclick="event.preventDefault();document.getElementById('{{ $voteUpId }}').submit();">
                  <i class="fa fa-caret-up"></i>
                </a>
                <span class="vote-count">
                  {{ $question->vote }}
                </span>
                <a class="vote-down {{ $question->user_vote == 'VOTE DOWN' ? 'active' : '' }}"
                  onclick="event.preventDefault();document.getElementById('{{ $voteDownId }}').submit();">
                  <i class="fa fa-caret-down"></i>
                </a>
              @else
                <a class="vote-up"
                  data-toggle="modal" data-target="#modal-please-login">
                  <i class="fa fa-caret-up"></i>
                </a>
                <span class="vote-count">
                  {{ $question->vote }}
                </span>
                <a class="vote-down"
                  data-toggle="modal" data-target="#modal-please-login">
                  <i class="fa fa-caret-down"></i>
                </a>
              @endif

              {{--
              <form
                style="display: none;"
                id="{{ $voteUpId }}"
                action="{{ route('pertanyaan.vote-up', $question->id) }}"
                method="POST">
                @csrf
              </form>
              <form
                style="display: none;"
                id="{{ $voteDownId }}"
                action="{{ route('pertanyaan.vote-down', $question->id) }}"
                method="POST">
                @csrf
              </form>
              --}}
            </div>

            <div class="media-body">
              <div class="media mb-2">
                <img class="d-flex mr-3 img-thumbnail rounded-circle" src="https://api.adorable.io/avatars/50/{{ $question->user->email }}.png" alt="Generic placeholder image">
                <div class="media-body">
                  <h5 class="mt-0">{{ $question->user->name }}</h5>
                  <span class="text-muted">{{ $question->created_at->diffForHumans() }}</span>
                </div>
              </div>

              <h3>
                <a href="{{ route('pertanyaan.show', $question->id) }}">
                  {{ $question->title }}
                </a>
              </h3>
              <p>
                {{ $question->content }}
              </p>

              <div class="row">
                <div class="col-sm-4">
                  <span class="text-muted">
                    {{ $question->replies_count }}
                    Jawaban
                  </span>
                </div>
                <div class="col-sm-8">
                </div>
                <div class="col-sm-12">
                  <span class="text-muted">Dibuat {{ $question->created_at->diffForHumans() }} </span> ·
                  <span class="text-muted">Diperbarui {{ $question->updated_at->diffForHumans() }}</span>
                </div>
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

      <div class="card mb-4">
        <div class="card-body">
          {{--
          @if (auth()->check())
            <form action="{{ route('jawaban.store', $question->id) }}" method="POST">
              @csrf

              <div class="form-group">
                <label>Jawaban</label>
                <textarea class="form-control" name="content" rows="5"></textarea>
              </div>

              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          @else
            <div class="alert alert-warning mb-0">
              Silahkan <a href="{{ route('login') }}">login</a> untuk menjawab pertanyaan
            </div>
          @endif
          --}}
        </div>
      </div>

      <h3 class="mb-4">Jawaban</h3>
      {{--
      @if (count($question->replies))
        @foreach ($question->replies as $reply)
          @php
            $voteUpReplyId = 'form-reply-vote-up-' . $reply->id;
            $voteDownReplyId = 'form-reply-vote-down-' . $reply->id;
          @endphp
          <div class="card mb-4">
            <div class="card-body">
              <div class="media mb-2">
                <div class="mr-3 vote-container">
                  @if (auth()->check())
                    <a class="vote-up {{ $reply->user_vote == 'VOTE UP' ? 'active' : '' }}"
                      onclick="event.preventDefault();document.getElementById('{{ $voteUpReplyId }}').submit();">
                      <i class="fa fa-caret-up"></i>
                    </a>
                    <span class="vote-count">
                      {{ $reply->vote }}
                    </span>
                    <a class="vote-down {{ $reply->user_vote == 'VOTE DOWN' ? 'active' : '' }}"
                      onclick="event.preventDefault();document.getElementById('{{ $voteDownReplyId }}').submit();">
                      <i class="fa fa-caret-down"></i>
                    </a>
                  @else
                    <a class="vote-up"
                      data-toggle="modal" data-target="#modal-please-login">
                      <i class="fa fa-caret-up"></i>
                    </a>
                    <span class="vote-count">
                      {{ $reply->vote }}
                    </span>
                    <a class="vote-down"
                      data-toggle="modal" data-target="#modal-please-login">
                      <i class="fa fa-caret-down"></i>
                    </a>
                  @endif

                  <form
                    style="display: none;"
                    id="{{ $voteUpReplyId }}"
                    action="{{ route('jawaban.vote-up', $reply->id) }}"
                    method="POST">
                    @csrf
                  </form>
                  <form
                    style="display: none;"
                    id="{{ $voteDownReplyId }}"
                    action="{{ route('jawaban.vote-down', $reply->id) }}"
                    method="POST">
                    @csrf
                  </form>
                </div>

                <div class="media-body">
                  <div class="media mb-2">
                    <img class="d-flex mr-3 img-thumbnail rounded-circle" src="https://api.adorable.io/avatars/50/{{ $reply->user->email }}.png" alt="Generic placeholder image">
                    <div class="media-body">
                      <h5 class="mt-0">{{ $reply->user->name }}</h5>
                      <span class="text-muted">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                  </div>

                  <p>
                    {{ $reply->content }}
                  </p>
                </div>
              </div>

            </div>
          </div>
        @endforeach
      @else
        <div class="alert alert-warning">
          Pertanyaan ini belum memiliki jawaban.
        </div>
      @endif
      --}}
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
@endsection
