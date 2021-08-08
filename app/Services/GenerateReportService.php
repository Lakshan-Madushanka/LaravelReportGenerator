<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade;
use  PdfReport;
use ExcelReport;
use CSVReport;

class GenerateReportService
{

    private string $sortBy = "desc";
    private string $type = "pdf";
    private string $title;
    private $query;
    private array $meta;
    private array $colmns;
    private string $groupBy;
    private Request $request;
    private array $editColumns;
    private array $addColumns;
    private $reportGeneratorObject;

    /**
     * @param  string  $title
     */
    public function setTitle(string $title): GenerateReportService
    {
        $this->title = $title;

        return $this;
    }

    public function setQuery(array $query): GenerateReportService
    {
        $this->query = $query;

        return $this;
    }

    public function setMetaData(
        $query,
        Request $request,
        array $columns,
        string $sortBy,
        string $groupBy = '',
        string $title = 'Report'
    ): GenerateReportService {
        $this->request = $request;
        $this->query = $query;
        $this->colmns = $columns;
        $this->sortBy = $sortBy;
        $this->groupBy = $groupBy;
        $this->title = $title;

        return $this;
    }

    public function setDetails()
    {
        $this->meta = ['Sorted By' => $this->sortBy];

        //set report type
        if (strtolower($this->request->query('type') === 'excel')) {
            $this->type = 'excel';
        } elseif (strtolower($this->request->query('type') === 'csv')) {
            $this->type = 'csv';
        }
    }

    public function generateReport()
    {
        $this->setDetails();

        if ($this->type === 'pdf') {

            return PdfReport::of($this->title, $this->meta, $this->query, $this->colmns)
                ->{$this->groupBy ? 'groupBY' : 'limit'}($this->groupBy)
                ->limit($this->request->query('limit'));

        } elseif ($this->type === 'excel') {

            return ExcelReport::of($this->title, $this->meta, $this->query, $this->colmns)
                ->{$this->groupBy ? 'groupBY' : 'limit'}($this->groupBy)
                ->limit($this->request->query('limit'));
        } else {

            return CSVReport::of($this->title, $this->meta, $this->query, $this->colmns)
                ->{$this->groupBy ? 'groupBY' : 'limit'}($this->groupBy)
                ->limit($this->request->query('limit'));
        }
    }
}



