<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Origin;
use App\Models\HomeCategory;
use App\Models\Product;
use App\Models\Language;
use App\Models\OriginTranslation;
use App\Utility\OriginUtility;
use Illuminate\Support\Str;

class OriginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $categories = Origin::orderBy('name', 'asc')->get();
           
        return view('backend.product.origin.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Origin::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.origin.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Origin;
        $category->name = $request->name;
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Origin::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category_translation = OriginTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'origin_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Origin has been inserted successfully'))->success();
        return redirect()->route('origins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $category = Origin::findOrFail($id);
        $categories = Origin::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', OriginUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)
            ->get();

        return view('backend.product.origin.edit', compact('category', 'categories', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		//dd($request->all());
        $category = Origin::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $category->name = $request->name;
        }
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
         //dd($category);
        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Origin::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }


        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category_translation = OriginTranslation::firstOrNew(['lang' => $request->lang, 'origin_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Origin::findOrFail($id);

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->forcedelete();
        }

        foreach (Product::where('origin_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        OriginUtility::delete_category($id);

        flash(translate('Origin has been deleted Permanently'))->success();
        return redirect()->route('origins.index');
    }
    public function temdestroy($id)
    {
        $category = Origin::findOrFail($id);

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('origin_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        OriginUtility::temp_delete_category($id);

        flash(translate('Origin has been deleted Temporarily'))->success();
        return redirect()->route('origins.index');
    }
    public function updateFeatured(Request $request)
    {
        $category = Origin::findOrFail($request->id);
        $category->featured = $request->status;
        if($category->save()){
            return 1;
        }
        return 0;
    }
}
