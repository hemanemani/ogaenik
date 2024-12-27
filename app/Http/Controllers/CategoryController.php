<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\HomeCategory;
use App\Models\Product;
use App\Models\Language;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;
use App\Models\CategoryProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProductCategoryExport;
use App\Exports\SelectedCategoryProductExport;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        // $categories = Category::all();      
        
        $categories = Category::with('products')->get();
    
        foreach ($categories as $category) {
            $category->total_products_count = $this->getTotalProductsCount($category);
        }
 
        return view('backend.product.categories.index', compact('categories'));
    }
    
    private function getTotalProductsCount($category)
    {
        $allProducts = collect($category->products);
    
        $subcategories = Category::where('parent_id', $category->id)->with('products')->get();
    
        foreach ($subcategories as $subcategory) {
            $allProducts = $allProducts->merge($subcategory->products);
    
            $subSubcategories = Category::where('parent_id', $subcategory->id)->with('products')->get();
            
            foreach ($subSubcategories as $subSubcategory) {
                $allProducts = $allProducts->merge($subSubcategory->products);
            }
        }
        return $allProducts->count();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
		$category->specification = $request->specification;		
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
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

        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
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
    
    public function export_bulk_category_product(Request $request) 
	{		
        $productcategories = Category::with(['parentCategory.parentCategory', 'products'])
        ->where('level', 2)
        ->get();
        return Excel::download(new CategoryProductExport($productcategories), 'categories_with_products.xlsx');
	} 
    
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
		//dd($request->all());
        //$lang = $request->lang;
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)
            ->get();
            
        $subcategories = Category::where('parent_id', $category->id)->get();
        
        $allProducts = collect();
        
        $allProducts = $allProducts->merge($category->products);
        
        foreach ($subcategories as $subcategory) {
            
            $allProducts = $allProducts->merge($subcategory->products);
            
            $subSubcategories = Category::where('parent_id', $subcategory->id)->get();
            foreach ($subSubcategories as $subSubcategory) {
                $allProducts = $allProducts->merge($subSubcategory->products);
            }
        }
            
            
        // $categoryProducts = $category->products;

        return view('backend.product.categories.edit', compact('category', 'categories','allProducts'));
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
        $category = Category::findOrFail($id);
       $category->name = $request->name;
			$category->specification = $request->specification;	
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $request->meta_title;
        $category->change_slug = $request->change_slug;
        $category->meta_description = $request->meta_description;
         //dd($category);
        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if($request->change_slug == 1){
             $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }else{
             if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
            }
            else {
                $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
            }
        }


        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
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
        $category = Category::findOrFail($id);

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->forcedelete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::temp_delete_category($id);

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }
    public function temdestroy($id)
    {
        $category = Category::findOrFail($id);

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::delete_category($id);

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        if($category->save()){
            return 1;
        }
        return 0;
    }
    public function product_category(){
        
        
        $products = Product::with([
            'category',
            'category.parentCategory',
            'category.parentCategory.parentCategory'
        ])->get();
        
        // dd($products);

        
        return view('backend.product.categories.product_categories',compact('products'));

    }
    
    public function export_bulk_product_category(Request $request) 
	{		
        $productwithcategories = Product::with([
            'category',
            'category.parentCategory',
            'category.parentCategory.parentCategory'
        ])->get();
        
        return Excel::download(new ProductCategoryExport($productwithcategories), 'product_with_categories.xlsx');
	} 
	
	
	public function export_selected_category_products(Request $request){
	    
         $categoryId = $request->input('product_category');
         

        if (!$categoryId) {
            return back()->with('error', 'Please select a category.');
        }
        
        // Check if the selected category is a subcategory or sub-subcategory
        $category = Category::find($categoryId);
        
        
        // If it's a sub-subcategory, fetch its products directly
        if ($category->parent_id && $category->parentCategory && $category->parentCategory->parent_id) {
        $products = Product::where('category_id', $categoryId)->get();
        }
        
        // If it's a subcategory, fetch the products for that subcategory and its sub-subcategories
        elseif ($category->parent_id) {
            $categoryIds = $this->getAllCategoryIds($categoryId);
            $products = Product::whereIn('category_id', $categoryIds)->get();
        }
        // If it's a main category, fetch the products for that category and all its descendants
        else {
            $categoryIds = $this->getAllCategoryIds($categoryId);
            $products = Product::whereIn('category_id', $categoryIds)->get();
        }
        
        
        
        if ($products->isEmpty()) {
        return back()->with('error', 'No products found for the selected category.');
        }
        
         $fileName = $category->name . '_products.xlsx';
    
        return Excel::download(new SelectedCategoryProductExport($products), $fileName);
    }
	
	 private function getAllCategoryIds($categoryId)
    {
        $categories = Category::where('parent_id', $categoryId)->get();
        $ids = $categories->pluck('id')->toArray();
    
        foreach ($categories as $category) {
            $ids = array_merge($ids, $this->getAllCategoryIds($category->id)); // Recursive call for subcategories
        }
    
        return $ids;
    }

    
    
}
