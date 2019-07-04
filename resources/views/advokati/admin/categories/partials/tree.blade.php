<?php //dd($categories) ?>
@unless ($categories->isEmpty())
    <ul class="category-tree">
        @foreach ($categories as $category)
            <li class="my-2">
                <span class="actions">
                    <a href="{{ route('categories.edit', [ app()->getLocale(), $category->getKey() ]) }}" title="Edit this category">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('categories.create', [ app()->getLocale(), 'parent_id' => $category->getKey() ]) }}" title="Create child">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                </span>

                {{ $category->weight }}. {{ $category->name }}

                @can('content-delete')
                    {!! Form::open(['method' => 'DELETE', 'route' => ['categories.destroy', app()->getLocale(), 'id' => $category->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger py-0 px-2 ml-2']) !!}
                    {!! Form::close() !!}
                @endcan

                @include(env('THEME').'.admin.categories.partials.tree', [ 'categories' => $category->children ])
            </li>
        @endforeach
    </ul>
@endunless