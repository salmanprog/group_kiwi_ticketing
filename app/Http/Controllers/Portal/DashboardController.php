<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\{CmsWidget, CompanyUser, Estimate};
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use CustomHelper;
use DB;
use Auth;

class DashboardController extends Controller
{
    public function adminIndex()
    {
        if (Auth::user()->user_type !== 'admin') {
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }

        $data['page_title'] = 'Dashboard';
        $data['widgets'] = [
            'company' => [
                'title' => 'Companies',
                'count' => DB::table('users')->where('user_type', 'company')->count(),
                'link'  => route('company-management.index'),
                'icon'  => 'fa fa-building',
                'color' => 'bg-danger',
            ],
            'manager' => [
                'title' => 'Managers',
                'count' => DB::table('users')->where('user_type', 'manager')->count(),
                'link'  => route('manager-management.index'),
                'icon'  => 'fa fa-user-tie',
                'color' => 'bg-primary',
            ],
            'client' => [
                'title' => 'Clients',
                'count' => DB::table('users')->where('user_type', 'client')->count(),
                'link'  => route('client-management.index'),
                'icon'  => 'fa fa-users',
                'color' => 'bg-success',
            ],
            'salesman' => [
                'title' => 'Salesmen',
                'count' => DB::table('users')->where('user_type', 'salesman')->count(),
                'link'  => route('salesman-management.index'),
                'icon'  => 'fa fa-user-tag',
                'color' => 'bg-warning',
            ],
        ];

        $months = collect(range(1, 12))->map(fn ($m) =>
            Carbon::create()->month($m)->format('M')
        );

        $estimateData = [];
        $contractData = [];

        foreach (range(1, 12) as $month) {
            $estimateData[] = DB::table('user_estimate')
                ->whereMonth('created_at', $month)
                ->count();

            $contractData[] = DB::table('contracts')
                ->whereMonth('created_at', $month)
                ->count();
        }

        $data['line_chart'] = [
            'labels' => $months,
            'estimates' => $estimateData,
            'contracts' => $contractData,
        ];
        
        $data['pie_chart'] = [
            'labels' => ['Companies', 'Managers', 'Clients', 'Salesmen'],
            'data' => [
                DB::table('users')->where('user_type', 'company')->count(),
                DB::table('users')->where('user_type', 'manager')->count(),
                DB::table('users')->where('user_type', 'client')->count(),
                DB::table('users')->where('user_type', 'salesman')->count(),
            ],
        ];

        return $this->__cbAdminView('dashboard.admin-index', $data);
    }


    public function companyIndex()
    {
        if (Auth::user()->user_type !== 'company') {
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }

        $companyId = CompanyUser::getCompany(Auth::user()->id)->id;

        $data['page_title'] = 'Dashboard';

        /* =======================
        | Widgets
        ======================= */
        $data['widgets'] = [
            'manager' => [
                'title' => 'Accounts',
                // 'count' => DB::table('users')
                //     ->join('company_users', 'company_users.user_id', '=', 'users.id')
                //     ->where('users.user_type', 'manager')
                //     // ->where('company_users.company_id', $companyId)
                //     ->count(),
                'count' => DB::table('organizations')
                     ->where('auth_code', Auth::user()->auth_code)
                    ->count(),
                'link' => route('organization.index'),
                'icon' => 'fa fa-user-tie',
                'color' => 'bg-primary',
            ],
            'client' => [
                'title' => 'Contacts',
                'count' => DB::table('users') ->where('auth_code', Auth::user()->auth_code)
                    //->join('company_users', 'company_users.user_id', '=', 'users.id')
                    ->where('users.user_type', 'client')
                    // ->where('company_users.company_id', $companyId)
                    ->count(),
                'link' => route('client-management.index'),
                'icon' => 'fa fa-users',
                'color' => 'bg-success',
            ],
            'salesman' => [
                'title' => 'Estimates',
                'count' => DB::table('user_estimate')
                    //->join('company_users', 'company_users.user_id', '=', 'users.id')
                    ->where('auth_code', Auth::user()->auth_code)
                    // ->where('company_users.company_id', $companyId)
                    ->count(),
                'link' => route('estimate.index'),
                'icon' => 'fa fa-user-tag',
                'color' => 'bg-warning',
            ],
            'contract' => [
                'title' => 'Contracts',
                'count' => DB::table('contracts')
                     ->where('auth_code', Auth::user()->auth_code)
                    ->count(),
                'link' => route('contract.index'),
                'icon' => 'fa fa-file-contract',
                'color' => 'bg-info',
            ],
            // 'organization' => [
            //     'title' => 'Organizations',
            //     'count' => DB::table('organizations')
            //         // ->where('company_id', $companyId)
            //         ->count(),
            //     'link' => route('organization.index'),
            //     'icon' => 'fa fa-building',
            //     'color' => 'bg-danger',
            // ],
        ];

        /* =======================
        | Graph 1: Estimates + Contracts per Month
        ======================= */

        $contracts = DB::table('contracts')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            // ->where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $estimates = DB::table('user_estimate')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            // ->where('company_id', $companyId)s
            ->whereYear('created_at', Carbon::now()->year)
            ->whereNotIn('status', ['draft', 'rejected'])
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = [];
        $contractData = [];
        $estimateData = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create()->month($m)->format('M');
            $contractData[] = $contracts[$m] ?? 0;
            $estimateData[] = $estimates[$m] ?? 0;
        }

        $data['line_chart'] = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Estimates Sent',
                    'data' => $estimateData,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40,167,69,0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Contracts Created',
                    'data' => $contractData,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0,123,255,0.1)',
                    'fill' => true,
                ],
            ],
        ];

        /* =======================
        | Pie Charts
        ======================= */
        $data['estimate_chart'] = CmsWidget::getStatusPieChart('user_estimate');
        $data['contract_chart'] = CmsWidget::getStatusPieChart('contracts');
        $estimates_sent = Estimate::with(['items.itemTaxes','discounts'])->where('status', 'sent')->get();
        $estimates_approved = Estimate::with(['items.itemTaxes','discounts'])->where('status', 'approved')->get();
        $estimates_draft = Estimate::with(['items.itemTaxes','discounts'])->where('status', 'draft')->get();
        $estimates_rejected = Estimate::with(['items.itemTaxes','discounts'])->where('status', 'rejected')->get();
        $estimate_send_total = (float) number_format($estimates_sent->sum('final_total'), 2, '.', '');
        $estimate_approved_total = (float) number_format($estimates_approved->sum('final_total'), 2, '.', '');
        $estimate_draft_total = (float) number_format($estimates_draft->sum('final_total'), 2, '.', '');
        $estimate_rejected_total = (float) number_format($estimates_rejected->sum('final_total'), 2, '.', '');
        $data['estimate_send_total'] = $estimate_send_total;
        $data['estimate_approved_total'] = $estimate_approved_total;
        $data['estimate_draft_total'] = $estimate_draft_total;
        $data['estimate_rejected_total'] = $estimate_rejected_total;
        // print_r($grandTotal);
        // die();

        return $this->__cbAdminView('dashboard.company-index', $data);
    }

      public function managerIndex()
    {
        if(Auth::user()->user_type !== 'manager'){
            return redirect()->back()->with('error','You are not authorized to access this page');
        }
        $data['page_title'] = 'Dashboard';
        $data['widgets'] = [
            'client' => [
                'title' => 'Clients',
                'count' => DB::table('users')->join('company_users', 'company_users.user_id', '=', 'users.id')->where('users.user_type', 'client')->where('company_users.company_id', CompanyUser::getCompany(Auth::user()->id)->id)->count(),
                'link' => route('client-management.index'),
                'icon' => 'fa fa-users',
                'color' => 'bg-success',
            ],
            'salesman' => [
                'title' => 'Salesmen',
                'count' => DB::table('users')->join('company_users', 'company_users.user_id', '=', 'users.id')->where('users.user_type', 'salesman')->where('company_users.company_id', CompanyUser::getCompany(Auth::user()->id)->id)->count(),
                'link' => route('salesman-management.index'),
                'icon' => 'fa fa-user-tag',
                'color' => 'bg-warning',
            ],
            'contract' => [
                'title' => 'Contracts',
                'count' => DB::table('contracts')
                    // ->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)
                    ->count(),
                'link' => route('contract.index'),
                'icon' => 'fa fa-file-contract',
                'color' => 'bg-info',
            ],
             'organization' => [
                'title' => 'Organizations',
                'count' => DB::table('organizations')
                // ->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)
                    ->count(),             
                'link' => route('organization.index'),
                'icon' => 'fa fa-building',
                'color' => 'bg-danger',
            ],
            'products' => [
                'title' => 'Products',
                'count' => DB::table('company_products')
                // ->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)
                    ->count(),
                'link' => route('product.index'),
                'icon' => 'fa fa-box',
                'color' => 'bg-warning',
            ],
        ];

        // Chart Data
        $data['line_chart'] = CmsWidget::getLineChart('contract');
        $data['contract_chart'] = CmsWidget::getStatusPieChart('contracts');
        $data['estimate_chart'] = CmsWidget::getStatusPieChart('user_estimate');


        return $this->__cbAdminView('dashboard.company-index', $data);
    }


      public function salesmanIndex()
    {
        if(Auth::user()->user_type !== 'salesman'){
            return redirect()->back()->with('error','You are not authorized to access this page');
        }
        $data['page_title'] = 'Dashboard';
        $data['widgets'] = [
            'client' => [
                'title' => 'Clients',
                'count' => DB::table('users')->join('company_users', 'company_users.user_id', '=', 'users.id')->where('users.user_type', 'client')->where('company_users.company_id', CompanyUser::getCompany(Auth::user()->id)->id)->count(),
                'link' => route('client-management.index'),
                'icon' => 'fa fa-users',
                'color' => 'bg-success',
            ],
            'contract' => [
                'title' => 'Contracts',
                'count' => DB::table('contracts')
                    ->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)
                    ->count(),
                'link' => route('contract.index'),
                'icon' => 'fa fa-file-contract',
                'color' => 'bg-info',
            ],
            'organization' => [
                'title' => 'Organizations',
                'count' => DB::table('organizations')
                ->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)
                    ->count(),             
                'link' => route('organization.index'),
                'icon' => 'fa fa-building',
                'color' => 'bg-danger',
            ],
            'products' => [
                'title' => 'Products',
                'count' => DB::table('company_products')
                ->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)
                    ->count(),
                'link' => route('product.index'),
                'icon' => 'fa fa-box',
                'color' => 'bg-warning',
            ],
        ];

        // Chart Data
        $data['line_chart'] = CmsWidget::getLineChart('contract');
        $data['contract_chart'] = CmsWidget::getStatusPieChart('contracts');
        $data['estimate_chart'] = CmsWidget::getStatusPieChart('user_estimate');


        return $this->__cbAdminView('dashboard.company-index', $data);
    }


    public function clientIndex()
    {
        if(Auth::user()->user_type !== 'client'){
            return redirect()->back()->with('error','You are not authorized to access this page');
        }
        $data['page_title'] = 'Dashboard';
        $data['widgets'] = [
            'contract' => [
                'title' => 'Contracts',
                'count' => DB::table('contracts')
                    ->where('client_id', Auth::user()->id)
                    ->count(),
                'link' => route('contract.index'),
                'icon' => 'fa fa-file-contract',
                'color' => 'bg-info',
            ],
            'estimate' => [
                'title' => 'Estimates',
                'count' => DB::table('user_estimate')
                    ->where('client_id', Auth::user()->id)
                    ->count(),
                'link' => route('estimate.index'),
                'icon' => 'fa fa-file-contract',
                'color' => 'bg-warning',
            ],
            'organization' => [
                'title' => 'Organizations',
                'count' => DB::table('organizations')
                ->where('client_id', Auth::user()->id)
                    ->count(),             
                'link' => route('organization.index'),
                'icon' => 'fa fa-building',
                'color' => 'bg-danger',
            ],
        ];
        return $this->__cbAdminView('dashboard.client-index', $data);
    }
}
