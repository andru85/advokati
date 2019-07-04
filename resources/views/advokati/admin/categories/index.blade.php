@extends(env('THEME').'.layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Category Management</h2>
            </div>
            <div class="pull-right">
                @can('permission-create')
                    <a class="btn btn-success" href="{{ route('categories.create', app()->getLocale()) }}"> Create New Category</a>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include(env('THEME').'.admin.categories.partials.tree')

@endsection