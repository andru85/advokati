<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Kalnoy\Nestedset\Collection;
use App\Http\Requests\PostCategoryRequest;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:category-list');
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $categories = Category::get()->toTree();
        return view(env('THEME').'.admin.categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $data = $request->only('parent_id');
        $categories = $this->getCategoryOptions();
        return view(env('THEME').'.admin.categories.create', compact('data', 'categories'));

        //return view(env('THEME').'.admin.categories.create');
    }

    public function store(PostCategoryRequest $request)
    {
        //dd($request);
        $category = Category::create($request->all());

        return redirect()
            ->route('categories.index', app()->getLocale())
            ->with('success', 'Category successfully created!');
    }

    public function edit($locale, $id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        $categories = $this->getCategoryOptions($category);

        return view(env('THEME').'.admin.categories.edit', compact('category', 'categories'));
    }

    public function update(PostCategoryRequest $request, $locale, $id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        $category->update($request->all());

        return redirect()->route('categories.index', [ app()->getLocale() ])->with('success', 'Category successfully updated!');
    }

    public function destroy($locale, $id)
    {
        DB::table("categories")->where('id', $id)->delete();
        return redirect()->route('categories.index', app()->getLocale())
            ->with('success','Category deleted successfully');
    }

    protected function makeOptions(Collection $items)
    {
        $options = [ '' => 'Root' ];

        foreach ($items as $item)
        {
            $options[$item->getKey()] = str_repeat('‒', $item->depth + 1).' '.$item->name;
        }

        return $options;
    }

    /**
     * @param Category $except
     *
     * @return CategoriesController
     */
    protected function getCategoryOptions($except = null)
    {
        /** @var \Kalnoy\Nestedset\QueryBuilder $query */
        $query = Category::select('id', 'name')->withDepth();

        if ($except)
        {
            $query->whereNotDescendantOf($except)->where('id', '<>', $except->id);
        }

        return $this->makeOptions($query->get());
    }
}
