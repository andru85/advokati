@extends(env('THEME').'.layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Permission Management</h2>
            </div>
            <div class="pull-right">
                @can('permission-create')
                    <a class="btn btn-success" href="{{ route('permissions.create', app()->getLocale()) }}"> Create New Permission</a>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Guard name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($permissions as $key => $permission)
            <tr>
                <td>{{ $permission->id }}</td>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->guard_name }}</td>
                <td>
                    @can('permission-edit')
                        <a class="btn btn-primary" href="{{ route('permissions.edit', [app()->getLocale(), $permission->id]) }}">Edit</a>
                    @endcan
                    @can('permission-delete')
                        {!! Form::open(['method' => 'DELETE','route' => ['permissions.destroy', 'locale' => app()->getLocale(), 'id' => $permission->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>


    {!! $permissions->render() !!}


@endsection