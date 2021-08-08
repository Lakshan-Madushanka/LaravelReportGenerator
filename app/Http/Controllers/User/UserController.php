<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade;
use  PdfReport;
use ExcelReport;
use CSVReport;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
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
        $sortBy = 'desc';
        $type = 'pdf';

        //set sort by
        if (strtolower($request->query('sort_by') === 'asc')) {
            $sortBy = 'asc';
        }

        //set report type
        if (strtolower($request->query('type') === 'excel')) {
            $sortBy = 'excel';
        } elseif (strtolower($request->query('sort_by') === 'csv')) {
            $sortBy = 'csv';
        }

        $title = 'All Users Report - Confidential';
        $meta = ['Sorted By' => $sortBy];

        $queryBuilder = User::select('n_i_c', 'user_id', 'full_name', 'gender')
            ->orderBy('id', $sortBy);


        $columns = [
            'N I C' => 'n_i_c',
            'User Id' => 'user_id',
            'Full Name' => 'full_name',
            'Gender' => 'gender'
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)
            // ->editColumn('Gender', ['class' => 'righr bold'])
            // ->limit($request->query('limit'))
            ->download('AllUsers.pdf');
    }

    public function allUsersWithRoles(Request $request)
    {

        $type = 'pdf';
        $sortBy = 'desc';
        $title = 'All Users Report - Confidential';
        $meta = ['Sorted By' => $sortBy];

        $this->setReportDetails($request, $type, $sortBy, $title, $meta);

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

        if ($type === 'pdf') {
            return PdfReport::of($title, $meta, $queryBuilder, $columns)
                ->editColumn('Role', ['class' => 'bold'])
                ->editColumn('Role Id', ['class' => 'visible'])
                ->setCss([
                    '.visible' => 'display:none',
                ])
                ->groupBy('Role Id')
                //->setGroupByTitle('End of the Section')
                ->limit($request->query('limit'))
                ->download('AllUsers.pdf');
        }

        if ($type === 'excel') {
            return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                ->editColumn('Role', ['class' => 'right bold'])
                ->editColumn('Role Id', ['class' => 'visible'])
                ->setCss([
                    '.visible' => 'display:none',
                ])
                ->groupBy('Role Id')
                ->setGroupByTitle('End of the Section')
                ->limit($request->query('limit'))
                ->download('AllUsers');
        }

        if ($type === 'csv') {
            return CSVReport::of($title, $meta, $queryBuilder, $columns)
                ->editColumn('Role', ['class' => 'right bold'])
                ->editColumn('Role Id', ['class' => 'visible'])
                ->setCss([
                    '.visible' => 'display:none',
                ])
                ->groupBy('Role Id')
                ->setGroupByTitle('End of the Section')
                ->limit($request->query('limit'))
                ->download('AllUsers');
        }

    }

    public function setReportDetails(Request $request, &$type, &$sortBy, &$title, &$meta)
    {
        //set sort by
        if (strtolower($request->query('sort_by') === 'asc')) {
            $sortBy = 'asc';
        }

        //set report type
        if (strtolower($request->query('type') === 'excel')) {
            $type = 'excel';
        } elseif (strtolower($request->query('sort_by') === 'csv')) {
            $type = 'csv';
        }

    }

}
