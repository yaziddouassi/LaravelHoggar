<?php

namespace Hoggar\Hoggar\Utils;
use Illuminate\Support\Str;

class WizardPart
{
   public $piece1;
   public $piece2;
   public $piece3 ;
   public $piece4;

   public function getPiece1($a,$b,$c) {
       $this->piece1 = "<?php
namespace App\Http\Controllers\Hoggar\Crud\\$a;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Hoggar\Hoggar\Generator\WizardCreate;
use App\Models\Voiture;
use Illuminate\Database\Eloquent\Collection;

class CreatorController extends WizardCreate
{
    
    public   \$hogarDataModelLabel = '$b' ;
    public   \$hogarDataModelTitle = 'Create $b' ;
    public   \$hogarDataRouteListe = '/admin/$c';
    public   \$hogarModelClass = 'App\Models\\$a';
    public   \$hogarModelClassName = '$a';
    public   \$hogarDataUrlCreate = '/admin/$c/create' ;
    public   \$hogarValidationUrl = '/admin/$c/create/validation' ;

    public   \$hogarShowOther = true ;
    public   \$wizardCount = 2 ;
    public   \$wizardForm = [1 => ['name'], 2 => ['age']];
    public   \$wizardLabel = [1 => 'first', 2 => 'second'];
    public   \$wizardStop = [1];


    
    public function initField()
    {
        \$this->AddField('Text',['field' => 'name']);
        \$this->AddField('Number',['field' => 'age','min' => '' ,'max' => '','step' => '']);
    }

    public function store(Request \$request)
    {  
        

        \$this->hogarRecord = new \$this->hogarModelClass;

        if (\$request->wizardStep == 1) {
                 \$request->validate([
                     'name' => ['required'],
                  ]);

            if (\$request->saveActive == 'yes') {
                \$this->createRecord(\$request);
                \$this->hogarRecord->save() ; 
            }
        }
       

       
        if (\$request->wizardStep == 2) {
            
            \$request->validate([
                'age' => ['required'],
             ]);

             if (\$request->saveActive == 'yes') {
                 \$this->createRecord(\$request);
                  \$this->hogarRecord->save() ; 
             }
          }
  
        
       
        
    }


    public function index(Request \$request)
    {
       
        return Inertia::render('HoggarPages/Crud/$a/Creator', [
            'routes' =>  \Hoggar\Hoggar\Models\Hoggarcrud::where('active',true)->get(),
            'user' => Auth::user(),
            'hogarInputs'  => \$this->hogarInputs ,
            'hogarSettings'  => \$this->hogarSettings,
            'wizardForm' => \$this->wizardForm,
            'wizardLabel' => \$this->wizardLabel,
            'wizardCount' => \$this->wizardCount,
            'wizardStop' => \$this->wizardStop,
        ]);
    }

}

    
       ";

    return $this->piece1 ;

   }


   public function getPiece2($a,$b,$c) {
    $this->piece2 = "<?php

namespace App\Http\Controllers\Hoggar\Crud\\$a;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Hoggar\Hoggar\Generator\HoggarUpdate;
use App\Models\Voiture;
use Illuminate\Database\Eloquent\Collection;

class UpdatorController extends HoggarUpdate
{

    public   \$hogarDataModelLabel = '$b' ;
    public   \$hogarDataModelTitle = 'Update $b' ;
    public   \$hogarDataRouteListe = '/admin/$c';
    public   \$hogarModelClass = 'App\Models\\$a';
    public   \$hogarModelClassName = '$a';
    public   \$hogarDataUrlCreate = '/admin/$c/create' ;
    public   \$hogarValidationUrl = '/admin/$c/updator/validation' ;

    public   \$wizardCount = 2 ;
    public   \$wizardForm = [1 => ['name'], 2 => ['age']];
    public   \$wizardLabel = [1 => 'first', 2 => 'second'];
    public   \$wizardStop = [1];

    public function initField()
    {   
        \$this->AddField('Text',['field' => 'name']);
        \$this->AddField('Number',['field' => 'age','min' => '' ,'max' => '','step' => '']);
    }


    public function store(Request \$request)
     {

        
        if (\$request->wizardStep == 1) {
                 \$request->validate([
                     'name' => ['required'],
                  ]);

            if (\$request->saveActive == 'yes') {
                \$this->hogarRecord = \$this->hogarModelClass::find(\$request->id);
                if(\$this->hogarRecord) {
                    \$this->updateRecord(\$request);
            \$this->hogarRecord->save() ;
                }
                 
            }
        }
       

        if (\$request->wizardStep == 2) {
             \$request->validate([
               'age' => ['required'],
             ]);

          if (\$request->saveActive == 'yes') {
                \$this->hogarRecord = \$this->hogarModelClass::find(\$request->id);
                if(\$this->hogarRecord) {
                    \$this->updateRecord(\$request);
                    \$this->hogarRecord->save() ;
                }
    
               }
          }
  

     }


     public function checkRecord(Request \$request)
{
    \$Record = \$this->hogarModelClass::find(\$request->id);
    
    if (\$Record === null) {
        return redirect(\$this->hogarDataRouteListe);
    }

    \$this->hogarRecordInput = new Collection();
    \$this->hogarRecordInput = \$Record;

    return null; // Return null to indicate no redirection needed
}

public function index(Request \$request)
{
    \$redirect = \$this->checkRecord(\$request);

    if (\$redirect) {
        return \$redirect; // Ensure redirection is returned
    }

    \$this->InitFieldAgain();
    
 
    
    return Inertia::render('HoggarPages/Crud/$a/Updator', [
        'routes' =>  \Hoggar\Hoggar\Models\Hoggarcrud::where('active',true)->get(), 
        'user' => Auth::user(),
        'hogarRecordInput' => \$this->hogarRecordInput,
        'hogarInputs'  => \$this->hogarInputs ,
        'hogarSettings'  => \$this->hogarSettings,
        'wizardForm' => \$this->wizardForm,
        'wizardLabel' => \$this->wizardLabel,
        'wizardCount' => \$this->wizardCount,
        'wizardStop' => \$this->wizardStop,
    ]);
}

   
}
    
    ";

    return $this->piece2 ;

   }
   
   
   public function getPiece3($a,$b,$c) {

      $this->piece3 = "<?php

namespace App\Http\Controllers\Hoggar\Crud\\$a;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Hoggar\Hoggar\Generator\Listing;
use Illuminate\Database\Eloquent\Collection;

class ListingController extends Listing
{
  
    public   \$hogarDataModelLabel = '$b' ;
    public   \$hogarDataModelTitle = 'Create $b' ;
    public   \$hogarDataRouteListe = '/admin/$c';
    public   \$hogarModelClass = 'App\Models\\$a';
    public   \$hogarModelClassName = '$a';
    public   \$hogarDataUrlCreate = '/admin/$c/create' ;
    public   \$hogarDataUrlCheckRecord = '/admin/$c/CheckRecord' ;
    public   \$customs = ['custom1' => '/admin/$c/custom1'] ;
    public   \$urlDelete = '/admin/$c/delete';
    public   \$PaginationPerPageList = [1,2,3,4] ;
    public   \$orderByFieldList = ['id'] ;
    public   \$orderDirectionList = ['asc','desc'] ;
    public   \$sessionFilter = [/*'search','PaginationPerPage','orderByField','orderDirection' */] ;
   

    public function CustomFilterList(Request \$request)
        {
            \$this->AddFilter('Text',['field' => 'name']);
            
        }

    public function InitQuery(Request \$request) {
            if (\$request->filled('name')) {
            //    \$this->queryFilter = \$this->queryFilter->where('name',\$request->name);
            }
            }
  
    
    public function InitAction(Request \$request)
        {
            \$this->AddAction('action1',
            ['label'=> 'Ajouter','icon' => 'description','class' => 'text-[red]',
            'url' => '/admin/voitures/action1',
            'confirmation' => 'voulez-vous Ajouter ces records' ,
            'message' => 'records ajouter' ]);
        }

    public function action1(Request \$request)
        {  

        \$this->hogarModelClass::whereIn('id',\$request->actionIds )->update([
                'name' => 'Fiat',
            ]);

        }
    
  
        public function checkRecord(Request \$request)
        {  
            \$record = \$this->hogarModelClass::find(\$request->id) ;

            if (!\$record) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Enregistrement introuvable.',
                ], 404);
            }
        
            return response()->json([
                'success' => true,
                'data' => \$record,
            ]);


        }    
        
    public function custom1(Request \$request)
        {  

            \$request->validate([
                'name' => ['required'],
            ]);

            \$this->hogarModelClass::where('id',\$request->id )->update([
                'name' => \$request->name,
            ]);
        } 

    public function delete(Request \$request)
        {  
            \$this->hogarModelClass::destroy(\$request->id );
        } 



    public function index(Request \$request)
    {
        \$this->AllInit(\$request);
       
        return Inertia::render('HoggarPages/Crud/$a/Listing', [
            'items' => \$this->tables,
            'user' => Auth::user(),
            'routes' =>  \Hoggar\Hoggar\Models\Hoggarcrud::where('active',true)->get(),
            'hogarSettings'  => \$this->hogarSettings,
            'allFilters' => \$this->allFilters,
            'customFilters' => \$this->customFilters,
            'sessionFilter' => \$this->sessionFilter,
            'groupActions' =>  \$this->groupActions ,
            'hogarDataUrlCheckRecord' => \$this->hogarDataUrlCheckRecord,
            'customs' => \$this->customs,
        ]);
    }

    
}
      
      
      ";

      return $this->piece3;
      }



      public function getPiece4($a,$b,$c) {

           $this->piece4 = " 
Route::get('/admin/$c', [\App\Http\Controllers\Hoggar\Crud\\$a\ListingController::class, 'index'])->middleware('$b');
Route::get('/admin/$c/create', [\App\Http\Controllers\Hoggar\Crud\\$a\CreatorController::class, 'index'])->middleware('$b');
Route::post('/admin/$c/create/validation', [\App\Http\Controllers\Hoggar\Crud\\$a\CreatorController::class, 'store'])->middleware('$b');
Route::get('/admin/$c/update/{id}', [\App\Http\Controllers\Hoggar\Crud\\$a\UpdatorController::class, 'index'])->middleware('$b');
Route::post('/admin/$c/updator/validation', [\App\Http\Controllers\Hoggar\Crud\\$a\UpdatorController::class, 'store'])->middleware('$b');
Route::post('/admin/$c/delete', [\App\Http\Controllers\Hoggar\Crud\\$a\ListingController::class, 'delete'])->middleware('$b');
Route::post('/admin/$c/CheckRecord', [\App\Http\Controllers\Hoggar\Crud\\$a\ListingController::class, 'checkRecord'])->middleware('$b');
Route::post('/admin/$c/action1', [\App\Http\Controllers\Hoggar\Crud\\$a\ListingController::class, 'action1'])->middleware('$b');
Route::post('/admin/$c/custom1', [\App\Http\Controllers\Hoggar\Crud\\$a\ListingController::class, 'custom1'])->middleware('$b');
           " ; 


           return $this->piece4;
      }



}