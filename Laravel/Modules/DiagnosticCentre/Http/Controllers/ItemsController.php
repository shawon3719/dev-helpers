<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Gate;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\DiagnosticCentre\Entities\Category;
use Symfony\Component\HttpFoundation\Response;
use Modules\DiagnosticCentre\Entities\Item;
use Modules\DiagnosticCentre\Http\Requests\MassDestroyItemRequest;
use Modules\DiagnosticCentre\Imports\PathologyItemsImport;
use Modules\DiagnosticCentre\Services\ItemsService;

class ItemsController extends Controller
{
    protected $itemsService;

    public function __construct()
    {
        $this->itemsService = new ItemsService(); 
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('diagnostic_items_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['items'] = Item::with('category')->latest()->get();

        if ($request->ajax()) {
            return $this->itemsService->getTableData();
        }

        return view('diagnosticcentre::items.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('diagnostic_items_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['categories'] = Category::with('children')->where('parent_id', null)->latest()->get();
        $data['item_code'] = $this->itemsService->generateItemCode();

        if($request->ajax()){
            return view('diagnosticcentre::items.ajaxCreate', $data);
        }else{
            return view('diagnosticcentre::items.create', $data);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $item = Item::create( array_merge($request->except('_token'), ['created_by' => Auth::user()->id]));
        
        Session::flash('success', 'Category has been created successfully!');

        if($request->ajax()){

            $items = Item::latest()->get();

            return response()->json(['items' => $items, 'item' => $item]);

        }else{
            return redirect()->route('diagnostic-centre.items.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Item $item, Request $request)
    {
        $item->load('category');
        abort_if(Gate::denies('diagnostic_items_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->ajax()){
            return response()->json(['item' => $item]);
        }else{
            return view('diagnosticcentre::items.show', compact('item'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Item $item)
    {
        abort_if(Gate::denies('diagnostic_items_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categories = Category::with('children')->where('parent_id', null)->latest()->get();

        return view('diagnosticcentre::items.edit', compact('item','categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        Item::where('id', $id)->update( array_merge($request->except(['_token','_method']), ['updated_by' => Auth::user()->id]));
        
        Session::flash('success', 'Item has been updated successfully!');

        return redirect()->route('diagnostic-centre.items.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('diagnostic_items_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        Item::find($id)->delete();

        Session::flash('success', 'Item has been deleted successfully!');

        return redirect()->route('diagnostic-centre.items.index');
    }

    public function massDestroy(MassDestroyItemRequest $request)
    {
        abort_if(Gate::denies('diagnostic_items_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Item::whereIn('id', $request->ids)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function import(){

        abort_if(Gate::denies('diagnostic_items_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['action_url'] = route('diagnostic-centre.items.import-store');
        $data['title'] = 'Items';
        $data['filename'] = 'sample_templates/pathology-items-import-format.xlsx';

        return view("diagnosticcentre::common.import", $data);

    }

    public function storeImport(Request $request)
    {
        abort_if(Gate::denies('diagnostic_items_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fileName = File::extension($request->document->getClientOriginalName());

        $extensions = array("xls","xlsx");

        $fileExtension = $request->file('document')->getClientOriginalExtension();

        if(!in_array($fileExtension,$extensions)){ 

            $message = "File is a '.$fileName.' file.!! Please upload a valid xls/xlsx file..!!";
            return response()->json(['type' => 'failed', 'message' => $message, 'redirect_url' => route('diagnostic-centre.items.import')])->setStatusCode(500);;
        }

        $import = new PathologyItemsImport();
        
        try{

            $row_array = $import->toArray($request->file("document"));

            $flag = 0;

            $processed = 0;            

            foreach ($row_array[0] as $row) {
                $code = $this->itemsService->generateItemCode();

                if(isset($row['name'])  && isset($row['price']) && isset($row['category'])){

                    $category = Category::where('name', $row['category'])->exists();
                    if($category){
                        Item::create([
                            'code'           => $code,
                            'name'           => $row['name'],
                            'price'          => $row['price'],
                            'offer_price'    => $row['offer_price'] ?? '',
                            'exp_date'       => date("d-m-Y", strtotime($row['exp_date'])),
                            'category_id'    => Category::where('name', $row['category'])->first()->id,
                            'description'    => $row['description'] ?? '',
                            'created_by'     => Auth::user()->id
                        ]); 
                        
                        $processed ++;
                    }
                
                }else{

                    $incompleteRow[] = $row;

                    $request->session()->put('incompleteRow', $incompleteRow);
                }          
                  $flag++;            
        
            }

        $message = "<code>".$processed."</code> Items has been successfully processed from <code>".$flag."</code> records.";
        return response()->json(['type' => 'success', 'message' => $message, 'redirect_url' => route('diagnostic-centre.items.index')]);

        }catch(Exception $ex){

            $e = $ex->getMessage();
       
            $message = "Something went wrong please check the issue details. $e";
            return response()->json(['type' => 'failed', 'message' => $message, 'redirect_url' => route('diagnostic-centre.items.import')])->setStatusCode(500);;
        }  
    
    }

}
