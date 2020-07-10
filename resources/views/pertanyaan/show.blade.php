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

    .question-content img,
    .answer-content img {
      width: 100%!important;
    }

    .is-best-answer {
      background-color: rgb(243,249,233);
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
        $upvoteQuestionId = 'form-upvote-question-' . $question->id;
        $downvoteQuestionId = 'form-downvote-question-' . $question->id;
      @endphp

      <div class="card mb-4">
        <div class="card-body">
          <div class="media mb-2 question">
            <div class="mr-3 vote-container">
              @if (auth()->check())
                <a class="upvote {{ $question->user_vote == 'UPVOTE' ? 'active' : '' }}"
                  onclick="event.preventDefault();document.getElementById('{{ $upvoteQuestionId }}').submit();">
                  <i class="fa fa-caret-up"></i>
                </a>
                <span class="vote-count">
                  {{ $question->vote }}
                </span>
                <a class="downvote {{ $question->user_vote == 'DOWNVOTE' ? 'active' : '' }}"
                  onclick="event.preventDefault();document.getElementById('{{ $downvoteQuestionId }}').submit();">
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
          @if (auth()->check())
            <form action="{{ route('jawaban.store', $question->id) }}" method="POST">
              @csrf

              <div class="form-group">
                <label>Jawaban</label>
                <textarea class="ckeditor" id="content" name="content" rows="5"></textarea>
              </div>

              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          @else
            <div class="alert alert-warning mb-0">
              Silahkan <a href="{{ route('login') }}">login</a> untuk menjawab pertanyaan
            </div>
          @endif
        </div>
      </div>

      <h3 class="mb-4">Jawaban</h3>

      @if (count($question->answers))
        @foreach ($question->answers as $answer)
          @php
            $upvoteAnswerId = 'form-upvote-answer-' . $answer->id;
            $downvoteAnswerId = 'form-downvote-answer-' . $answer->id;
            $bestAnswerId = 'form-best-answer-answer-' . $answer->id;
          @endphp
          <div class="card mb-4">
            <div class="card-body {{ $answer->id == $question->best_answer_id ? 'is-best-answer' : '' }}">
              <div class="media mb-2 answer">
                <div class="mr-3 vote-container">
                  @if (auth()->check())
                    <a class="upvote {{ $answer->user_vote == 'UPVOTE' ? 'active' : '' }}"
                      onclick="event.preventDefault();document.getElementById('{{ $upvoteAnswerId }}').submit();">
                      <i class="fa fa-caret-up"></i>
                    </a>
                    <span class="vote-count">
                      {{ $answer->vote }}
                    </span>
                    <a class="downvote {{ $answer->user_vote == 'DOWNVOTE' ? 'active' : '' }}"
                      onclick="event.preventDefault();document.getElementById('{{ $downvoteAnswerId }}').submit();">
                      <i class="fa fa-caret-down"></i>
                    </a>
                    @if ($question->isOwnedByUser(auth()->user()->id))
                      <a class="downvote {{ $answer->id == $question->best_answer_id ? 'text-success' : '' }}"
                        onclick="event.preventDefault();document.getElementById('{{ $bestAnswerId }}').submit();">
                        <i class="fa fa-check"></i>
                      </a>
                    @endif
                  @else
                    <a class="upvote"
                      data-toggle="modal" data-target="#modal-please-login">
                      <i class="fa fa-caret-up"></i>
                    </a>
                    <span class="vote-count">
                      {{ $answer->vote }}
                    </span>
                    <a class="downvote"
                      data-toggle="modal" data-target="#modal-please-login">
                      <i class="fa fa-caret-down"></i>
                    </a>
                    @if ($answer->id == $question->best_answer_id)
                      <a class="downvote text-success">
                        <i class="fa fa-check"></i>
                      </a>
                    @endif
                  @endif

                  <form
                    style="display: none;"
                    id="{{ $upvoteAnswerId }}"
                    action="{{ route('jawaban.upvote', $answer->id) }}"
                    method="POST">
                    @csrf
                  </form>
                  <form
                    style="display: none;"
                    id="{{ $downvoteAnswerId }}"
                    action="{{ route('jawaban.downvote', $answer->id) }}"
                    method="POST">
                    @csrf
                  </form>
                  <form
                    style="display: none;"
                    id="{{ $bestAnswerId }}"
                    action="{{ route('jawaban.set-as-best-answer', $answer->id) }}"
                    method="POST">
                    @csrf
                  </form>
                </div>

                <div class="media-body">
                  <div class="media mb-2">
                    <img class="d-flex mr-3 img-thumbnail rounded-circle" src="https://api.adorable.io/avatars/50/{{ $answer->user->email }}.png" alt="Generic placeholder image">
                    <div class="media-body">
                      <h5 class="my-0">
                        {{ $answer->user->name }}
                        {!! $answer->user->reputation_label !!}
                      </h5>
                      <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                    </div>
                  </div>

                  <div class="answer-content">
                    {!! $answer->content !!}
                  </div>
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
<!-- CK EDITOR -->
<script type="text/javascript" src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $('#content').ckeditor({
        toolbar: 'Full',
        enterMode : CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P
    });
});
<script>
    $("#inputFile").change(function(event) {  
      fadeInAdd();
      getURL(this);    
    });

    $("#inputFile").on('click',function(event){
      fadeInAdd();
    });

    function getURL(input) {    
      if (input.files && input.files[0]) {   
        var reader = new FileReader();
        var filename = $("#inputFile").val();
        filename = filename.substring(filename.lastIndexOf('\\')+1);
        reader.onload = function(e) {
          debugger;      
          $('#imgView').attr('src', e.target.result);
          $('#imgView').hide();
          $('#imgView').fadeIn(500);      
          $('.custom-file-label').text(filename);             
        }
        reader.readAsDataURL(input.files[0]);    
      }
      $(".alert").removeClass("loadAnimate").hide();
    }

    function fadeInAdd(){
      fadeInAlert();  
    }
    function fadeInAlert(text){
      $(".alert").text(text).addClass("loadAnimate");  
    }
    CKEDITOR.replace('keterangan', {
    enterMode: Number(2),
    });

</script>

@endsection
