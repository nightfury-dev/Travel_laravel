<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryDaily;
use App\Models\Message;
use App\Models\Product;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $itinerary_id = $request->itinerary_id;
        $type = $request->type;
        $pageConfigs = ['pageHeader' => true];

        $task_list = Task::where('itinerary_id', $itinerary_id)->get();
        $itinerary = Itinerary::find($itinerary_id);

        return view('pages.admin.task.index', compact('itinerary', 'itinerary_id', 'task_list', 'pageConfigs', 'type'));
    }

    public function edit(Request $request)
    {
        $task_id = $request->task_id;
        $task = array();
        $itinerary_id = $request->itinerary_id;
        if ($task_id != null) {
            $task = Task::find($task_id);
            $itinerary_id = $task->itinerary_id;
        }
        $itinerary = Itinerary::find($itinerary_id);

        $account = User::where('account_type', 4)->orWhere('account_type', 5)->get();
        foreach ($account as $acc) {
            $acc->title = $acc->get_account_type->title;
            $acc->account_id = $acc->get_account_type->id;
            $acc->user_id = $acc->id;
        }
        $current_user = Auth::user();
        $current_user->title = $current_user->get_account_type->title;

        $data = array();
        $data['task'] = $task;
        $data['account'] = $account;
        $data['current_account'] = $current_user;
        $data['result'] = "success";
        $data['itinerary_ref_num'] = $itinerary->reference_number;

        echo json_encode($data);
    }

    public function delete(Request $request)
    {
        $task_id = $request->task_id;

        if ($task = Task::find($task_id)) {
            if ($task->status == 2) {
                echo 'confirm';
            }
            Task::find($task_id)->delete();
            echo 'success';
        }
    }

    public function detail_delete(Request $request)
    {
        $detail_id = $request->detail_id;
        $task_id = TaskDetail::find($detail_id)->task_id;
        $task_status = Task::find($task_id)->status;
        if ($task_status == 2) {
            echo 'confirm';
        } else {
            TaskDetail::find($detail_id)->delete();
            echo 'success';
        }
    }

    public function save(Request $request)
    {
        $task_id = $request->task_id;
        $daily_id = $request->daily_id;

        $data = array();
        $data = $request->task_data;

        $account_id = Auth::user()->id;

        $data['assigned_by'] = $account_id;

        if ($task_id != 0) {
            $task = Task::find($task_id);
            $task->update($data);
            $data['mode'] = "update";

        } else {
            $task = new Task();
            $task->fill($data);
            $task->save();

            $daily_id_arr = explode(':', $daily_id);
            $temp_id = 0;
            $flag = 0;
            for ($i = 0; $i < count($daily_id_arr); $i++) {
                $product_id = ItineraryDaily::find($daily_id_arr[$i])->product_id;
                $supplier_id = Product::find($product_id)->supplier_id;
                if ($temp_id != 0 && $temp_id != $supplier_id) {
                    $data['result'] = "failure";
                    $data['message'] = "You have to set one task with services of same suppliers.";
                }
                $temp_id = $supplier_id;
            }

            foreach ($daily_id_arr as $da_id) {
                $new_record = TaskDetail::create([
                    'task_id' => $task->id,
                    'daily_id' => $da_id,
                ]);
            }

            $data['mode'] = "create";
        }
        $data['result'] = "success";
        return json_encode($data);
    }

    public function detail(Request $request)
    {
        $task_id = $request->task_id;
        $type = $request->type;
        $pageConfigs = ['pageHeader' => true];
        $task = Task::find($task_id);
        $itinerary_id = $task->itinerary_id;
        $task_details = TaskDetail::where('task_id', $task_id)->orderBy('created_at', 'desc')->get();

        return view('pages.admin.task.detail', compact('task_details', 'task', 'pageConfigs', 'type', 'itinerary_id'));
    }

    public function contact(Request $request)
    {

        $task_id = $request->task_id;

        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Contact"],
        ];

        $task = Task::find($task_id);
        if ($task->status == 2) {
            $msg = "Already Completed Task!";
            return redirect()->route('admin.itinerary')->with('msg', $msg);
        } else if ($task->status == -1) {
            $msg = "Closed Task!";
            return redirect()->route('admin.itinerary')->with('msg', $msg);
        }

        Message::where('link_id', $task_id)
            ->where('message_type', 2)
            ->where('to_id', Auth::user()->id)
            ->update(array(
                'status' => 1,
            ));

        $itinerary_id = Task::find($task_id)->itinerary_id;
        $itinerary = Itinerary::find($itinerary_id);

        $from_id = Auth::user()->id;
        $to_id = 0;
        $task_detail = TaskDetail::where('task_id', $task_id)->get()->first();
        if ($task_detail) {
            $daily_id = $task_detail->daily_id;
            $itinerary_daily = ItineraryDaily::find($daily_id);
            if ($itinerary_daily) {
                $product_id = $itinerary_daily->product_id;
                $supplier = Product::find($product_id)->supplier;
                $to_id = $supplier;
            }
        }

        if ($request->ajax()) {
            $search_string = $request->search_string;

            $contacts = Message::where('link_id', $task_id)
                ->where('message_type', 2)
                ->where('message', 'like', '%' . $search_string . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('pages.admin.task.contact_search', compact('task_id', 'contacts'))->render();
        }

        $contacts = Message::where('link_id', $task_id)
            ->where('message_type', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.admin.task.contact', compact('breadcrumbs', 'task', 'itinerary', 'contacts', 'from_id', 'to_id'));
    }

    public function send_message(Request $request)
    {
        $task_id = $request->task_id;
        $message_content = $request->message;
        $message_title = $request->title;
        $from_id = $request->from_id;
        $to_id = $request->to_id;

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

        $from_email = Auth::user()->email;
        $to_email = User::find($to_id)->email;

        // $data = [
        //     'body' => $message_content,
        //     ''
        // ];

        // $objDemo = new \stdClass();
        // $objDemo->to = $to_email;
        // $objDemo->from = Config::get('mail.from.address');
        // $objDemo->title = Config::get('mail.from.name');
        // $objDemo->file_path = $path;
        // $objDemo->subject = 'Travel Quoting Itinerary';

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
            'link_id' => $task_id,
            'from_id' => Auth::user()->id,
            'to_id' => $to_id,
            'message' => $message_content,
            'title' => $message_title,
            'file' => $full_file_name,
            'message_type' => 2,
            'status' => 0,
        ]);

        if ($new_record) {
            echo 'success';
            exit(1);
        }
    }

    public function delete_message(Request $request)
    {
        $itinerary_message = Message::find($request->message_id);
        if (!empty($itinerary_message)) {
            $itinerary_message->delete();
            $success = "success";
            return $success;
        }
    }
}