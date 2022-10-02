@extends('layout.layout')
@section('title', ' Chats | Chat-Wink')


@section('content')

    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="{{ route('convos') }}" class="back-icon">
                    <i class="fa fa-backward" aria-hidden="true"
                        style="color: #212529; font-size:22px; padding-right:5px;"></i>
                </a>
                <img src="{{ asset('uploads/' . $user->profile_pic) }}" alt="">
                <div class="details">
                    <span>{{ $user->fname }} {{ $user->lname }}</span>
                    <p class="status" style="color:rgb(138, 132, 132)"></p>
                </div>
            </header>
            <div class="chat-box">


            </div>
            <form action="{{ route('message', $user->id) }}" urlink="{{ route('getmessage', $user->id) }}"
                class="typing-area" method="post">
                @csrf
                <input class='message' type="text" name="message" class="input-field" placeholder="Type a message here..."
                    autocomplete="off">
                {{-- <input type="submit" value=""> --}}
                <button>
                    <i class="fa fa-paper-plane" aria-hidden="true" style="font-size: 25px"></i>
                </button>
            </form>
        </section>
    </div>

    <script src="{{ asset('js/chats.js') }}"></script>
@endsection
