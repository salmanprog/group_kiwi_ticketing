<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CustomHelper;
use DB;
use Auth;

class CmsWidget extends Model
{
    use SoftDeletes, CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cms_widgets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'icon',
        'color',
        'div_column_class',
        'link',
        'widget_type',
        'sql',
        'config',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * It is used to enable or disable DB cache record
     * @var bool
     */
    protected $__is_cache_record = true;

    /**
     * @var
     */
    protected $__cache_signature;

    /**
     * @var string
     */
    protected $__cache_expire_time = 1; //days

    public static function getLineChart(string $type)
    {
        // Common: month names (Jan to Dec)
        $months = collect(range(1, 12))->map(fn($m) => date('F', mktime(0, 0, 0, $m, 10)));

        // Determine table and base query
        if ($type === 'users') {
            $query = DB::table('users')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', now()->year);
        } elseif ($type === 'contract') {
            $companyId = CompanyUser::getCompany(Auth::id())->id;

            $query = DB::table('contracts')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->where('company_id', $companyId)
                ->whereYear('created_at', now()->year);
        } else {
            return [
                'labels' => [],
                'data' => [],
            ];
        }

        // Get data grouped by month number
        $registrations = $query
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        // Map data to all months (fill missing with 0)
        $data = $months->map(function ($month, $index) use ($registrations) {
            return $registrations->get($index + 1, 0);
        });

        return [
            'labels' => $months->toArray(),
            'data' => $data->toArray(),
        ];
    }


    /**
     * Get distribution of users by user_type.
     */
    public static function getPieChart($type)
    {
        $results = DB::table('users')
            ->select('user_type', DB::raw('COUNT(*) as count'))
            ->where('user_type', '!=', 'admin')
            ->groupBy('user_type')
            ->get();

        return [
            'labels' => $results->pluck('user_type')->toArray(),
            'data' => $results->pluck('count')->toArray(),
        ];
    }

    public static function getMonthlyStatusChart(string $table)
    {
        $statuses = ['accepted', 'pending', 'rejected'];

        // Labels: January to December
        $months = collect(range(1, 12))->map(fn($month) => date('F', mktime(0, 0, 0, $month, 10)));

        // Initialize result array
        $result = [];
        foreach ($statuses as $status) {
            $result[$status] = array_fill(1, 12, 0); // key: month (1–12), value: count
        }

        // Base query
        $query = DB::table($table)
            ->selectRaw('MONTH(created_at) as month, status, COUNT(*) as count')
            ->whereYear('created_at', now()->year);

        // Optional: restrict to current user's company
        if ($table === 'contracts') {
            $companyId = CompanyUser::getCompany(Auth::id())->id;
            $query->where('company_id', $companyId);
        }

        $records = $query->groupBy('month', 'status')->get();

        // Fill result with DB data
        foreach ($records as $record) {
            $status = strtolower($record->status);
            $month = (int)$record->month;

            if (in_array($status, $statuses)) {
                $result[$status][$month] = $record->count;
            }
        }

        // Format for JS (array index 0–11)
        $formattedResult = [];
        foreach ($statuses as $status) {
            $formattedResult[$status] = [];
            for ($i = 1; $i <= 12; $i++) {
                $formattedResult[$status][] = $result[$status][$i] ?? 0;
            }
        }

        return [
            'labels' => $months->toArray(),
            'accepted' => $formattedResult['accepted'],
            'pending'  => $formattedResult['pending'],
            'rejected' => $formattedResult['rejected'],
        ];
    }


    public static function getStatusPieChart($table)
    {
        // Get company ID if needed (assuming estimates belong to a company)
        $companyId = CompanyUser::getCompany(Auth::id())->id;

        $whereColum = ($table === 'contracts') ? 'is_accept' : 'status';

        // Query to count estimates grouped by status
        $statusCounts = DB::table($table)
            ->select($whereColum, DB::raw('COUNT(*) as count'))
            // ->where('company_id', $companyId)
            ->groupBy($whereColum)
            ->pluck('count', $whereColum)
            ->toArray();

        // Define expected statuses to keep order and fill missing statuses with 0
        if ($table === 'contracts') {
            $statuses = ['pending', 'accepted', 'rejected'];
        } else {
            $statuses = ['sent', 'draft', 'approved', 'rejected'];
        }

        $counts = [];
        $labels = [];

        foreach ($statuses as $status) {
            $counts[] = $statusCounts[$status] ?? 0;
            if ($status === 'sent') {
                $labels[] = 'Pending';
            } else {
                $labels[] = ucfirst($status);
            }
        }

        return [
            'labels' => $labels,
            'data' => $counts,
        ];
    }
}
