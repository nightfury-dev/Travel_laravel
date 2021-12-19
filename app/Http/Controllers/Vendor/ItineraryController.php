<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Message;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $pageConfigs = ['pageHeader' => true];

        $user_id = Auth::user()->id;

        $task_id_list = DB::table('task')
            ->select('task.id')
            ->leftjoin('task_detail', 'task_detail.task_id', '=', 'task.id')
            ->leftjoin('itinerary_daily', 'itinerary_daily.id', '=', 'task_detail.daily_id')
            ->leftjoin('itinerary', 'itinerary.id', '=', 'itinerary_daily.itinerary_id')
            ->leftjoin('product', 'product.id', '=', 'itinerary_daily.product_id')
            ->where('task.status', 1)
            ->where('product.supplier', $user_id)
            ->where('itinerary.status', '>=', 1)
            ->groupBy('task.id')
            ->get();

        $temp = array();
        foreach ($task_id_list as $id) {
            array_push($temp, $id->id);
        }

        $task_list = Task::whereIn('id', $temp)->orderBy('created_at', 'desc')->get();

        return view('pages.vendor.itinerary.index', compact('task_list', 'pageConfigs'));
    }

    public function detail(Request $request)
    {
        $task_id = $request->task_id;
        $pageConfigs = ['pageHeader' => true];
        $task = Task::find($task_id);
        $itinerary_id = $task->itinerary_id;
        $task_details = TaskDetail::where('task_id', $task_id)->orderBy('created_at', 'desc')->get();

        return view('pages.vendor.itinerary.detail', compact('task_details', 'task', 'pageConfigs', 'itinerary_id'));
    }

    public function contact(Request $request)
    {

        $task_id = $request->task_id;

        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Contact"],
        ];

        $task = Task::find($task_id);

        Message::where('link_id', $task_id)
            ->where('message_type', 2)
            ->where('to_id', Auth::user()->id)
            ->update(array(
                'status' => 1,
            ));

        $itinerary_id = Task::find($task_id)->itinerary_id;
        $itinerary = Itinerary::find($itinerary_id);

        $from_id = Auth::user()->id;
        $to_id = $task->assigned_to;

        if ($request->ajax()) {
            $search_string = $request->search_string;

            $contacts = Message::where('link_id', $task_id)
                ->where('message_type', 2)
                ->where('message', 'like', '%' . $search_string . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('pages.vendor.itinerary.contact_search', compact('task_id', 'contacts'))->render();
        }

        $contacts = Message::where('link_id', $task_id)
            ->where('message_type', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.vendor.itinerary.contact', compact('breadcrumbs', 'task', 'itinerary', 'contacts', 'from_id', 'to_id'));
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

    public function confirm(Request $request)
    {
        $task_id = $request->task_id;
        $task = Task::find($task_id);
        $task->status = 2;
        $task->save();
        Session::flash('msg', 'task approve');
        return redirect()->back();
    }

    public function decline(Request $request)
    {
        $task_id = $request->task_id;
        $task = Task::find($task_id);
        $task->status = -1;
        $task->save();
        Session::flash('msg', 'task decline');
        return redirect()->back();
    }
}