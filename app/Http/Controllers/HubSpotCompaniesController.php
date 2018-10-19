<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Rossjcooper\LaravelHubSpot\HubSpot;

class HubSpotCompaniesController extends Controller
{

    public $hubspot;

    private $arrayCompanies = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HubSpot $hubSpot)
    {
        $this->hubspot = $hubSpot;
    }

    public function index()
    {
        return response()->json($this->getAllCompanies(),200)
            ->header('Content-Type','json');
    }

    public function show($company)
    {
        $response = array_filter($this->getAllCompanies(),function ($item) use ($company){
            if(Str::contains(mb_strtolower($item['company'],'UTF-8'),mb_strtolower($company,'UTF-8'))){
                return mb_strtolower($item['company'],'UTF-8') == mb_strtolower($company,'UTF-8');
            }else{
                return mb_strtolower($item['company'],'UTF-8') == mb_strtolower($company,'UTF-8');
            }
        });

        if(empty($response) || is_null($response)){
            return response()->json($response,204)
                ->header('Content-Type','json');
        }else{
            return response()->json($response,200)
                ->header('Content-Type','json');
        }
    }

    /**
     * PRIVATE FUNCTIONS
     */
    private function getAllCompanies()
    {
        $arrayCompanies = array();
        $response = $this->hubspot->companies()->all([
            'properties'  => ['name']
        ]);
        foreach ($response->companies as $company) {
            $arrayCompanies[] = ['company_id'=>$company->companyId,'company' => $company->properties->name->value];
        }
        return $arrayCompanies;
    }
}
