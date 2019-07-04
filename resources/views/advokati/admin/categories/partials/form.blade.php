@foreach(config('app.available_locales') as $locale)
<div class="form-group">
    {!! Form::label('name-'.$locale, 'Название '.$locale.':') !!}
    {!! Form::text('name-'.$locale, isset($category)?$category->translate($locale)->name:null, [ 'class' => 'form-control', 'autofocus' => true ]) !!}
    {!! $errors->first('name') !!}
</div>
@endforeach
<div class="form-group">
    {!! Form::label('weight', 'Вес:') !!}
    {!! Form::text('weight', isset($category)?$category->weight:1, [ 'class' => 'form-control', 'autofocus' => true ]) !!}
    {!! $errors->first('weight') !!}
</div>

<div class="form-group">
    {!! Form::label('parent_id', 'Parent:') !!}
    {!! Form::select('parent_id', $categories, isset($category)?$category->parent_id:null, [ 'class' => 'form-control' ]) !!}
    {!! $errors->first('parent_id') !!}
</div>