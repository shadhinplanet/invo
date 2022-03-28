<?php

namespace App\Http\Controllers;

use App\Events\ActivityEvent;
use App\Mail\InvoiceEmail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    /**
     * Display a listing of invoices.
     *
     * @return view with invoices with filter
     */
    public function index(Request $request)
    {
        // Get latest invoices
        $invoices = Invoice::with('client')->where('user_id',Auth::id())->latest();

        // filter by clients
        if (!empty($request->client_id)) {
            $invoices = $invoices->where('client_id', $request->client_id);
        }

        // filter by status
        if (!empty($request->status)) {
            $invoices = $invoices->where('status', $request->status);
        }

        // filter by email sent
        if (!empty($request->emailsent)) {
            $invoices = $invoices->where('email_sent', $request->emailsent);
        }

        // paginate
        $invoices = $invoices->paginate(10);

        // return view with clients and invoices
        return view('invoice.index')->with([
            'clients'  => Client::where('user_id', Auth::user()->id)->get(),
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show the form for creating a new invoice.
     *
     * @return view with clients and tasks
     */
    public function create(Request $request)
    {
        $tasks = false;
        // If client id and status is not empty
        if (!empty($request->client_id) && !empty($request->status)) {
            // validation
            $request->validate([
                'client_id' => ['required', 'not_in:none'],
                'status' => ['required', 'not_in:none'],
            ]);
            // get tasks from request
            $tasks = $this->getInvoiceData($request);
        }

        // Return view with clients and tasks
        return view('invoice.create')->with([
            'clients' => Client::where('user_id', Auth::user()->id)->get(),
            'tasks' => $tasks
        ]);
    }


    /**
     * Update the specified invoice in database.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        try {
            // update status
            $invoice->update([
                'status'    => $invoice->status == 'unpaid' ?  'paid' : 'unpaid'
            ]);
            // return response
            return redirect()->route('invoice.index')->with('success', 'Invoice payment status updated!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('invoice.index')->with('error', $th->getMessage());
        }
    }


    /**
     * Remove the specified invoice from database.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        try {
            // delete pdf file
            Storage::delete('public/invoices/' . $invoice->download_url);
            // delete data from database
            $invoice->delete();
            // return response
            return redirect()->route('invoice.index')->with('success', 'Invoice Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('invoice.index')->with('error', $th->getMessage());
        }
    }


    /**
     * Get invoice from request
     *
     * @param Request $request
     *
     * @return tasks
     */
    public function getInvoiceData(Request $request)
    {
        try {
            // Get latest tasks
            $tasks = Task::latest();

            // filter by clients
            if (!empty($request->client_id)) {
                $tasks = $tasks->where('client_id', '=', $request->client_id);
            }

            // filter by status
            if (!empty($request->status)) {
                $tasks = $tasks->where('status', '=', $request->status);
            }

            // filter by from date
            if (!empty($request->fromDate)) {
                $tasks = $tasks->whereDate('created_at', '>=', $request->fromDate);
            }

            // filter by end date
            if (!empty($request->endDate)) {
                $tasks = $tasks->whereDate('created_at', '<=', $request->endDate);
            }

            // return tasks
            return $tasks->get();
        } catch (\Throwable $th) {
            // return false
            return false;
        }
    }



    /**
     * Preview or Generate new invoice
     *
     * @param Request $request
     *
     * @return response or view
     */
    public function inovice(Request $request)
    {
        // if rewuest is generate
        if (!empty($request->generate) && $request->generate == 'yes') {
            try {
                // generate invoice pdf
                $this->generate($request);
                // return resposne
                return redirect()->route('invoice.index')->with('success', 'Invocie Created');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->route('invoice.index')->with('error', $th->getMessage());
            }
        }

        // if request is preview invoice
        if (!empty($request->preview) && $request->preview == 'yes') {
            // check discount and discount type
            if (!empty($request->discount) && !empty($request->discount_type)) {
                $discount = $request->discount;
                $discount_type = $request->discount_type;
            } else {
                $discount = 0;
                $discount_type = '';
            }
            // get tasks from request ids
            $tasks = Task::whereIn('id', $request->invoice_ids)->get();

            // return view with invoice
            return view('invoice.preview')->with([
                'invoice_no'  => 'INVO_' . rand(23435252, 235235326532),
                'user'  => Auth::user(),
                'tasks' => $tasks,
                'discount' => $discount,
                'discount_type' => $discount_type,
            ]);
        }
    }


    /**
     * Generate invoice pdf
     *
     * @param Request $request
     *
     * @return none
     */
    public function generate(Request $request)
    {
        // check discount and discount type
        if (!empty($request->discount) && !empty($request->discount_type)) {
            $discount = $request->discount;
            $discount_type = $request->discount_type;
        } else {
            $discount = 0;
            $discount_type = '';
        }

        // generate invoice random id
        $invo_no  = 'INVO_' . rand(23435252, 235235326532);

        // get tasks from request ids
        $tasks = Task::whereIn('id', $request->invoice_ids)->get();

        // get all data into an array
        $data = [
            'invoice_no'    => $invo_no,
            'user'          => Auth::user(),
            'tasks'         => $tasks,
            'discount'      => $discount,
            'discount_type' => $discount_type,
        ];

        // PDF generate with data
        $pdf = PDF::loadView('invoice.pdf', $data);

        // Store PDF in Storage
        Storage::put('public/invoices/' . $invo_no . '.pdf', $pdf->output());

        // Insert Invoice data
        Invoice::create([
            'invoice_id'    => $invo_no,
            'client_id'     => $tasks->first()->client->id,
            'user_id'       => Auth::user()->id,
            'status'        => 'unpaid',
            'amount'        => $tasks->sum('price'),
            'download_url'  => $invo_no . '.pdf'
        ]);
        event(new ActivityEvent('Invoice '.$invo_no.' Generated','Invoice', Auth::id()));
    }

    /**
     * Send email with the attachment
     *
     * @param Invoice $invoice
     *
     * @return response
     */
    public function sendEmail(Invoice $invoice)
    {
        try {
            // get all data into an array
            $data = [
                'user'          => Auth::user(),
                'invoice_id'    => $invoice->invoice_id,
                'invoice'       => $invoice,
                'pdf'           => public_path('storage/invoices/' . $invoice->download_url),
            ];

            // Email initialize with Mailable and Queue
            Mail::to($invoice->client)->send(new InvoiceEmail($data));

            // update invoice email sent status
            $invoice->update([
                'email_sent'    => 'yes'
            ]);

            // return response
            return redirect()->route('invoice.index')->with('success', 'Email sent');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('invoice.index')->with('error', $th->getMessage());
        }
    }
}
