<?php

namespace App\Http\Controllers;
use DB;
use  App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use Mail;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(Request $request){
        $meta_desc="Shop bán hàng văn phòng phẩm, ở đây có bán nhiều loại sản phẩm với mẫu đẹp, vừa rẻ vừa đẹp,
        nhiều sản phẩm với nhiểu thương hiệu khách hàng có nhiều sự lựa chọn ";
        $meta_keywords="Nhiều mẫu bút, gôm, vở, vừa ra mắt trên thị trường";
        $meta_title="Shop bán hàng văn phòng phẩm";
        $url_canonical=$request->url();

        

        $cate_product=DB::table('tbl_category_productse')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product=DB::table('tbl_brand_products')->where('brand_status','0')->orderby('brand_id','desc')->get();
        //$all_product=DB::table('tbl_product')
        //->join('tbl_category_productse','tbl_category_productse.category_id','=','tbl_product.category_id')
        //->join('tbl_brand_products','tbl_brand_products.brand_id','=','tbl_product.brand_id')
        //->orderby('tbl_product.product_id','desc')->get();
        $all_product=DB::table('tbl_product')->where('product_status','0')->orderby('product_id','desc')->limit(4)->get();
        return view('pages.home')->with('category',$cate_product)->with('brand',$brand_product)->with('all_product',$all_product)
        ->with('meta_desc', $meta_desc)->with('meta_keywords', $meta_keywords)->with('meta_title', $meta_title)->with('url_canonical', $url_canonical);//cách 1 
       // return view('pages.home')->with(compact('cate_product','brand_product','all_product'));//cách 2
    }
    public function search(Request $request){
         //seo 
         $meta_desc = "Tìm kiếm sản phẩm"; 
         $meta_keywords = "Tìm kiếm sản phẩm";
         $meta_title = "Tìm kiếm sản phẩm";
         $url_canonical = $request->url();
         //--seo
        $keywords=$request->keywords_submit;
        $cate_product=DB::table('tbl_category_productse')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product=DB::table('tbl_brand_products')->where('brand_status','0')->orderby('brand_id','desc')->get();
        //$all_product=DB::table('tbl_product')
        //->join('tbl_category_productse','tbl_category_productse.category_id','=','tbl_product.category_id')
        //->join('tbl_brand_products','tbl_brand_products.brand_id','=','tbl_product.brand_id')
        //->orderby('tbl_product.product_id','desc')->get();
        $search_product=DB::table('tbl_product')->where('product_name','like','%'.$keywords.'%')->get();
        return view('pages.sanpham.search')->with('category',$cate_product)->with('brand',$brand_product)->with('search_product',$search_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical);
    }
//send mail
public function send_mail(){
    $to_name="nhi";
    $to_email="xuannguyen.25022000@gmail.com";
    $data=array("name"=>"Mail từ tài khoản khách hàng","body"=>"Mail gửi về vấn đề hàng hoá");
    Mail::send('pages.send_mail',$data,function($message) use ($to_name,$to_email){
        $message->to($to_email)->subject('Test thử gửi mail google');//tiêu đề
        $message->from($to_email,$to_name);
    });
    //return redirect('/')->with('message','');
}
}
