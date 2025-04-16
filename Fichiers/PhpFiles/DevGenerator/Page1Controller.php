<?php
namespace App\Http\Controllers\Hoggar\DevGenerator;

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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;


class Page1Controller extends Controller
{
   

    public function create(Request $request)
    {

        $request->validate([
            'model' => ['required'],
            'middleware' => ['required'],
          ]);


        $transformString = new \Hoggar\Hoggar\Utils\TransformString();

        $modelLink  = $transformString->transformLink($request->model) ;
        $modelUrl  = $transformString->transformUrl($request->model) ;

        $crudPart = new \Hoggar\Hoggar\Utils\CrudPart();

        $piece1 = $crudPart->getPiece1($request->model,$modelLink,$modelUrl);
        $piece2 = $crudPart->getPiece2($request->model,$modelLink,$modelUrl);
        $piece3 = $crudPart->getPiece3($request->model,$modelLink,$modelUrl);
        $piece4 = $crudPart->getPiece4($request->model,$request->middleware,$modelUrl);
        
       

        $tempdossier1  =  'app/Http/Controllers/Hoggar/Crud/' . $request->model ;
        $dossier1 = base_path($tempdossier1);

        $tempchemin1  = 'app/Http/Controllers/Hoggar/Crud/' . $request->model . '/CreatorController.php' ;
        $tempchemin2  = 'app/Http/Controllers/Hoggar/Crud/' . $request->model . '/UpdatorController.php' ;
        $tempchemin3  = 'app/Http/Controllers/Hoggar/Crud/' . $request->model . '/ListingController.php' ;
        $tempchemin4 = "routes/hoggar.php" ;

        $chemin1 = base_path($tempchemin1);
        $chemin2 = base_path($tempchemin2);
        $chemin3 = base_path($tempchemin3);
        $chemin4 = base_path($tempchemin4);

        if (File::exists($dossier1)) {
            return back()->withErrors([
                'message' => 'Un CRUD pour ce modèle existe déjà.'
            ]);
        }

        if (!File::exists($dossier1)) {
            File::makeDirectory($dossier1, 0755, true);
        }

        if (!File::exists($chemin1)) {
          
            File::put($chemin1,$piece1);
        }

        if (!File::exists($chemin2)) {
            
            File::put($chemin2, $piece2);
        }

        if (!File::exists($chemin3)) {
            
            File::put($chemin3, $piece3);
        }

        File::append($chemin4 , $piece4);
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        $sourcePath = base_path('vendor/hoggar/hoggar/Fichiers/CrudFiles');
       
        $temp = 'resources/js/Pages/HoggarPages/Crud/' . $request->model ;

        $directory = base_path($temp);

        File::copyDirectory($sourcePath, $directory);

        if (!File::exists($directory)) {
            return response()->json(['error' => 'Dossier non trouvé.'], 404);
        }
    
        // Récupère tous les fichiers (même dans les sous-dossiers)
        $files = File::allFiles($directory);
    
        foreach ($files as $file) {
            if ($file->getExtension() === 'txt') {
                // Nouveau nom avec extension .vue
                $newFileName = str_replace('.txt', '.vue', $file->getFilename());
    
                // Nouveau chemin complet
                $newFilePath = $file->getPath() . '/' . $newFileName;
    
                // Renommer le fichier
                File::move($file->getPathname(), $newFilePath);
            }
        }
    

        
        $crud = new \Hoggar\Hoggar\Models\Hoggarcrud() ;
        $crud->model  =  $request->model;
        $crud->label  = $transformString->transformLink($request->model) ;
        $crud->route  = '/admin/' .  $transformString->transformUrl($request->model) ;
        $crud->icon  = 'description' ;
        $crud->active  = true ;
        $crud->save()  ;


    }

   
    public function index(Request $request)
    {

        $listModels = [];

        $path = app_path('Models');
        
        if (File::exists($path)) {
            foreach (File::files($path) as $file) {
                $listModels[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
        }
            

        return Inertia::render('HoggarPages/DevGenerator/Page1', [
            'routes' =>  \Hoggar\Hoggar\Models\Hoggarcrud::where('active',true)->get(),
            'user' => Auth::user(),
            'listModels' => $listModels ,
            'middlewareList' => config('hoggar.middlewareList'),
        ]);
    }

    
}