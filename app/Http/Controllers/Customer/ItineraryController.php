<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryCustomerInformation;
use App\Models\ItineraryInvoice;
use App\Models\Message;
use App\Models\User;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Session;
use Validator;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $pageConfigs = ['pageHeader' => true];
        $user_id = Auth::user()->id;

        $itineraries = Itinerary::select('itinerary.*')
            ->join('enquiry', 'enquiry.id', '=', 'itinerary.enquiry_id')
            ->where('enquiry.customer_id', $user_id)
            ->wherein('itinerary.status', [2, 3])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.customer.itinerary.index', compact('itineraries', 'pageConfigs'));
    }

    public function customer_info(Request $request)
    {
        $itinerary_id = $request->id;
        $customer_information = ItineraryCustomerInformation::where('itinerary_id', $itinerary_id)->first();
        return view('pages.customer.itinerary.customer_info', compact('itinerary_id', 'customer_information'));
    }

    public function send_customer_info(Request $request)
    {
        $itinerary_id = $request->itinerary_id;
        $customer_information = $request->customer_information;

        $exist = ItineraryCustomerInformation::where('itinerary_id', $itinerary_id)->first();

        if ($exist) {
            Session::flash('msg', 'already send customer info');
            return redirect()->back();
        } else {
            ItineraryCustomerInformation::create([
                'itinerary_id' => $itinerary_id,
                'customer_infomation' => $customer_information,
            ]);

            Session::flash('msg', 'success send customer info');
            return redirect()->route('customer.itinerary')->with('msg', );
        }
    }

    public function messages(Request $request)
    {

        $itinerary_id = $request->id;

        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Contact"],
        ];

        $itinerary = Itinerary::find($itinerary_id);

        Message::where('link_id', $itinerary_id)
            ->where('message_type', 3)
            ->where('to_id', Auth::user()->id)
            ->update(array(
                'status' => 1,
            ));

        $from_id = Auth::user()->id;
        $to_id = $itinerary->updated_id;

        if ($request->ajax()) {
            $search_string = $request->search_string;

            $contacts = Message::where('link_id', $itinerary_id)
                ->where('message_type', 3)
                ->where('message', 'like', '%' . $search_string . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('pages.customer.itinerary.message_search', compact('itinerary_id', 'contacts'))->render();
        }

        $contacts = Message::where('link_id', $itinerary_id)
            ->where('message_type', 3)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.customer.itinerary.message', compact('breadcrumbs', 'itinerary_id', 'itinerary', 'contacts', 'from_id', 'to_id'));
    }

    public function send_message(Request $request)
    {
        $itinerary_id = $request->itinerary_id;
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
        //     Mail::send($task_id = $request->task_id;

        $full_file_name = "";
        if ($origin_filename != "" && $new_filename != "") {
            $full_file_name = $new_filename . ':' . $origin_filename;
        }

        $new_record = Message::create([
            'link_id' => $itinerary_id,
            'from_id' => Auth::user()->id,
            'to_id' => $to_id,
            'message' => $message_content,
            'title' => $message_title,
            'file' => $full_file_name,
            'message_type' => 3,
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

    public function invoice(Request $request)
    {
        $itinerary_id = $request->id;
        $itinerary = Itinerary::find($itinerary_id);
        $itinerary_invoice = ItineraryInvoice::where('itinerary_id', $itinerary_id)->get();

        if ($itinerary->status == 3) {
            $msg_result = "completed itinerary";
            return redirect()->route('customer.itinerary')->with('msg', $msg_result);
        } else {
            return view('pages.customer.itinerary.invoice', compact('itinerary_id', 'itinerary_invoice', 'itinerary'));
        }
    }

    public function invoice_detail(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $itinerary_invoice = ItineraryInvoice::find($invoice_id);
        $itinerary_id = $itinerary_invoice->itinerary_id;
        $itinerary = Itinerary::find($itinerary_id);
        
        return view('pages.customer.itinerary.invoice_detail', compact('itinerary_id', 'itinerary_invoice', 'invoice_id', 'itinerary'));
    }

    public function invoice_buy(Request $request)
    {
        $itinerary_id = $request->id;
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"], ["name" => "Invoice"],
        ];

        $itinerary_id = $request->itinerary_id;
        $total_amount = $request->total_amount;
        $currency = $request->currency;

        return view('pages.customer.itinerary.pay', compact('itinerary_id', 'breadcrumbs', 'pageConfigs', 'total_amount', 'currency'));
    }

    public function invoice_pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
        ]);

        if ($validator->passes()) {

            // $stripe = Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $stripe = \Cartalyst\Stripe\Stripe::make(env('STRIPE_SECRET_KEY'));

            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $request->get('card_no'),
                        'exp_month' => $request->get('ccExpiryMonth'),
                        'exp_year' => $request->get('ccExpiryYear'),
                        'cvc' => $request->get('cvvNumber'),
                    ],
                ]);
                if (!isset($token['id'])) {
                    return redirect()->route('customer.itinerary.invoice', ['id', $request->itinerary_id])->with('msg', 'Token Creation Failed');
                }
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => $request->currency,
                    'amount' => number_format($request->amount, 2),
                    'description' => 'wallet',
                ]);

                if ($charge['status'] == 'succeeded') {
                    $itinerary = Itinerary::find($request->itinerary_id);
                    $itinerary->status = 3;
                    $itinerary->save();



                    return redirect()->route('customer.itinerary')->with('msg', 'payment Success');
                } else {
                    return redirect()->route('customer.itinerary.invoice', ['id' => $request->itinerary_id])->with('msg', 'payment Failed');
                }
            } catch (Exception $e) {
                return redirect()->route('customer.itinerary.invoice', ['id' => $request->itinerary_id])->with('msg', $e->getMessage());
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                return redirect()->route('customer.itinerary.invoice', ['id' => $request->itinerary_id])->with('msg', $e->getMessage());
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                return redirect()->route('customer.itinerary.invoice', ['id' => $request->itinerary_id])->with('msg', $e->getMessage());
            }
        }
    }

    public function preview_itinerary(Request $request)
    {

    }
}