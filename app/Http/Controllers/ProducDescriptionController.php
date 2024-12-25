<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDescription;
use App\Models\Product;

class ProducDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $desc = ProductDescription::all(); 
        return view('backend.product.product_description.index',compact('desc'));

    }
    
        public function show($id)
    {
            $productDescription = ProductDescription::findOrFail($id);

            $headingToDelete = $productDescription->heading;
    
            $productDescription->delete();
    
            $productsToUpdate = Product::whereNotNull('dynamic_product_heading')
            ->where('dynamic_product_heading', '!=', '[]')
            ->whereNotNull('dynamic_product_desc')
            ->where('dynamic_product_desc', '!=', '[]')
            ->get();
            

    
            foreach ($productsToUpdate as $product) {
                $headings = json_decode($product->dynamic_product_heading, true);
                $descriptions = json_decode($product->dynamic_product_desc, true);
                
               
    
                 if (is_array($headings) && is_array($descriptions) && count($headings) === count($descriptions)) {
                    $indicesToRemove = array_keys($headings, $headingToDelete);
        
                    $updatedHeadings = array_filter($headings, function ($heading) use ($headingToDelete) {
                        return $heading !== $headingToDelete;
                    });
        
                    foreach ($indicesToRemove as $index) {
                        unset($descriptions[$index]);
                    }
        
                    $updatedHeadings = array_values($updatedHeadings);
                    $updatedDescriptions = array_values($descriptions);
        
                    if (count($updatedHeadings) !== count($headings)) {
                        $product->dynamic_product_heading = json_encode($updatedHeadings);
                        $product->dynamic_product_desc = json_encode($updatedDescriptions);
                        $product->save();
                    }
                }
            }
    
            flash(translate('Product description heading and related product headings have been updated successfully'))->success();
            return redirect()->route('product-description.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.product.product_description.create');
    }
    
     public function store(Request $request)
    {
        $desc = new ProductDescription;
        $desc->heading = $request->heading;

        $desc->save();
        
        flash(translate('New Heading has been created successfully'))->success();
        return redirect()->route('product-description.index');

    }
    
      public function edit(Request $request, $id)
    {
        $descheading = ProductDescription::findOrFail($id);
        return view('backend.product.product_description.edit', compact('descheading'));
    }
    
      public function update(Request $request, $id)
        {
            $productDescription = ProductDescription::findOrFail($id);
           
            $oldHeading = $productDescription->heading;
            
            $productDescription->heading = $request->heading;
            $productDescription->save();
            
            
            
            $productsToUpdate = Product::whereNotNull('dynamic_product_heading')
                               ->where('dynamic_product_heading', '!=', '[]')
                                ->whereJsonContains('dynamic_product_heading', $oldHeading)
                               ->get();
                               
      
           
            foreach ($productsToUpdate as $product) {
                $headings = json_decode($product->dynamic_product_heading, true); 
                    
                 $updatedHeadings = collect($headings)->map(function ($heading) use ($oldHeading, $request) {
                return $heading === $oldHeading ? $request->heading : $heading;
                
                })->toArray();
                
               
                $product->dynamic_product_heading = json_encode($updatedHeadings);
                $product->save();
            }
    
            
            flash(translate('Heading has been updated successfully'))->success();
            return redirect()->route('product-description.index');
        }
    
      
    
         public function destroy($id)
        {
             $productDescription = ProductDescription::findOrFail($id);

            $headingToDelete = $productDescription->heading;
    
            $productDescription->delete();
    
            $productsToUpdate = Product::whereNotNull('dynamic_product_heading')
            ->where('dynamic_product_heading', '!=', '[]')
            ->whereNotNull('dynamic_product_desc')
            ->where('dynamic_product_desc', '!=', '[]')
            ->get();
            

    
            foreach ($productsToUpdate as $product) {
                $headings = json_decode($product->dynamic_product_heading, true);
                $descriptions = json_decode($product->dynamic_product_desc, true);
                
               
    
                 if (is_array($headings) && is_array($descriptions) && count($headings) === count($descriptions)) {
                    $indicesToRemove = array_keys($headings, $headingToDelete);
        
                    $updatedHeadings = array_filter($headings, function ($heading) use ($headingToDelete) {
                        return $heading !== $headingToDelete;
                    });
        
                    foreach ($indicesToRemove as $index) {
                        unset($descriptions[$index]);
                    }
        
                    $updatedHeadings = array_values($updatedHeadings);
                    $updatedDescriptions = array_values($descriptions);
        
                    if (count($updatedHeadings) !== count($headings)) {
                        $product->dynamic_product_heading = json_encode($updatedHeadings);
                        $product->dynamic_product_desc = json_encode($updatedDescriptions);
                        $product->save();
                    }
                }
            }
    
            flash(translate('Product description heading and related product headings have been updated successfully'))->success();
            return redirect()->route('product-description.index');
    }
    
}
