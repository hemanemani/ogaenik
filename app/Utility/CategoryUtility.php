<?php

namespace App\Utility;

use App\Models\Category;

class CategoryUtility
{
    /*when with trashed is true id will get even the deleted items*/
    public static function get_immediate_children($id, $with_trashed = false, $as_array = false)
    {
        $children = $with_trashed ? Category::where('parent_id', $id)->get() : Category::where('parent_id', $id)->get();
        $children = $as_array && !is_null($children) ? $children->toArray() : array();

        return $children;
    }

    public static function get_immediate_children_ids($id, $with_trashed = false)
    {

        $children = CategoryUtility::get_immediate_children($id, $with_trashed, true);

        return !empty($children) ? array_column($children, 'id') : array();

    }

    /*when with trashed is true id will get even the deleted items*/
    public static function flat_children($id, $with_trashed = false, $container = array())
    {
        $children = CategoryUtility::get_immediate_children($id, $with_trashed, true);

        if (!empty($children)) {
            foreach ($children as $child) {

                $container[] = $child;
                $container = CategoryUtility::flat_children($child['id'], $with_trashed, $container);

            }
        }

        return $container;
    }

    /*when with trashed is true id will get even the deleted items*/
    public static function children_ids($id, $with_trashed = false)
    {
        $children = CategoryUtility::flat_children($id, $with_trashed = false);

        return !empty($children) ? array_column($children, 'id') : array();
    }

    public static function move_children_to_parent($id)
    {
        $children_ids = CategoryUtility::get_immediate_children_ids($id, true);

        $category = Category::where('id', $id)->first();

        CategoryUtility::move_lelel_up($id);

        Category::whereIn('id', $children_ids)->update(['parent_id' => $category->parent_id]);

    }

    public static function move_lelel_up($id){
        if (CategoryUtility::get_immediate_children_ids($id, true) > 0) {
            foreach (CategoryUtility::get_immediate_children_ids($id, true) as $value) {
                $category = Category::find($value);
                $category->level -= 1;
                $category->save();
                return CategoryUtility::move_lelel_up($value);
            }
        }
    }

    public static function delete_category($id)
    {
        $category = Category::where('id', $id)->first();
        if (!is_null($category)) {
            CategoryUtility::move_children_to_parent($category->id);
            $category->delete();
        }

    }
    public static function temp_delete_category($id)
    {
        $category = Category::where('id', $id)->first();
        if (!is_null($category)) {
            CategoryUtility::move_children_to_parent($category->id);
            $category->forcedelete();
        }

    }
    
}
