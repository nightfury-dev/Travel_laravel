<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\AccountType;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;


class CrmController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["name" => "CRM"]
        ];

        $users = User::where('account_type', '<>', 5)
            ->orderBy('created_at', 'desc')->get();

        return view('pages.admin.crm.index',compact('pageConfigs','breadcrumbs','users'));
    }

    public function create(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["name" => "CRM", "link" => "/crm"],["name" => "Create"] 
        ];

        $account_type = AccountType::where('id', '<>', 5)->get();

        return view('pages.admin.crm.create', compact('pageConfigs','breadcrumbs', 'account_type'));
    }

    public function edit(Request $request, $user_id){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["name" => "CRM", "link" => "/crm"],["name" => "Edit"] 
        ];

        $account_type = AccountType::where('id', '<>', 5)->get();
        $account = User::where('id', $user_id)->first();

        return view('pages.admin.crm.edit',compact('pageConfigs','breadcrumbs', 'account_type', 'account'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'main_email' => ['string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    public function add(Request $request){
        $msg_result = "";
        
        $this->validator($request->all())->validate();

        $user = User::create([
            'username' => $request->username,
            'email' => $request->main_email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'account_type' => $request->account_type,
            'avatar_path' => $request->avatar_path,
            'category' => $request->category,    
            'main_region_state' => $request->main_region_state,
            'main_postal_code' => $request->main_postal_code,
            'main_country' => $request->main_country,
            'main_city' => $request->main_city,
            'main_street_address' => $request->main_street_address,
            'main_street_number' => $request->main_street_number,
            'main_email' => $request->main_email,
            'main_office_phone' => $request->main_office_phone,
            'billing_company_name' => $request->company_name,
            'billing_postal_code' => $request->billing_postal_code,
            'billing_region_state' => $request->billing_region_state,
            'billing_country' => $request->billing_country,
            'billing_city' => $request->billing_city,
            'billing_street_number' => $request->billing_street_number,
            'billing_street_address' => $request->billing_street_address,
            'billing_email' => $request->billing_email,
            'billing_office_phone' => $request->billing_office_phone
        ]);

        if($request->ajax()){
            return response()->json(['result'=>'success']);
        }
        else {
            $msg_result = "account add success";
            return redirect()->route('admin.crm')->with('msg', $msg_result);
        }
    }

    public function update(Request $request){

        $update_data = array(
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'account_type' => $request->account_type,
            'avatar_path' => $request->avatar_path,
            'category' => $request->category,
            'main_region_state' => $request->main_region_state,
            'main_postal_code' => $request->main_postal_code,
            'main_country' => $request->main_country,
            'main_city' => $request->main_city,
            'main_street_address' => $request->main_street_address,
            'main_street_number' => $request->main_street_number,
            'main_email' => $request->main_email,
            'main_office_phone' => $request->main_office_phone,
            'billing_company_name' => $request->company_name,
            'billing_postal_code' => $request->billing_postal_code,
            'billing_region_state' => $request->billing_region_state,
            'billing_country' => $request->billing_country,
            'billing_city' => $request->billing_city,
            'billing_street_number' => $request->billing_street_number,
            'billing_street_address' => $request->billing_street_address,
            'billing_email' => $request->billing_email,
            'billing_office_phone' => $request->billing_office_phone
        );

        User::whereId($request->account_id)->update($update_data);
        
        $msg_result = "account update success";
        return redirect()->route('admin.crm')->with('msg', $msg_result);
    }

    public function avatar_upload(Request $request){
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|max:1000',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $status = "";
        $data = array(
            'flag' => '',
            'file_path' => ''
        );

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            // Rename image
            $filename = time().'.'.$image->guessExtension();
            
            $path = $image->storeAs(
                 'public/avatar', $filename
            );

            $avatar_path = 'avatar/'. $filename;

            $data = array(
                'flag' => 'uploaded',
                'file_path' => $avatar_path
            );
        }
        echo json_encode($data);
    }

    public function reset_password(Request $request){
        $user = User::where('id', $request->user_id_pwd)->first();
        $user->password = Hash::make($request->change_password);
        $user->save();
        return redirect()->back()->with('msg', 'change password success');
    }

    public function delete(Request $request){
        $account = User::find($request->user_id);
        $avatar = $account->avatar_path;

        if($account->delete()){
            if($avatar != '') {
                $file = 'public/' . $avatar;
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
            }
            $success = "Success!";
            return $success;
        }
    }
}

