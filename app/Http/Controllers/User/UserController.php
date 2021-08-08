<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GenerateReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade;
use  PdfReport;
use ExcelReport;
use CSVReport;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    private $generateReport;

    public function __construct(GenerateReportService $generateReport)
    {
        $this->generateReport = $generateReport;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json(['Data' => $user], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function allUsersReport(Request $request)
    {
        $orderBy = 'id';
        $sortBy = 'desc';
        $groupBy = '';

        //set sort by and maa data
        if (strtolower($request->query('sorBy') === 'asc')) {
            $sortBy = 'asc';
        }

        $orderByQuery = strtolower($request->query('orderBy'));
        if ($orderByQuery) {
            $orderBy = $orderByQuery;
        }

        $groupByQuery = strtolower($request->query('groupBy'));
        if ($orderByQuery) {
            $groupBy = $groupByQuery;
        }

        $queryBuilder = User::select('n_i_c', 'user_id', 'full_name', 'gender' )
            ->orderBy($groupBy ? $groupBy : $orderBy, $sortBy);

        $columns = [
            'N I C' => 'n_i_c',
            'User Id' => 'user_id',
            'Full Name' => 'full_name',
            'Gender' => 'gender',
        ];

        return $this->generateReport
            ->setMetaData($queryBuilder, $request, $columns, 'allusers', $sortBy, $groupBy)
            ->generateReport()->editColumn('Gender', ['class' => 'blue'])
            ->setCss([
                '.blue' => 'color:blue',
            ])
            ->download('AllUsers'.'_'.now());
    }

    public function allUsersWithRoles(Request $request)
    {

        $sortBy = 'desc';
        //set sort by and maa data
        if (strtolower($request->query('sorBy') === 'asc')) {
            $sortBy = 'asc';
        }

        $queryBuilder = DB::table('users')
            ->leftJoin('user_role', 'users.id', '=', 'user_role.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'user_role.role_id')
            ->select('n_i_c', 'users.user_id', 'full_name', 'gender',
                'roles.id as id', 'roles.name as roleName')
            ->orderBy('id', $sortBy);

        $columns = [
            'N I C' => 'n_i_c',
            'User Id' => 'user_id',
            'Full Name' => 'full_name',
            'Gender' => 'gender',
            'Role' => 'roleName',
            'Role Id' => 'id',
        ];

        return $this->generateReport
            ->setMetaData($queryBuilder, $request, $columns, 'allusers', $sortBy, 'Role Id')
            ->generateReport()
            ->editColumn('Role', ['class' => 'bold'])
            ->editColumn('Role Id', ['class' => 'visible'])
            ->setCss([
                '.visible' => 'display:none',
            ])
            ->download('AllUsersWithRoles'.'_'.now());
    }

}
