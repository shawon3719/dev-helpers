<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\DiagnosticCentre\Entities\Category;
use Modules\DiagnosticCentre\Http\Requests\MassDestroyCategoryRequest;
use Modules\DiagnosticCentre\Http\Requests\StoreCategoryRequest;
use Modules\DiagnosticCentre\Services\CategoriesService;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use File;
use Modules\DiagnosticCentre\Imports\PathologyCategoriesImport;

class CategoriesController extends Controller
{
    protected $categoriesService;

    public function __construct()
    {
        $this->categoriesService = new CategoriesService(); 
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('diagnostic_categories_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['categories'] = Category::latest()->get();

        if ($request->ajax()) {
            return $this->categoriesService->getTableData();
        }

        return view('diagnosticcentre::categories.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        abort_if(Gate::denies('diagnostic_categories_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['categories'] = Category::with('children')->where('parent_id', null)->latest()->get();
        $data['category_code'] = $this->categoriesService->generateCategoryCode();
        return view('diagnosticcentre::categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create( array_merge($request->except('_token'), ['created_by' => Auth::user()->id]));
        
        Session::flash('success', 'Category has been created successfully!');

        return redirect()->route('diagnostic-centre.categories.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Category $category)
    {
        abort_if(Gate::denies('diagnostic_categories_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('diagnosticcentre::categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Category $category)
    {
        abort_if(Gate::denies('diagnostic_categories_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::with('children')->where('parent_id', null)->latest()->get();

        return view('diagnosticcentre::categories.edit', compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreCategoryRequest $request, $id)
    {
        Category::where('id', $id)->update( array_merge($request->except(['_token','_method']), ['updated_by' => Auth::user()->id]));
        
        Session::flash('success', 'Category has been updated successfully!');

        return redirect()->route('diagnostic-centre.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('diagnostic_categories_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Category::find($id)->delete();

        Session::flash('success', 'Category has been deleted successfully!');

        return redirect()->route('diagnostic-centre.categories.index');
    }

    public function massDestroy(MassDestroyCategoryRequest $request)
    {
        abort_if(Gate::denies('diagnostic_categories_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Category::whereIn('id', $request->ids)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function import(){

        abort_if(Gate::denies('diagnostic_categories_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['action_url'] = route('diagnostic-centre.categories.import-store');
        $data['title'] = 'Categories';
        $data['filename'] = 'sample_templates/pathology-categories-import-format.xlsx';

        return view("diagnosticcentre::common.import", $data);

    }

    public function storeImport(Request $request)
    {
        abort_if(Gate::denies('diagnostic_categories_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fileName = File::extension($request->document->getClientOriginalName());

        $extensions = array("xls","xlsx");

        $fileExtension = $request->file('document')->getClientOriginalExtension();

        if(!in_array($fileExtension,$extensions)){ 

            $message = "File is a '.$fileName.' file.!! Please upload a valid xls/xlsx file..!!";
            return response()->json(['type' => 'failed', 'message' => $message, 'redirect_url' => route('diagnostic-centre.categories.import')])->setStatusCode(500);;
        }

        $import = new PathologyCategoriesImport();
        
        try{

            $row_array = $import->toArray($request->file("document"));

            $flag = 0;

            $processed = 0;            

            foreach ($row_array[0] as $row) {
                $code = $this->categoriesService->generateCategoryCode();

                if(isset($row['name'])  && isset($row['type'])){

                    $parent_category = null;

                    if(isset($row['parent_category'])){
                        $exist = Category::where('name', $row['parent_category'])->exists();
                        if($exist){
                            $parent_category = Category::where('name', $row['parent_category'])->first()->id;
                        }
                    }
                    
                            
                   Category::create([
                       'code'           => $code,
                       'name'           => $row['name'],
                       'type'           => $row['type'],
                       'description'    => $row['description'] ?? '',
                       'parent_id'      => $parent_category,
                       'created_by'     => Auth::user()->id
                   ]); 
                   
                    $processed ++;
                
                }else{

                    $incompleteRow[] = $row;

                    $request->session()->put('incompleteRow', $incompleteRow);
                }          
                  $flag++;            
        
            }
        // Session::flash("success", "<code>".$processed."</code> Categories has been successfully processed from <code>".$flag."</code> records.");
       
        // return redirect()->route('diagnostic-centre.categories.index');

        $message = "<code>".$processed."</code> Categories has been successfully processed from <code>".$flag."</code> records.";
        return response()->json(['type' => 'success', 'message' => $message, 'redirect_url' => route('diagnostic-centre.categories.index')]);

        }catch(Exception $ex){

            $e = $ex->getMessage();
            
            // Session::flash("failed", "Something went wrong please check the issue details. $e");
       
            $message = "Something went wrong please check the issue details. $e";
            return response()->json(['type' => 'failed', 'message' => $message, 'redirect_url' => route('diagnostic-centre.categories.import')])->setStatusCode(500);;
        }  
    
    }

}
