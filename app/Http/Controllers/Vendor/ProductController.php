<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTag;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\ProductGallery;
use App\Models\ProductPricing;
use App\Models\User;
use DB;
use Exception;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $pageConfigs = ['isContentSidebar' => true, 'bodyCustomClass' => 'file-manager-application'];

        if ($request->ajax()) {
            $search_string = $request->search_string;
            
            $firstGallery = DB::table('product_gallery')
                ->select(DB::raw('product_id', 'MAX(id) as max_gallery'))
                ->groupBy(['product_gallery.product_id']);

            $product = DB::table('product')
                ->select('product.*', 'category.title as category_title')
                ->joinSub($firstGallery, 'first_gallery', function ($join) {
                    $join->on('product.id', '=', 'first_gallery.product_id');
                })
                ->join('category', 'product.category', '=', 'category.id')
                ->orderBy('created_at', 'desc');

            if ($search_string != '') {
                $product->where('product.title', 'like', '%' . $search_string . '%');
            }

            $product = $product->paginate(18);

            $data = array(
                'product_gallery_model' => new ProductGallery,
                'product' => $product,
                'flag' => 'ajax',
            );

            return view('pages.vendor.product.search', $data)->render();
        }

        $product = Product::where('supplier', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(18);

        return view('pages.vendor.product.index', array(
            'pageConfigs' => $pageConfigs,
            'product' => $product,
            'flag' => 'no',
        ));
    }

    public function detail(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::find($product_id);

        $product_gallery = ProductGallery::where('product_id', $product_id)->get();
        $product_description = ProductDescription::where('product_id', $product_id)->get();

        $pricing_group_data = DB::table('product_pricing')
            ->select(DB::raw('count(*) as group_count, duration'))
            ->where('product_id', $product_id)
            ->groupBy('duration')
            ->get();

        $pricing_data = array();

        for ($i = 0; $i < count($pricing_group_data); $i++) {
            $pricing_group = ProductPricing::where('duration', $pricing_group_data[$i]->duration)->where('product_id', $product_id)->get();
            $temp = array(
                'duration' => $pricing_group_data[$i]->duration,
                'currency' => $pricing_group[0]->currency,
                'pricing_data' => array(),
            );

            for ($j = 0; $j < count($pricing_group); $j++) {
                $tt = array(
                    'id' => $pricing_group[$j]->id,
                    'tag' => $pricing_group[$j]->tag,
                    'price' => $pricing_group[$j]->price,
                );
                array_push($temp['pricing_data'], $tt);
            }
            array_push($pricing_data, $temp);
        }

        $data = array(
            'product' => $product,
            'product_gallery' => $product_gallery,
            'product_description' => $product_description,
            'product_pricing' => $pricing_data,
            'pricing_model' => new ProductPricing,
        );

        return view('pages.vendor.product.preview', $data)->render();
    }

    public function add(Request $request)
    {
        $pageConfigs = ['isContentSidebar' => true, 'bodyCustomClass' => 'file-manager-application'];

        $language = Language::all();
        $currency = Currency::all();

		$category = Category::where('parent_id', Auth::user()->category)->get();
		$category_tag = CategoryTag::where('parent_id', Auth::user()->category)->get();
		
        return view('pages.vendor.product.add', array(
            'pageConfigs' => $pageConfigs,
            'category' => $category,
            'category_tag' => $category_tag,
            'language' => $language,
            'currency' => $currency,
        ));
    }

    public function edit(Request $request)
    {
        $pageConfigs = ['isContentSidebar' => true, 'bodyCustomClass' => 'file-manager-application'];

        $product_id = $request->product_id;
        $product = Product::find($product_id);

        $category = Category::where('parent_id', Auth::user()->category)->get();
		$category_tag = CategoryTag::where('parent_id', Auth::user()->category)->get();

        $language = Language::all();
        $currency = Currency::all();

        $pricing_group_data = DB::table('product_pricing')
            ->select(DB::raw('count(*) as group_count, duration'))
            ->where('product_id', $product_id)
            ->groupBy('duration')
            ->get();

        $pricing_data = array();

        for ($i = 0; $i < count($pricing_group_data); $i++) {
            $pricing_group = ProductPricing::where('duration', $pricing_group_data[$i]->duration)->where('product_id', $product_id)->get();

            $temp = array(
                'duration' => $pricing_group_data[$i]->duration,
                'currency' => $pricing_group[0]->currency,
                'blackout' => $pricing_group[0]->blackout,
                'blackout_msg' => $pricing_group[0]->blackout_msg,
                'pricing_data' => array(),
            );

            for ($j = 0; $j < count($pricing_group); $j++) {
                $tt = array(
                    'id' => $pricing_group[$j]->id,
                    'tag' => $pricing_group[$j]->tag,
                    'description' => $pricing_group[$j]->description,
                    'price' => $pricing_group[$j]->price,
                );
                array_push($temp['pricing_data'], $tt);
            }
            array_push($pricing_data, $temp);
        }

        return view('pages.vendor.product.edit', array(
            'pageConfigs' => $pageConfigs,
            'category' => $category,
            'category_tag' => $category_tag,
            'language' => $language,
            'product' => $product,
            'currency' => $currency,
            'pricing_data' => $pricing_data,
        ));
    }

    public function delete(Request $request)
    {
        $product_id = $request->product_id;

        $product = Product::find($product_id);
        if ($product->delete()) {
            ProductDescription::where('product_id', $product_id)->delete();
            $gallery = ProductGallery::where('product_id', $product_id)->get();
            ProductGallery::where('product_id', $product_id)->delete();
            for ($i = 0; $i < count($gallery); $i++) {
                $path = $gallery[$i]->path;
                $file = 'public/' . $path;
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
            }
            ProductPricing::where('product_id', $product_id)->delete();
        }

        $request->session()->flash('alert', 'Deleted Successfully');
        return response()->json(['msg' => 'success']);
    }

    public function tag(Request $request)
    {
        $category = $request->category;
        $city = CategoryTag::where('parent_id', $category)->get();
        echo json_encode($city);
    }

    public function save(Request $request)
    {

        $rule = [
            'title' => 'required',
            'category' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            // 'autocomplete' => 'required'
        ];

        $customMessages = [
            'title.required' => 'Title filed is required',
            'category.required' => 'Category filed is required',
            'start_time.required' => 'Start Time filed is required',
            'end_time.required' => 'End Time filed is required',
            // 'autocomplete.required' => 'Location filed is required'
        ];

        $data = [];
        $validator = \Validator::make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['messages'] = $validator->errors()->all();
            return response()->json($data);
        }

        try {
            $product = Product::updateOrCreate(
                ['id' => $request->general_product_id],
                [
                    'title' => $request->title,
                    'category' => $request->category,
                    'zip' => $request->zip,
                    'country' => $request->country,
                    'city' => $request->city,
                    'position' => $request->position,
                    'state' => $request->state,
                    'street_address' => $request->street_number . ' ' . $request->street_address,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'supplier' => Auth::user()->id,
                ]
            );

            $data['success'] = true;
            $data['messages'] = 'Saved Successfully';
            $data['product_id'] = $product->id;

            return response()->json($data);
        } catch (Exception $e) {
            $data['success'] = false;
            $data['messages'] = $e->getMessage();
            return response()->json($data);
        }
    }

    public function product_description(Request $request)
    {
        $rule = [
            'group."*".language' => 'required',
            'group."*".description' => 'required',
        ];

        $customMessages = [
            'group."*".language.required' => 'Language field is required',
            'group."*".description.required' => 'Description field is required',
        ];

        $data = [];
        $validator = \Validator::make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['messages'] = $validator->errors()->all();
            return response()->json($data);
        }

        $product_id = $request->description_product_id;
        if (empty($product_id)) {
            $product = Product::create(
                [
                    'title' => "Test Product plz enter correct infomation",
                    'category' => 0,
                    'zip' => '',
                    'country' => '',
                    'city' => '',
                    'position' => '',
                    'state' => '',
                    'street_address' => '',
                    'start_time' => '',
                    'end_time' => '',
                    'supplier' => 0,
                ]
            );

            $product_id = $product->id;
        }

        ProductDescription::where('product_id', $product_id)->delete();

        

        $description_data = $request->group;
        $flag = 0;

        foreach ($description_data as $description) {
            ProductDescription::create(
                [
                    'id' => $description["'descriptionID'"],
                    'product_id' => $product_id,
                    'language' => $description["'language'"],
                    'description' => $description["'description'"],
                ]);
            $flag = 1;
        }

        if ($flag == 1) {
            $data['success'] = true;
            $data['messages'] = 'Saved Successfully';
            $data['product_id'] = $product_id;
            return response()->json($data);
        } else {
            $data['success'] = false;
            $data['messages'] = 'Any Error Occurs. Please try it again.';
            return response()->json($data);
        }
    }

    public function upload_gallery(Request $request)
    {
        if ($request->hasFile('file')) {
            $product_id = $request->gallery_product_id;
            if (empty($product_id)) {
                $product = Product::create(
                    [
                        'title' => "Test Product plz enter correct infomation",
                        'category' => 0,
                        'zip' => '',
                        'country' => '',
                        'city' => '',
                        'position' => '',
                        'state' => '',
                        'street_address' => '',
                        'start_time' => '',
                        'end_time' => '',
                        'supplier' => 0,
                    ]
                );

                $product_id = $product->id;
            }

            $data = [];
            $flag = 0;
            $files = $request->file('file');

            foreach ($files as $file) {
                if ($file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $upload_filename = $filename . time() . '.' . $extension;

                    $path = $file->storeAs(
                        'public/gallery', $upload_filename
                    );

                    if ($path) {
                        $upload = ProductGallery::create([
                            'product_id' => $product_id,
                            'path' => 'gallery/' . $upload_filename,
                            'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                        ]);
                    } else {
                        $flag = 1;
                    }
                } else {
                    $flag = 1;
                }
            }

            if ($flag == 0) {
                $data['success'] = true;
                $data['messages'] = 'Saved Successfully';
                $data['product_id'] = $product_id;

                return response()->json($data, 200);
            } else {
                $data['success'] = false;
                $data['messages'] = 'Upload Error. Please try it again.';
                return response()->json($data, 400);
            }
        }
    }

    public function delete_gallery(Request $request)
    {
        $file_name = $request->file_name;
        $path = 'gallery/' . $file_name;
        ProductGallery::where('path', $path)->first()->delete();

        $file = 'public/' . $path;
        if (Storage::exists($file)) {
            Storage::delete($file);
        }
        echo 'success';
    }

    public function product_pricing(Request $request)
    {
        $rule = [
            'fromdate.*' => 'required',
            'todate.*' => 'required',
            'currency.*' => 'required',
            'blackoutdate.*.*' => 'required',
            'blackoutmsg.*.*' => 'required',
            'tag.*.*' => 'required',
            'price.*.*' => 'required',
        ];

        $customMessages = [
            'fromdate.*.required' => 'From Date field is required',
            'todate.*.required' => 'To Date field is required',
            'currency.*.required' => 'Currency field is required',
            'blackoutdate.*.*.required' => 'Blackout Date field is required',
            'blackoutmsg.*.*.required' => 'Blackout Note field is required',
            'tag.*.*.required' => 'Type field is required',
            'price.*.*.required' => 'Price field is required',
        ];

        $validator = \Validator::make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['messages'] = $validator->errors()->all();
            return response()->json($data);
        }

        // $this->validate($request, $rule, $customMessages);

        $product_id = $request->price_product_id;
        if (empty($product_id)) {
            $product = Product::create(
                [
                    'title' => "Test Product plz enter correct infomation",
                    'category' => 0,
                    'zip' => '',
                    'country' => '',
                    'city' => '',
                    'position' => '',
                    'state' => '',
                    'street_address' => '',
                    'start_time' => '',
                    'end_time' => '',
                    'supplier' => 0,
                ]
            );

            $product_id = $product->id;
        }

        $flag = 0;

        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $currency = $request->currency;

        $tag = $request->tag;
        $price = $request->price;
        $description = $request->description;
        $priceID = $request->priceID;

        $kk = 0;
        $tag_temp = array();
        foreach ($tag as $tg) {
            $tag_temp[$kk] = $tg;
            $kk++;
        }

        $kk = 0;
        $price_temp = array();
        foreach ($price as $pr) {
            $price_temp[$kk] = $pr;
            $kk++;
        }

        $kk = 0;
        $description_temp = array();
        foreach ($description as $dr) {
            $description_temp[$kk] = $dr;
            $kk++;
        }

        $kk = 0;
        $priceID_temp = array();
        foreach ($priceID as $pi) {
            $priceID_temp[$kk] = $pi;
            $kk++;
        }

        $blackoutdate = $request->blackoutdate;

        $kk = 0;
        $blackoutdate_arr = array();

        if (!empty($blackoutdate)) {
            foreach ($blackoutdate as $bkdate) {
                $blackoutdate_arr[$kk] = $bkdate;
                $kk++;
            }
        }

        $blackoutmsg = $request->blackoutmsg;

        $kk = 0;
        $blackoutmsg_arr = array();

        if (!empty($blackoutmsg)) {
            foreach ($blackoutmsg as $bkmsg) {
                $blackoutmsg_arr[$kk] = $bkmsg;
                $kk++;
            }
        }

        // $kk = 0;
        // $blackoutdate = $request->blackoutdate;
        // $blackoutmsg = $request->blackoutmsg;

        // $blackoutdate_arr = array();
        // $blackoutmsg_arr = array();
        // while($kk < count($blackoutdate)) {
        //     $blackoutdate_arr[$kk] = $blackoutdate[$kk];
        //     $blackoutmsg_arr[$kk] = $blackoutmsg[$kk];
        //     $kk++;
        // }

        $blackout_d = array();
        $blackout_m = array();

        for ($i = 0; $i < count($fromdate); $i++) {
            $blackoutdate_temp = "";
            $blackoutmsg_temp = "";

            if (!empty($blackoutdate_arr)) {
                for ($j = 0; $j < count($blackoutdate_arr[$i]); $j++) {
                    if ($j == 0) {
                        $blackoutdate_temp .= $blackoutdate_arr[$i][$j];
                        $blackoutmsg_temp .= $blackoutmsg_arr[$i][$j];
                    } else {
                        $blackoutdate_temp .= ', ';
                        $blackoutdate_temp .= $blackoutdate_arr[$i][$j];

                        $blackoutmsg_temp .= ', ';
                        $blackoutmsg_temp .= $blackoutmsg_arr[$i][$j];
                    }
                }
            }

            array_push($blackout_d, $blackoutdate_temp);
            array_push($blackout_m, $blackoutmsg_temp);
        }

        $origin_pricing_id_arr = ProductPricing::where('product_id', $product_id)->pluck('id')->toArray();
        $new_pricing_id_arr = array();

        for ($i = 0; $i < count($fromdate); $i++) {
            for ($j = 0; $j < count($tag_temp[$i]); $j++) {
                if ($priceID_temp[$i][$j] != null) {
                    array_push($new_pricing_id_arr, intval($priceID_temp[$i][$j]));

                    $product_pricing = ProductPricing::find($priceID_temp[$i][$j])->update([
                        'product_id' => $product_id,
                        'tag' => $tag_temp[$i][$j],
                        'description' => $description_temp[$i][$j],
                        'price' => $price_temp[$i][$j],
                        'currency' => $currency[$i],
                        'duration' => $fromdate[$i] . ' ~ ' . $todate[$i],
                        'blackout' => $blackout_d[$i],
                        'blackout_msg' => $blackout_m[$i],
                    ]);
                } else {
                    $product_pricing = ProductPricing::create(
                        [
                            'product_id' => $product_id,
                            'tag' => $tag_temp[$i][$j],
                            'description' => $description_temp[$i][$j],
                            'price' => $price_temp[$i][$j],
                            'currency' => $currency[$i],
                            'duration' => $fromdate[$i] . ' ~ ' . $todate[$i],
                            'blackout' => $blackout_d[$i],
                            'blackout_msg' => $blackout_m[$i],
                        ]
                    );
                }
                $flag = 1;
            }
        }

        //dd($new_pricing_id_arr);

        $diff_arr = array_diff($origin_pricing_id_arr, $new_pricing_id_arr);
        foreach ($diff_arr as $df) {
            ProductPricing::find($df)->delete();
        }

        if ($flag == 1) {
            // $request->session()->flash('alert', 'Saved Successfully');
            // return redirect()->route('product_edit', ['product_id' => $product_id]);
            $data['success'] = true;
            $data['messages'] = 'Saved Successfully';
            $data['product_id'] = $product_id;
            return response()->json($data);
        } else {
            // return redirect()->back();
            $data['success'] = false;
            $data['messages'] = 'Any Error Occurs. Please try it again.';
            return response()->json($data);
        }
    }
}
