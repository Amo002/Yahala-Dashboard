@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Welcome, {{ auth()->user()->name }}</h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card bg-dark text-white border border-light">
                <div class="card-body">
                    <h5 class="card-title">Dashboard Overview</h5>
                    <p class="card-text">This is the Yahala Admin Dashboard. Here you can manage your merchants, users, and roles.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
