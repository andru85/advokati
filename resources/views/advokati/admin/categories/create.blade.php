@extends(env('THEME').'.layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Create New Category</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('categories.index', app()->getLocale()) }}"> Back</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {!! Form::model($data, [ 'route' => ['categories.store', app()->getLocale()] ]) !!}
    @include(env('THEME').'.admin.categories.partials.form')

    <div class="form-group">
        {!! Form::submit('Create', [ 'class' => 'btn btn-primary' ]) !!}
    </div>
    {!! Form::close() !!}


@endsection

