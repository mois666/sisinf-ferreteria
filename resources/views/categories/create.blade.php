@extends('layouts.app')

@section('content')
    <!-- partial -->
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Nueva categoría </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nueva categoría</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('categories.store') }}"
                            autocomplete="off">
                            @csrf
                            @include('categories.fields')

                            <button type="submit" class="btn btn-primary me-2">Guardar</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-dark">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:../../partials/_footer.html -->
@endsection
@section('css')
@endsection
@section('js')
@endsection
