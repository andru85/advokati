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
        $this->middleware('permission:content-list');
        $this->middleware('permission:content-create', ['only' => ['create','store']]);
        $this->middleware('permission:content-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:content-delete', ['only' => ['destroy']]);
    }

    public function order_collection_by_weight($collection){
        foreach ($collection as $item){
            if(count($item->children) > 0){
                $this->order_collection_by_weight($item->children);
            }
        }
        return $collection->sortBy('weight');
    }

    public function index(Request $request)
    {
        $db_categories = Category::get()->toTree();
        $categories = $this->order_collection_by_weight($db_categories);

        foreach ($categories as $category){
            $category->children = $category->children->sortBy('weight');
        }
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
        $data = $request->except('_token','image');
        $category = new Category();
        $category->parent_id = $data['parent_id'];
        $category->weight = $data['weight'];
        $category->save();
        $default_locale = config('app.fallback_locale');

        foreach (config('app.available_locales') as $locale) {
            if (isset($data['name-' . $locale])) {
                $category->translateOrNew($locale)->name = $data['name-' . $locale];
            } else {
                $category->translateOrNew($locale)->name = $data['name-' . $default_locale];
            }
        }
        $category->save();
        return redirect()
            ->route('categories.index', app()->getLocale())
            ->with('success', 'Category successfully created!');
    }

    public function edit($locale, $id)
    {
        $category = Category::findOrFail($id);

        $categories = $this->getCategoryOptions($category);

        return view(env('THEME').'.admin.categories.edit', compact('category', 'categories'));
    }

    public function update(PostCategoryRequest $request, $locale, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->except('_token','image');
        $category->parent_id = $data['parent_id'];
        $category->weight = $data['weight'];
        foreach (config('app.available_locales') as $locale) {
            if (isset($data['name-' . $locale])) {
                $category->translateOrNew($locale)->name = $data['name-' . $locale];
            } else {
                $category->translateOrNew($locale)->name = $data['name-' . $default_locale];
            }
        }
        $category->update();
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
        $query = Category::select('id')->with('translations')->withDepth();

        if ($except)
        {
            $query->whereNotDescendantOf($except)->where('id', '<>', $except->id);
        }

        return $this->makeOptions($query->get());
    }
}
