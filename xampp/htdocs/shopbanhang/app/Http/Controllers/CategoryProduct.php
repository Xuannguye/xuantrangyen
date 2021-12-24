<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use  App\Http\Requests;
use Session;
use App\Category;
use Illuminate\Support\Facades\Redirect;
session_start();

class CategoryProduct extends Controller
{
    public function AuthLogin()
    {
        $admin_id=Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }

    }
    
    public function add_category_product(){
        $this->AuthLogin();
        return view('admin.add_category_product');
    }
    public function all_category_product(){
        $this->AuthLogin();
      

        $all_category_product =  Category::orderBy('category_id','DESC')->get();

        $manager_category_product  = view('admin.all_category_product')->with('all_category_product',$all_category_product);
        return view('admin_layout')->with('admin.all_category_product', $manager_category_product);


    }

     
    public function save_category_product(Request $request){
        $this->AuthLogin();
        // $data=array();
        // $data['category_name']=$request->category_product_name;
        // $data['category_desc']=$request->category_product_desc;
        // $data['meta_keywords']=$request->category_product_keywords;
        // $data['category_status']=$request->category_product_status;
        // DB::table('tbl_category_productse')->insert($data);
        // Session::put('message','Thêm danh mục sản phẩm thành công');
        // return Redirect::to('add-category-product');
        $data=$request->all();
        $category=new Category();
        $category->category_name=$data['category_product_name'];
        $category->category_desc=$data['category_product_desc'];
        $category->meta_keywords=$data['category_product_keywords'];
        $category->category_status=$data['category_product_status'];
        $category->save();
        Session::put('message','Thêm danh mục sản phẩm thành công');
        return Redirect::to('add-category-product');
    }

    public function unactive_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_productse')->where('category_id',$category_product_id)->update(['category_status'=>1]);
        Session::put('message','Không kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }

    public function active_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_productse')->where('category_id',$category_product_id)->update(['category_status'=>0]);
        Session::put('message','Kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }
    public function edit_category_product($category_product_id){
        $this->AuthLogin();
        $edit_category_product=Category::where('category_id',$category_product_id)->get();
        $manager_category_product=view('admin.edit_category_product')->with('edit_category_product',$edit_category_product);
        return view('admin_layout')->with('admin.edit_category_product',$manager_category_product);

    }

 

    public function update_category_product (Request $request,$category_product_id){
        $this->AuthLogin();
        // $data=array();
        // $data['category_name']=$request->category_product_name;
        // $data['category_desc']=$request->category_product_desc;
        // $data['meta_keywords']=$request->category_product_keywords;
        // DB::table('tbl_category_productse')->where('category_id',$category_product_id)->update($data);
        $data=$request->all();
        $category=Category::find($category_product_id);
        $category->category_name=$data['category_product_name'];
        $category->category_desc=$data['category_product_desc'];
        $category->meta_keywords=$data['category_product_keywords'];
        
        $category->save();

        Session::put('message','Cập nhật danh mục sản phẩm thành công');

        return Redirect::to('all-category-product');
    }

    public function delete_category_product ($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_productse')->where('category_id',$category_product_id)->delete();
        Session::put('message','Xoá danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }
//End Function Admin Page
    public function show_category_home(Request $request,$category_id){

        $cate_product=DB::table('tbl_category_productse')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product=DB::table('tbl_brand_products')->where('brand_status','0')->orderby('brand_id','desc')->get();
        $category_by_id=DB::table('tbl_product')->join('tbl_category_productse','tbl_product.category_id','=','tbl_category_productse.category_id')
        ->where('tbl_product.category_id',$category_id)->get();
        $category_name=DB::table('tbl_category_productse')->where('tbl_category_productse.category_id',$category_id)->limit(1)->get();
        foreach( $category_name as $key =>$val){
            //seo
            $meta_desc=$val->category_desc;
            $meta_keywords=$val->meta_keywords;
            $meta_title=$val->category_name;
            $url_canonical=$request->url();
            //---seo
        }

      
        return view('pages.category.show_category')->with('category',$cate_product)->with('brand',$brand_product)->with('category_by_id',$category_by_id)
        ->with('category_name',$category_name)->with('meta_desc', $meta_desc)->with('meta_keywords', $meta_keywords)->with('meta_title', $meta_title)->with('url_canonical', $url_canonical);
    }
   
}
