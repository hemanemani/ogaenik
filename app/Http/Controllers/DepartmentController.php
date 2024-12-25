<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\DepartmentTranslation;
use App\Models\Product;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $brands = Department::orderBy('name', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.department.index', compact('brands', 'sort_search'));
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
        $brand = new Department;
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $brand->display_title = $request->display_title;
        $brand->logo = $request->logo;
        $brand->icon = $request->icon;
        $brand->save();

        $brand_translation = DepartmentTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'department_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Department has been inserted successfully'))->success();
        return redirect()->route('departments.index');

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
        $brand  = Department::findOrFail($id);
        return view('backend.product.department.edit', compact('brand','lang'));
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
        $brand = Department::findOrFail($id);
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
        $brand->display_title = $request->display_title;
        $brand->logo = $request->logo;
        $brand->icon = $request->icon;
        $brand->save();

        $brand_translation = DepartmentTranslation::firstOrNew(['lang' => $request->lang, 'department_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Department has been updated successfully'))->success();
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
        $brand = Department::findOrFail($id);
        Product::where('department_id', $brand->id)->forcedelete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->forcedelete();
        }
        Department::where('id', $id)->forcedelete();      

        flash(translate('Department has been deleted successfully'))->success();
        return redirect()->route('departments.index');

    }
    public function temdestroy($id)
    {
        $brand = Department::findOrFail($id);
        Product::where('department_id', $brand->id)->delete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->delete();
        }
        Department::where('id', $id)->delete();      

        flash(translate('Department has been deleted successfully'))->success();
        return redirect()->route('departments.index');

    }
    
}
