<?php

namespace Hoggar\Hoggar\Generator;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class WizardCreate extends Controller
{
   
    public $hogarSettings = [] ;
    public $hogarInputs = [] ;
  
    public $tabFields = [];
    public $tabLabels = [];
    public $tabTypes = [];
    public $tabOptions = [];
    public $tabValues = [];
    public $tabDefaultValues = [];
    public $tabNodatabases = [];
    public $tabNullables = [];
    public $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio'];
    public $arrayTypes2 = ['Quill'];
    public $arrayTypes3 = ['File','Image','Video','Audio'];
    public $arrayTypes4 = ['FileEdit','ImageEdit','VideoEdit','AudioEdit'];
    public $arrayTypes5 = ['MultipleFile','MultipleImage','MultipleVideo','MultipleAudio'];
    public $arrayTypes6 = ['CheckBoxMultiple'];
    public $arrayTypes7 = ['CheckBox'];
    public $arrayTypes8 = ['Password'];
    public $hogarRecord = null;
   
    function __construct() {
        $this->hogarSettings['hogarShowOther'] = $this->hogarShowOther ;
        $this->hogarSettings['hogarDataModelLabel'] =  $this->hogarDataModelLabel ;
        $this->hogarSettings['hogarDataModelTitle'] =  $this->hogarDataModelTitle ;
        $this->hogarSettings['hogarDataRouteListe'] =  $this->hogarDataRouteListe ;
        $this->hogarSettings['hogarDataUrlCreate'] =  $this->hogarDataUrlCreate ;
        $this->hogarSettings['hogarModelClass'] =  $this->hogarModelClass ;
        $this->hogarSettings['hogarModelClassName'] =  $this->hogarModelClassName ;
        $this->hogarSettings['hogarValidationUrl']=  $this->hogarValidationUrl ;
        $this->initField();
        $this->initHogarInputs() ;
        
    }

    public function initHogarInputs() {

        $this->hogarInputs['hogarDataUrlStorage'] =  config('hoggar.urlstorage');
        $this->hogarInputs['hogarDataFields'] = $this->tabFields ;
        $this->hogarInputs['hogarDataLabels'] = $this->tabLabels ;
        $this->hogarInputs['hogarDataTypes'] = $this->tabTypes ;
        $this->hogarInputs['hogarDataValues'] = $this->tabValues ;
        $this->hogarInputs['hogarDataDefaultValues'] = $this->tabDefaultValues ;
        $this->hogarInputs['hogarDataOptions'] = $this->tabOptions ;
        $this->hogarInputs['hogarDataNullables'] = $this->tabNullables ;
        $this->hogarInputs['hogarNoDatabases'] = $this->tabNodatabases ;
    }


    public function AddField($a,$b) {
        $this->tabFields[$b['field']] = $b['field'] ;
        $this->tabLabels[$b['field']] = $b['field'] ;
        $this->tabTypes[$b['field']] = $a;
        $this->tabOptions[$b['field']] = $b;

        if (in_array($a, $this->arrayTypes1)) {
            $this->tabValues[$b['field']] = '';
            $this->tabDefaultValues[$b['field']] = '';
        }

        if (in_array($a, $this->arrayTypes2)) {
            $this->tabValues[$b['field']] = '';
            $this->tabDefaultValues[$b['field']] = '';
        }

        if (in_array($a, $this->arrayTypes3)) {
            $this->tabValues[$b['field']] = '';
            $this->tabDefaultValues[$b['field']] = '';
        }

        if (in_array($a, $this->arrayTypes5)) {
            $this->tabValues[$b['field']] = [];
            $this->tabDefaultValues[$b['field']] = [];
        }

        if (in_array($a, $this->arrayTypes6)) {
            $this->tabValues[$b['field']] = [];
            $this->tabDefaultValues[$b['field']] = [];
        }
        if (in_array($a, $this->arrayTypes7)) {
            $this->tabValues[$b['field']] = false;
            $this->tabDefaultValues[$b['field']] = false;
        }

        if (in_array($a, $this->arrayTypes8)) {
            $this->tabValues[$b['field']] = '';
            $this->tabDefaultValues[$b['field']] = '';
        }

    }

    public function SetFieldValue($a,$b) {

        if (in_array($a, $this->tabFields)) {
            $this->tabValues[$a] = $b;
           $this->tabDefaultValues[$a] = $b;
        }

    }



    public function SetFieldLabel($a,$b) {

        if (in_array($a, $this->tabFields)) {
            $this->tabLabels[$a] = $b;
         
        }

    }


    public function SetFieldNodatabases($a) {

           $this->tabNodatabases[$a] = $a;

    }


    public function SetFieldNullables($a) {
        
           $this->tabNullables[$a] = $a;

    }

 

   
    public function createRecord(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $this->tabFields) && !array_key_exists($key, $this->tabNodatabases)) {
                if (in_array($this->tabTypes[$key], $this->arrayTypes3)) {
                    // Handle single file uploads
                    if ($request->hasFile($key)) {
                        $file = $request->file($key);
                        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                        $file->storeAs('uploads', $uniqueName, 'public');
                        $path = 'uploads/' . $uniqueName ;
                        $this->hogarRecord->$key = $path;
                    }
                } elseif (in_array($this->tabTypes[$key], $this->arrayTypes5)) {
                    // Handle multiple file uploads
                    $temp = [];
                    if ($request->hasFile($key)) {
                        foreach ($request->file($key) as $file) {
                            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                            $file->storeAs('uploads', $uniqueName, 'public');
                            $path = 'uploads/' . $uniqueName ;
                            $temp[] = $path;
                        }
                    }
                    $this->hogarRecord->$key = json_encode($temp);
                } elseif (in_array($this->tabTypes[$key], $this->arrayTypes1)) {
                    $this->hogarRecord->$key = $value;
                }

                elseif (in_array($this->tabTypes[$key], $this->arrayTypes2)) {
                $this->hogarRecord->$key = $value;
               }
               
               elseif (in_array($this->tabTypes[$key], $this->arrayTypes6)) {
                $this->hogarRecord->$key = is_array($value) ? json_encode($value) : json_encode(explode(',', $value));
               }
               elseif (in_array($this->tabTypes[$key], $this->arrayTypes7)) {
                
                if($value == 'true') {
                    $value2 = true;
                 }
                 if($value == 'false') {
                    $value2 = false;
                 } 
                 $this->hogarRecord->$key = $value2;    
               }

               elseif (in_array($this->tabTypes[$key], $this->arrayTypes8)) {
                if($value) {
                  $this->hogarRecord->$key = Hash::make($value);
                }
             }


            }
        }
    }



    public function InitAll(Request $request) {
    
       
    }

    
}