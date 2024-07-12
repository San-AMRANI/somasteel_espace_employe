@extends('layouts.app')
@push('vite')
    @vite(['resources/js/department.js'])
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between my-3">
        <h2 class="mb-2">
            <b class="border-bottom border-black border-2 no-break"><em>{{__('Annuaire Département')}} </em></b>
        </h2>
        <input type="text" id="departmentFilter" class="form-control mx-1 mb-2" placeholder="Search departments...">
    </div>
    <div class="row departments">
        @foreach ($departments as $department)
            <div class="col-lg-3 col-md-4 col-sm-6 department-item" data-service="{{ $department }}">
                <a class="nav-link card department-card" href="{{ route('annuaire.depart', $department) }}">
                    <div class="card-body text-center">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <h5 class="card-title">{{ __($department) }}</h5>
                    </div>
                </a>
            </div>
        @endforeach
        <div class="col-lg-3 col-md-4 col-sm-6 department-item">
            <a class="nav-link card add-department-card" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                <div class="card-body text-center">
                    <i class="fas fa-plus fa-3x mb-3"></i>
                    <h5 class="card-title">{{ __('Créer un Département') }}</h5>
                </div>
            </a> 
        </div>
    </div>
    {{-- new depart --}}
    <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="createDepartmentModalLabel">Crée un Nouveau Département</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createDepartmentForm" action="{{ route('annuaire.depart.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="nomService" class="form-label text-center">Nom de Département</label>
                            <input type="text" class="form-control" id="nomService" name="nomService" required>
                            <div class="invalid-feedback">
                                Nom Département est requis.
                            </div>
                        </div>
                        <div class="modal-footer py-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
