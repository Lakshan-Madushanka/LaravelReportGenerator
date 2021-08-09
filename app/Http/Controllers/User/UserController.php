<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GenerateReportService;
use App\Traits\ReportGenerateTraits\UserReportGenerateTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade;
use  PdfReport;
use ExcelReport;
use CSVReport;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    use UserReportGenerateTrait;

    private $makeReport;

    public function __construct(GenerateReportService $generateReport)
    {
        $this->makeReport = $generateReport;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {

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
        return $this->userAllReport($request);
    }

    public function allUsersWithRoles(Request $request)
    {
        return $this->usersReportWithRoles($request);
    }

}
