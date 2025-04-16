<?php

namespace Hoggar\Hoggar\Generator;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Listing extends Controller
{
    public $hogarSettings = [] ;
    public $allFilters = ['search' => 'search', 'PaginationPerPage' => 'PaginationPerPage',
     'orderByField' => 'orderByField', 'orderDirection' => 'orderDirection'];
    public $customFilters = [] ;
    public $tabFilterFields = [];
    public $tabFilterLabels = [];
    public $tabFilterTypes = [];
    public $tabFilterOptions = [];
    public $search = '' ;
    public $queryFilter;
    public $tables;
    public $groupActions = [];
   
    function __construct() {
       $this->hogarSettings['hogarDataModelLabel'] =  $this->hogarDataModelLabel ;
        $this->hogarSettings['hogarDataModelTitle'] =  $this->hogarDataModelTitle ;
        $this->hogarSettings['hogarDataRouteListe'] =  $this->hogarDataRouteListe ;
        $this->hogarSettings['hogarDataUrlCreate'] =  $this->hogarDataUrlCreate ;
        $this->hogarSettings['hogarModelClass'] =  $this->hogarModelClass ;
        $this->hogarSettings['hogarModelClassName'] =  $this->hogarModelClassName ;
        $this->hogarSettings['PaginationPerPageList'] =  $this->PaginationPerPageList ;
        $this->hogarSettings['orderByFieldList'] =  $this->orderByFieldList ;
        $this->hogarSettings['orderDirectionList'] =  $this->orderDirectionList ;
        $this->hogarSettings['urlDelete'] =  $this->urlDelete ;
        
    }

    public function AddFilter($a,$b) {
        $this->tabFilterFields[$b['field']] = $b['field'] ;
        $this->tabFilterLabels[$b['field']] = $b['field'] ;
        $this->tabFilterTypes[$b['field']] = $a;
        $this->tabFilterOptions[$b['field']] = $b;
    }

    public function AddAction($a,$b) {
        $this->groupActions[$a] = $b ;
    }

    public function InitQuery(Request $request)
     {
       // Méthode volontairement vide, pour être overridée par les enfants
     }
    
    public function AllInit($request) {

        $PaginationPerPage = $this->PaginationPerPageList[0];
        $orderByField = $this->orderByFieldList[0];
        $orderDirection = $this->orderDirectionList[0];

        if ($request->filled('PaginationPerPage')) {
            if (in_array($request->PaginationPerPage, $this->PaginationPerPageList)) {
                $PaginationPerPage = $request->PaginationPerPage ;
                 }
        }

        if ($request->filled('orderByField')) {
            if (in_array($request->orderByField, $this->orderByFieldList)) {
                $orderByField = $request->orderByField ;
                 }
        }

        if ($request->filled('orderDirection')) {
            if (in_array($request->orderDirection, $this->orderDirectionList)) {
                $orderDirection = $request->orderDirection ;
                 }
        }


        $this->CustomFilterList($request);
        $this->InitAction($request);
        $this->customFilters['Fields'] =  $this->tabFilterFields ;
        $this->customFilters['Labels'] =  $this->tabFilterLabels ;
        $this->customFilters['Types'] =  $this->tabFilterTypes ;
        $this->customFilters['Options'] =  $this->tabFilterOptions;
        $this->queryFilter = $this->hogarModelClass::select('*');
        $this->InitQuery($request);
       
    
        $this->tables = $this->queryFilter->orderBy($orderByField,$orderDirection)
                         ->paginate($PaginationPerPage)
                        ->appends($request->except('page'));
    }
    
}