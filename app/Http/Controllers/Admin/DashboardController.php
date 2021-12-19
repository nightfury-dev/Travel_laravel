<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Dashboard"],
        ];

        $user_id = Auth::user()->id;

        $itineraries = Itinerary::where('created_id', $user_id)
            ->orWhere('updated_id', $user_id)
            ->orderBy('created_at', 'desc')->get();

        $task_list = Task::where('assigned_to', $user_id)->orderBy('created_at', 'desc')->get();

        $msg = '';
        return view('pages.admin.dashboard', compact('task_list', 'pageConfigs', 'breadcrumbs', 'msg'));
    }

    public function profile()
    {
        return view('pages.admin.profile');
    }

    public function update_profile(Request $request)
    {

        $update_data = array(
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'avatar_path' => $request->avatar_path,
            'main_region_state' => $request->main_region_state,
            'main_postal_code' => $request->main_postal_code,
            'main_country' => $request->main_country,
            'main_city' => $request->main_city,
            'main_street_address' => $request->main_street_address,
            'main_street_number' => $request->main_street_number,
            'main_email' => $request->main_email,
            'main_office_phone' => $request->main_office_phone,
            'billing_postal_code' => $request->billing_postal_code,
            'billing_region_state' => $request->billing_region_state,
            'billing_country' => $request->billing_country,
            'billing_city' => $request->billing_city,
            'billing_street_number' => $request->billing_street_number,
            'billing_street_address' => $request->billing_street_address,
            'billing_email' => $request->billing_email,
            'billing_office_phone' => $request->billing_office_phone,
        );

        User::whereId(Auth::user()->id)->update($update_data);
        $msg_result = "profile update success";

        return back()->with('msg', $msg_result);
    }

    public function update_password(Request $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->new_user_pwd);
        $user->save();
        return back()->with('msg', 'change user password success');
    }

    public function fetch_message(Request $request)
    {
        $user_id = Auth::user()->id;

        $messages = DB::table('message')
            ->select('message.*', 'users.avatar_path')
            ->leftjoin('users', 'users.id', '=', 'message.from_id')
            ->where('message.to_id', $user_id)
            ->where('message.status', 0)
            ->get();

        return response()->json(array('new_messages' => $messages));
    }

    public function read_message(Request $request, $id)
    {
        $message = Message::find($id);
        $link_id = $message->link_id;
        $message_type = $message->message_type;

        if ($message_type == 1) {
            return redirect()->route('admin.enquiry', array(
                'link_id' => $link_id,
                'message_id' => $id,
            ));
        } else if ($message_type == 2) {
            return redirect()->route('admin.task.contact', array(
                'task_id' => $link_id,
            ));
        } else if ($message_type == 3) {
            return redirect()->route('admin.itinerary.contact', array(
                'itinerary_id' => $link_id,
            ));
        }
    }

    public function mark_allmessage(Request $request)
    {
        $user_id = Auth::user()->id;

        Message::where('to_id', $user_id)->update(array(
            'status' => 1,
        ));

        echo 'success';
    }
}