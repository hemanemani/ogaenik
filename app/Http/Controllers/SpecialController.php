<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Special;
use App\Models\SpecialTranslation;
use App\Models\Product;
use Illuminate\Support\Str;

class SpecialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $brands = Special::orderBy('name', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.special.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $brand = new Special;
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = SpecialTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'special_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('On Special has been inserted successfully'))->success();
        return redirect()->route('specials.index');

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
        $lang   = $request->lang;
        $brand  = Special::findOrFail($id);
        return view('backend.product.special.edit', compact('brand','lang'));
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
        $brand = Special::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $brand->name = $request->name;
        }
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = SpecialTranslation::firstOrNew(['lang' => $request->lang, 'special_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Special has been updated successfully'))->success();
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
        $brand = Special::findOrFail($id);
        Product::where('special_id', $brand->id)->forcedelete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->forcedelete();
        }
        Special::where('id', $id)->forcedelete(); 
                //Special::destroy($id);

        flash(translate('Special has been deleted successfully'))->success();
        return redirect()->route('specials.index');

    }
    public function temdestroy($id)
    {
        $brand = Special::findOrFail($id);
        Product::where('special_id', $brand->id)->delete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->delete();
        }
        Special::where('id', $id)->delete(); 
                //Special::destroy($id);

        flash(translate('Special has been deleted successfully'))->success();
        return redirect()->route('specials.index');

    }
	 public function updatePublished(Request $request)
    {
		//dd($request->all());
        $product = Special::findOrFail($request->id);
        $product->top = $request->status;

        if($product->added_by == 'seller' && \App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            $seller = $product->user->seller;
            if($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0){
                return 0;
            }
        }

        $product->save();
        return 1;
    }
}
