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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getAllCompanies(),200)
            ->header('Content-Type','json');
    }

    /**
     * @param $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($company)
    {
        $response = array_filter($this->getAllCompanies(),function ($item) use ($company){
            return mb_strtolower($item['company'],'UTF-8') == mb_strtolower($company,'UTF-8');
        });

        if(isset($response["1"])){
            $data = $response["1"];
        }else{
            $data = $response[0];
        }

        if(!empty($data) || !is_null($data) || count($data) != 0){
            return response()->json($data,200)
                ->header('Content-Type','json');
        }else{
            return response()->json('No data found',204)
                ->header('Content-Type','json');
        }
    }

    public function contacts($id)
    {

        $response = $this->hubspot->contacts()->search($id,[
            'property'  => ['firstname','lastname','email'],
        ])->getData()->contacts;

        if(count($response) >= 1){
            $contactArray = [
                'name' => $response[0]->properties->firstname->value . ' ' . $response[0]->properties->lastname->value,
                'email' => $response[0]->properties->email->value,
            ];

            return response()->json($contactArray,200)
                ->header('Content-Type','json');
        }
        return response()->json('No data found',204)
            ->header('Content-Type','json');
    }

    /**
     * PRIVATE FUNCTIONS
     */
    /**
     * @return array
     */
    private function getAllCompanies()
    {
        $arrayCompanies = array();
        $response = $this->hubspot->companies()->all([
            'properties'  => ['name','phone']
        ]);
        //return $response->companies;
        foreach ($response->companies as $company) {
            $arrayCompanies[] = [
                'company_id'=>$company->companyId,
                'company' => $company->properties->name->value,
                'phone' => isset($company->properties->phone)? $company->properties->phone->value:''
            ];
        }
        return $arrayCompanies;
    }
}
