<?php

namespace App\Traits\ReportGenerateTraits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait UserReportGenerateTrait
{

    public function userAllReport(Request $request)
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

        $queryBuilder = User::select('n_i_c', 'user_id', 'full_name', 'gender')
            ->orderBy($groupBy ? $groupBy : $orderBy, $sortBy);

        $columns = [
            'N I C' => 'n_i_c',
            'User Id' => 'user_id',
            'Full Name' => 'full_name',
            'Gender' => 'gender',
        ];

        return $this->makeReport
            ->setMetaData($queryBuilder, $request, $columns, $sortBy, $groupBy, 'All Users Confidential !')
            ->generateReport()
            ->editColumn('Gender', ['class' => 'blue'])
            ->setCss([
                '.blue' => 'color:blue',
            ])
            ->download('AllUsers'.'_'.now());
    }


    public function usersReportWithRoles(Request $request)
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

        return $this->makeReport
            ->setMetaData($queryBuilder, $request, $columns, $sortBy, 'Role Id', 'All users with roles confidential !')
            ->generateReport()
            ->editColumn('Role', ['class' => 'bold'])
            ->editColumn('Role Id', ['class' => 'visible'])
            ->setCss([
                '.visible' => 'display:none',
            ])
            ->download('AllUsersWithRoles'.'_'.now());
    }

}
