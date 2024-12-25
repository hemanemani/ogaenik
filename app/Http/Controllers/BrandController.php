<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\User;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response 
     */
    public function index(Request $request)
    {
        // $brands = Brand::orderBy('created_at', 'desc')->get();
        $brands = Brand::withCount('products')
                   ->orderBy('created_at', 'desc')
                   ->get();
        
        foreach ($brands as $brand) {
        $brand->company_name = User::where('id', $brand->user_id)->value('companyname');
        }
        
        
        return view('backend.product.brands.index', compact('brands'));
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
        $brand = new Brand;
        $brand->name = $request->name;
		$brand->alpha_order = $request->alpha_order;
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

        $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Brand has been inserted successfully'))->success();
        return redirect()->route('brands.index');

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
        $brand  = Brand::findOrFail($id);
        $company_name = User::where('id', $brand->user_id)->value('companyname');
        
        
        $brandProducts = $brand->products;

        
        return view('backend.product.brands.edit', compact('brand','lang','company_name','brandProducts'));
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
		$user = User::where('brand_id',$id)->first();
		//dd($user);
		$user->avatar_original = $request->logo;
		$user->save();
		
        $brand = Brand::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $brand->name = $request->name;
        }
		$brand->alpha_order = $request->alpha_order;
        $brand->meta_title = $request->meta_title;
		$brand->status = 1;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = BrandTranslation::firstOrNew(['lang' => $request->lang, 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Brand has been updated successfully'))->success();
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
        $brand = Brand::findOrFail($id);
        Product::where('brand_id', $brand->id)->forcedelete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->forcedelete();
        }
        Brand::where('id', $id)->forcedelete(); 
        //Brand::destroy($id);

        flash(translate('Brand has been deleted successfully'))->success();
        return redirect()->route('brands.index');

    }
    public function temdestroy($id)
    {
        $brand = Brand::findOrFail($id);
        Product::where('brand_id', $brand->id)->delete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->delete();
        }
        Brand::where('id', $id)->delete(); 
        //Brand::destroy($id);

        flash(translate('Brand has been deleted successfully'))->success();
        return redirect()->route('brands.index');

    }
    public function updateFeatured(Request $request)
    {
		Product::where('brand_id', $request->id)
        ->update(['published' => $request->status]);
        $brand = Brand::findOrFail($request->id);
        $brand->status = $request->status;
        if($brand->save()){
            return 1;
        }
        return 0;
    }
}
