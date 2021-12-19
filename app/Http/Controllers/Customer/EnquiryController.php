<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $pageConfigs = ['isContentSidebar' => true, 'bodyCustomClass' => 'email-application'];
        $user_id = Auth::user()->id;

        $link_id = 0;
        $message_id = 0;

        if ($request->link_id) {
            $link_id = $request->link_id;
            $message_id = $request->message_id;
        }

        if ($request->ajax()) {
            $search_string = $request->search_string;
            $status = $request->status;

            $enquiries = Enquiry::where('customer_id', $user_id)
                ->orderBy('created_at', 'desc');

            if ($search_string != '') {
                $enquiries->where('title', 'like', '%' . $search_string . '%');
            }

            if ($status != -1) {
                $enquiries->where('is_created_itinerary', $status);
            }

            $enquiries = $enquiries->paginate(10);
            $total_enquiry_count = count($enquiries);

            $data = array(
                'total_enquiry_count' => $total_enquiry_count,
                'enquiries' => $enquiries,
                'search_string' => $search_string,
            );

            return view('pages.customer.enquiry.search', $data)->render();
        }

        $enquiries = Enquiry::where('customer_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $total_enquiry_count = count($enquiries);

        return view('pages.customer.enquiry.index', [
            'pageConfigs' => $pageConfigs,
            'total_enquiry_count' => $total_enquiry_count,
            'enquiries' => $enquiries,
            'search_string' => '',
            'link_id' => $link_id,
            'message_id' => $message_id,
        ]);
    }

    public function create()
    {
        $staff = User::where('account_type', 4)
            ->orWhere('account_type', 5)
            ->orderBy('account_type')->get();

        return view('pages.customer.enquiry.create', compact('staff'));
    }

    public function add(Request $request)
    {

        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Dashboard"],
        ];

        $new_enquiry = new Enquiry;
        $new_enquiry->title = $request->title;
        $new_enquiry->customer_id = Auth::user()->id;
        $new_enquiry->staff_id = $request->staff_id;
        $new_enquiry->travel_number = $request->adults_num + $request->children_num;
        $new_enquiry->budget = $request->budget;
        $duration = $request->duration;
        $start_date = substr($duration, 0, -13);
        $time = strtotime($start_date);
        $new_enquiry->from_date = date('Y-m-d', $time);
        $end_date = substr($duration, -10);
        $time = strtotime($end_date);
        $new_enquiry->to_date = date('Y-m-d', $time);
        $new_enquiry->adult_number = $request->adults_num;
        $new_enquiry->children_number = $request->children_num;
        $new_enquiry->infant_number = 0;
        $new_enquiry->single_count = $request->single_room;
        $new_enquiry->double_count = $request->double_room;
        $new_enquiry->twin_count = $request->twin_room;
        $new_enquiry->triple_count = $request->triple_room;
        $new_enquiry->family_count = $request->family_room;
        if ($request->budget_per_total == 'per_person') {
            $new_enquiry->budget_per_total = 1;
        } elseif ($request->budget_per_total == 'total') {
            $new_enquiry->budget_per_total = 0;
        }

        $new_enquiry->is_created_itinerary = 0;
        $new_enquiry->note = $request->note;

        $all = Enquiry::all();
        if (count($all) == 0) {
            $ref_no = "E-1";
        } else {
            $max = 0;
            for ($i = 0; $i < count($all); $i++) {
                $str = explode('-', $all[$i]);
                $num = intval($str[1]);
                if ($num > $max) {
                    $max = $num;
                }

            }
            $max++;
            $ref_no = "E-" . $max;
        }

        $new_enquiry->reference_number = $ref_no;
        $new_enquiry->save();

        $enquiries = Enquiry::all();
        return redirect()->route('customer.enquiry')->with('msg', 'enquiry created');
    }

    public function edit(Request $request)
    {
        $custom_enquiry = Enquiry::where('id', $request->enquiry_id)->first();

        $staff = User::where('account_type', 4)
            ->orWhere('account_type', 5)
            ->orderBy('account_type')->get();

        return view('pages.customer.enquiry.edit', compact('custom_enquiry', 'staff'));
    }

    public function update(Request $request)
    {
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Dashboard"],
        ];

        $update_enquiry = new Enquiry;
        $update_enquiry->title = $request->title;
        $update_enquiry->customer_id = Auth::user()->id;
        $update_enquiry->staff_id = $request->staff_id;
        $update_enquiry->travel_number = $request->adults_num + $request->children_num;
        $update_enquiry->budget = $request->budget;
        $duration = $request->duration;
        $start_date = substr($duration, 0, -13);
        $time = strtotime($start_date);
        $update_enquiry->from_date = date('Y-m-d', $time);
        $end_date = substr($duration, -10);
        $time = strtotime($end_date);
        $update_enquiry->to_date = date('Y-m-d', $time);
        $update_enquiry->adult_number = $request->adults_num;
        $update_enquiry->children_number = $request->children_num;
        $update_enquiry->infant_number = 0;
        $update_enquiry->single_count = $request->single_room;
        $update_enquiry->double_count = $request->double_room;
        $update_enquiry->twin_count = $request->twin_room;
        $update_enquiry->triple_count = $request->triple_room;
        $update_enquiry->family_count = $request->family_room;
        if ($request->budget_per_total == 'per_person') {
            $update_enquiry->budget_per_total = 1;
        } elseif ($request->budget_per_total == 'total') {
            $update_enquiry->budget_per_total = 0;
        }

        $update_enquiry->note = $request->note;

        $custom_enquiry = Enquiry::where('id', $request->enquiry_id)->first();
        $ref_str = $custom_enquiry->reference_number;
        $ref_nums = explode('-', $ref_str);
        $ref_num = $ref_nums[1];

        $all_enquiry = Enquiry::all();
        $cnt = 0;
        for ($i = 0; $i < count($all_enquiry); $i++) {
            $ref_str = $all_enquiry[$i]->reference_number;
            $ref_nums = explode('-', $ref_str);
            if ($ref_nums[1] == $ref_num) {
                $tmp_enquries[$cnt] = $all_enquiry[$i];
                $cnt++;
            }
        }
        if ($cnt == 1) {
            $ref = 'E-' . $ref_num . '-1';
            $custom_enquiry->reference_number .= '-0';
            $update_enquiry->reference_number = $ref;
            $custom_enquiry->save();
            $update_enquiry->save();
        } else {
            $max = 0;
            for ($i = 0; $i < $cnt; $i++) {
                $ref_str = $tmp_enquries[$i]->reference_number;
                $ref_nums = explode('-', $ref_str);
                $to_num = intval($ref_nums[2]);
                if ($to_num > $max) {
                    $max = $to_num;
                }

            }
            $max++;
            $update_enquiry->reference_number = 'E-' . $ref_num . '-' . $max;
            $update_enquiry->is_created_itinerary = $custom_enquiry->is_created_itinerary;
            $update_enquiry->save();
        }
        return redirect()->route('customer.enquiry')->with('msg', 'enquiry updated');
    }

    public function delete(Request $request)
    {
        $enquiry = Enquiry::find($request->enquiry_id);
        if (!empty($enquiry)) {
            $enquiry->delete();
            $success = "success";
            return $success;
        }
    }

    public function delete_some_enquiry(Request $request)
    {
        $id_arr = $request->id_arr;
        for ($i = 0; $i < count($id_arr); $i++) {
            $enquiry = Enquiry::find($id_arr[$i]);
            if (!empty($enquiry)) {
                $enquiry->delete();
            }
        }

        $success = "success";
        return $success;
    }

    public function get_messages(Request $request)
    {
        $enquiry_id = $request->enquiry_id;
        $enquiry = Enquiry::find($enquiry_id);
        $messages = Message::where('link_id', $enquiry_id)
            ->where('message_type', 1)
            ->orderBy('created_at', 'desc')->get();

        Message::where('link_id', $enquiry_id)
            ->where('message_type', 1)
            ->where('to_id', Auth::user()->id)
            ->update(array(
                'status' => 1,
            ));

        $data = array(
            'enquiry' => $enquiry,
            'messages' => $messages,
        );

        return view('pages.customer.enquiry.message', $data)->render();
    }

    public function delete_message(Request $request)
    {
        $enquiry_message = Message::find($request->message_id);
        if (!empty($enquiry_message)) {
            $enquiry_message->delete();
            $success = "success";
            return $success;
        }
    }

    public function send_message(Request $request)
    {
        $enquiry_id = $request->enquiry_id;
        $message_content = $request->message;
        $message_title = $request->title;

        $origin_filename = "";
        $new_filename = "";
        $path = "";
        if ($request->hasFile('attach_file')) {
            $file = $request->file('attach_file');

            $origin_filename = $file->getClientOriginalName();
            $origin_filename = str_replace(",", "", $origin_filename);
            $origin_filename = str_replace(":", "", $origin_filename);

            $new_filename = time() . '.' . $file->guessExtension();

            $path = $file->storeAs(
                'public/message', $new_filename
            );
        }

        $enquiry = Enquiry::find($enquiry_id);
        $from_email = Auth::user()->email;
        $to_email = $enquiry->get_staff->email;

        // $data = [
        //     'body' => $message_content,
        //     ''
        // ];

        // $objDemo = new \stdClass();
        // $objDemo->to = $to_email;
        // $objDemo->from = Config::get('mail.from.address');
        // $objDemo->title = Config::get('mail.from.name');
        // $objDemo->file_path = $path;
        // $objDemo->subject = 'Travel Quoting Enquiry';

        // if($path == "") {
        //     Mail::send('pages.customer.enquiry.mail', $data, function ($message) use ($objDemo) {
        //         $message->from($objDemo->from, $objDemo->title);
        //         $message->to($objDemo->to);
        //         $message->subject($objDemo->subject);
        //     });
        // }
        // else {
        //     Mail::send('pages.customer.enquiry.mail', $data, function ($message) use ($objDemo) {
        //         $message->from($objDemo->from, $objDemo->title);
        //         $message->to($objDemo->to);
        //         $message->attach($objDemo->file_path);
        //         $message->subject($objDemo->subject);
        //     });
        // }

        $full_file_name = "";
        if ($origin_filename != "" && $new_filename != "") {
            $full_file_name = $new_filename . ':' . $origin_filename;
        }

        $new_record = Message::create([
            'link_id' => $enquiry_id,
            'from_id' => Auth::user()->id,
            'to_id' => $enquiry->get_staff->id,
            'message' => $message_content,
            'title' => $message_title,
            'file' => $full_file_name,
            'message_type' => 1,
            'status' => 0,
        ]);

        if ($new_record) {
            $new_record->from_first_name = $new_record->get_From->first_name;
            $new_record->from_last_name = $new_record->get_From->last_name;
            $new_record->from_avatar_path = $new_record->get_From->avatar_path;
            $new_record->from_username = $new_record->get_From->username;
            $new_record->from_email = $new_record->get_From->email;

            $new_record->to_first_name = $new_record->get_To->first_name;
            $new_record->to_last_name = $new_record->get_To->last_name;
            $new_record->to_avatar_path = $new_record->get_To->avatar_path;
            $new_record->to_username = $new_record->get_To->username;
            $new_record->to_email = $new_record->get_To->email;

            $new_record->enquiry_title = $new_record->get_Enquiry()->title;
            $new_record->enquiry_reference_number = $new_record->get_Enquiry()->reference_number;
            $new_record->format_created_at = date_format(date_create($new_record->created_at), "Y/m/d h:i");

            return json_encode(array(
                'flag' => 1,
                'new_record' => $new_record,
            ));
        }
    }
}