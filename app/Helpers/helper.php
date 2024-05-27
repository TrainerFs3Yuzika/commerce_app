<?php
use App\Models\Category;

function getCategories(){
    return Category::orderBy('name','ASC')
    ->with('sub_category')
    ->orderBy('id','DESC')
    ->where('status',1)  //ini untuk mengedit dibagian edit category yang (active$inactive)
    ->where('showHome','Yes')
    ->get();
    
}
?>