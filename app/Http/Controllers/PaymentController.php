<?php
namespace App\Http\Controllers;

use App\Models\ContentManagement;
use App\Libraries\Payment\Payment;
use App\Models\{Invoice,Company,InstallmentPayment,InstallmentPlan,Contract};
use \Stripe\Stripe;
use Illuminate\Support\Facades\Crypt;

class PaymentController
{
    private $_gateway;

    public function __construct()
    {
        // $this->_gateway = Payment::init();
    }

    public function pay($slug)
    {
       $invoice = Invoice::where('slug', $slug)->first();
        if (!$invoice) {
            return back()->with('error', 'Invoice not found.');
        }
        $contract = Contract::where('id', $invoice->contract_id)->first();
        if (!$contract) {
            return back()->with('error', 'Contract not found.');
        }else{
            if($contract->is_accept == 'pending'){
                return redirect()->route('contract.show',['contract' => $contract->slug ])->with('error', 'Please accept the contract first.');
            }

            if($contract->is_accept == 'rejected'){
                return redirect()->route('contract.show',['contract' => $contract->slug ])->with('error', 'Your contract is rejected.');
            }
        }


        $company = Company::getCompanyAdmin($invoice->company_id);

        if (!$company) {
            return back()->with('error', 'Company not found.');
        }

        if ($company->stripe_key_status == 'test') {
            if (!$company->test_publishable_key || !$company->test_secret_key) {
                return back()->with('error', 'Company Stripe test keys not set.');
            }
                    Stripe::setApiKey($company->test_secret_key);
        } else {
            if (!$company->live_publishable_key || !$company->live_secret_key) {
                return back()->with('error', 'Company Stripe live keys not set.');
            }

                    Stripe::setApiKey($company->live_secret_key);
        }
        if ($invoice->total > 999999.99) {
            return back()->with('error', 'The total amount exceeds the allowed Stripe limit of $999,999.99.');
        }
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $invoice->total * 100,
                    'product_data' => [
                        'name' => "Invoice #{$invoice->invoice_number}",
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', $invoice->slug),
            'cancel_url' => route('payment.cancel', $invoice->slug),
            'metadata' => [
                'invoice_id' => $invoice->invoice_number,
                'invoice_number' => $invoice->invoice_number
            ]
        ]);
        return redirect($session->url);
    }

     public function payInstallment($id)
        {
        $id = decrypt($id);

       $installmentPayment = InstallmentPayment::find($id);
       $InstallmentPlan = InstallmentPlan::where('id', $installmentPayment->installment_plan_id)->first();
       $invoice = Invoice::where('id', $InstallmentPlan->invoice_id)->first();
       if (!$invoice) {
            return back()->with('error', 'Invoice not found.');
        }

        $contract = Contract::where('id', $invoice->contract_id)->first();
        if (!$contract) {
            return back()->with('error', 'Contract not found.');
        }else{
            if($contract->is_accept == 'pending'){
                return redirect()->route('contract.show',['contract' => $contract->slug ])->with('error', 'Please accept the contract first.');
            }

            if($contract->is_accept == 'rejected'){
                return redirect()->route('contract.show',['contract' => $contract->slug ])->with('error', 'Please accept the contract first.');
            }
        }

        $company = Company::getCompanyAdmin($invoice->company_id);

        if (!$company) {
            return back()->with('error', 'Company not found.');
        }

        if ($company->stripe_key_status == 'test') {
            if (!$company->test_publishable_key || !$company->test_secret_key) {
                return back()->with('error', 'Company Stripe test keys not set.');
            }
                    Stripe::setApiKey($company->test_secret_key);
        } else {
            if (!$company->live_publishable_key || !$company->live_secret_key) {
                return back()->with('error', 'Company Stripe live keys not set.');
            }

                    Stripe::setApiKey($company->live_secret_key);
        }

           if ($installmentPayment->amount > 999999.99) {
            return back()->with('error', 'The total amount exceeds the allowed Stripe limit of $999,999.99.');
        }
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $installmentPayment->amount * 100,
                    'product_data' => [
                        'name' => "Invoice #{$invoice->invoice_number } installment #{$installmentPayment->installment_number}",
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment-installment.success', ['invoice' => $invoice->slug, 'installmentPayment' => encrypt($installmentPayment->id), 'installmentPlan' => encrypt($installmentPayment->installment_plan_id)]),
            'cancel_url' => route('payment.cancel', $invoice->slug),
            'metadata' => [
                'invoice_id' => $invoice->invoice_number,
                'invoice_number' => $invoice->invoice_number
            ]
        ]);

        return redirect($session->url);
    }

    public function installmentPaymentSuccess($invoiceSlug, $installmentPayment, $installmentPlan)
    {
        $data;
            $paymentId = Crypt::decrypt($installmentPayment);
            $planId = Crypt::decrypt($installmentPlan);

            $installmentPayment = InstallmentPayment::where('id', $paymentId)
                ->where('installment_plan_id', $planId)
                ->firstOrFail();

            $installmentPayment->update([
                'is_paid' => 1,
                'paid_at' => now(),
                'status' => 'paid',
            ]);

            $installmentPlan = InstallmentPlan::findOrFail($planId);
           $totalPayments = InstallmentPayment::where('installment_plan_id', $planId)
                ->where('is_paid', 1)
                ->count();
            // Check if plan is fully paid
            if ($installmentPlan->installment_count == $totalPayments) {
                $installmentPlan->update([
                    'status' => 'completed',
                ]);
            }

            $invoice = Invoice::where('slug', $invoiceSlug)->first();
            $invoice->paid_amount += $installmentPayment->amount;
            if ($invoice) {
                if ($totalPayments == '1') {
                    $invoice->status = 'partial';
                } elseif ($totalPayments == '2') {
                    $invoice->status = 'partial';
                } else {
                    $invoice->status = 'paid';
                }
                $invoice->save();
            }
            

            $data['invoice'] = $invoiceSlug;
            $data['installmentPayment'] = $installmentPayment;

        return view('portal.payment.installment-success', compact('data'));

    }


    public function paymentSuccess($invoice)
    {
        $invoice = Invoice::where('slug', $invoice)->firstOrFail();
        $invoice->status = 'paid';
        $invoice->paid_amount = $invoice->total;
        $invoice->save();

        return view('portal.payment.success', compact('invoice'));
    }

    public function paymentCancel($invoice)
    {
        $invoice = Invoice::where('slug', $invoice)->firstOrFail();

        return view('payment.cancel', compact('invoice'));
    }


}
