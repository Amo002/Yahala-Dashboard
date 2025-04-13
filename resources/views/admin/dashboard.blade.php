@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-primary">Welcome, {{ auth()->user()->name }}</h1>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Users</h6>
                            <h2 class="mt-2 mb-0">150</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Active Merchants</h6>
                            <h2 class="mt-2 mb-0">45</h2>
                        </div>
                        <i class="bi bi-shop fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Roles</h6>
                            <h2 class="mt-2 mb-0">8</h2>
                        </div>
                        <i class="bi bi-shield-lock fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pending Tasks</h6>
                            <h2 class="mt-2 mb-0">12</h2>
                        </div>
                        <i class="bi bi-list-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-plus text-success me-2"></i>
                                New user registered
                                <small class="text-muted d-block">2 minutes ago</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-shop text-primary me-2"></i>
                                New merchant added
                                <small class="text-muted d-block">15 minutes ago</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-shield-lock text-info me-2"></i>
                                Role permissions updated
                                <small class="text-muted d-block">1 hour ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people me-2"></i> Manage Users
                        </a>
                        <a href="{{ route('admin.merchants.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-shop me-2"></i> Manage Merchants
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-shield-lock me-2"></i> Manage Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
