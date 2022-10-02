@extends('layout.layout')
@section('title', 'Home | Chat-Wink')


@section('content')
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <img src="{{ asset('uploads/' . auth()->user()->profile_pic) }}" alt="">
                    <div class="details">
                        <span>{{ auth()->user()->fname }} {{ auth()->user()->lname }}</span>
                        <p style="color:rgb(138, 132, 132)">{{ auth()->user()->status }}</p>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="logout">Logout</a>
            </header>
            <div class="search" urlink='{{ route('search') }}'>
                <span class="text">Select an user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button>
                    <span class="iconify" id="search-icon" data-icon="ant-design:search-outlined"></span>
                </button>
            </div>
            <form action="{{ route('get_convo') }}" method="post" id="getchat">
                {{-- <form action="{{ route('search') }}" method="post" id="getchat"> --}}
                {{-- <input type="text" name="search" placeholder="Enter name to search..."> --}}
                {{-- <input type="submit"> --}}
                @csrf
            </form>
            <style>
                .users-list a {
                    text-decoration: none;
                }

            </style>
            <div class="users-list">

            </div>
        </section>
    </div>
    <script src="{{ asset('js/convos.js') }}"></script>

@endsection
