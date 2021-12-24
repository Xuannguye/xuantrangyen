<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false; //set time to false
    protected $fillable = [
    	 'category_name','category_desc','meta_keywords','category_status'
    ];
    protected $primaryKey = 'category_id';
 	protected $table = 'tbl_category_productse';

 	public function product(){
 		return $this->hasMany('App\Product');
 	}
}
