<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;

class categoryService
{

    public $paginatedList = true;
    public  function __construct()
    {
        //
    }

    public function lists($data = null)
    {
        $search_query = [];

        $order = 'desc';
        $orderBy = 'unique_items_count';

        $query = Category::leftJoin('settings', 'settings.id', '=', 'categories.source_site');

        if(!empty($data)){

            $search_query = [
                "search" => $data["search"] ?? '',
                "search_area" => $data["search_area"] ?? '',
                "search_from_date" => $data["search_from_date"] ?? '',
                "search_to_date" => $data["search_to_date"] ?? '',
                "search_date_range" => $data["search_date_range"] ?? '',
                "max_filter_range" => $data['max_filter_range'] ?? '',
                "min_filter_range" => $data['min_filter_range'] ?? '',
                "filter_type" => $data['filter_type'] ?? ''
            ];

            if(isset($data["search"]) && isset($data["search_area"])){

                $query->where('categories.'.$data["search_area"],'like','%'.$data["search"].'%');
                
            }

            if(isset($data["search_from_date"]) && isset($data["search_to_date"])){

                if(isset($data["search_area"]) && $data["search_area"] == 'updated_at'){
                    
                    $query->whereBetween( 'categories.'.$data["search_area"], [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
                
                }else{
                
                    $query->whereBetween('categories.created_at', [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
                
                }
            }

        }

        if(isset($data['max_filter_range']) && isset($data['min_filter_range'])){

            $minimum_range = $data['min_filter_range'];
            $maximum_range = $data['max_filter_range'];

            $query->where('unique_items_count', '>=', $minimum_range)
            ->where('unique_items_count', '<=', $maximum_range);

        }

        $query->select([
            'categories.name',
            'settings.site',
            'categories.path',
            'categories.id',
            'categories.updated_at',
            'categories.unique_items_count',
            'categories.last_7_days_sale',
            'categories.last_30_days_sale',
            ])
            ->groupBy('categories.name',
            'categories.path',
            'categories.id');

        if(isset($data['filter_type'])){
            $query->orderBy($orderBy,$data['filter_type']);
        }else{
            $query->orderBy($orderBy,$order);
        }

        if ($this->paginatedList === true) {

            $item_per_page = 25;

            $categories = $query->paginate($item_per_page)->appends($search_query);
            $categories->pagination_summary = get_pagination_summary($categories);
        } else {
            $categories = $query->get();
        }

        return $categories;
    }


    public function getAuthorCategories($data = null, $author, $source_site)
    {
        $search_query = [];

        $order = 'desc';
        $orderBy = 'unique_items_count';

        $query = Item::leftJoin('categories', 'items.classification', '=', 'categories.path')
        ->leftJoin('settings', 'settings.id', '=', 'categories.source_site')
        ->where('author_username', 'like', $author)
        ->where('items.source_site', $source_site);

        if(!empty($data)){

            $search_query = [
                "search" => $data["search"] ?? '',
                "search_area" => $data["search_area"] ?? '',
                "search_from_date" => $data["search_from_date"] ?? '',
                "search_to_date" => $data["search_to_date"] ?? '',
                "search_date_range" => $data["search_date_range"] ?? '',
                "max_filter_range" => $data['max_filter_range'] ?? '',
                "min_filter_range" => $data['min_filter_range'] ?? '',
                "filter_type" => $data['filter_type'] ?? ''
            ];

            if(isset($data["search"]) && isset($data["search_area"])){

                $query->where('categories.'.$data["search_area"],'like','%'.$data["search"].'%');
                
            }

            if(isset($data["search_from_date"]) && isset($data["search_to_date"])){

                if(isset($data["search_area"]) && $data["search_area"] == 'updated_at'){
                    
                    $query->whereBetween( 'categories.'.$data["search_area"], [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
                
                }else{
                
                    $query->whereBetween('categories.created_at', [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
                
                }
            }

        }

        if(isset($data['max_filter_range']) && isset($data['min_filter_range'])){

            $minimum_range = $data['min_filter_range'];
            $maximum_range = $data['max_filter_range'];

            $query->whereRaw("(select count(distinct(item_id)) from items 
            where categories.path = items.classification) >= $minimum_range 
            and (select count(distinct(item_id)) from items
            where categories.path = items.classification) <= $maximum_range");

        }

        $query->select([
            'categories.name',
            'categories.path',
            'categories.id',
            'categories.updated_at',
            'settings.site',
            'categories.unique_items_count',
            'categories.last_7_days_sale',
            'categories.last_30_days_sale'
            ])
            ->groupBy('categories.name',
            'categories.path',
            'categories.id',
            'items.source_site'
            );


        if(isset($data['filter_type'])){
            $query->orderBy($orderBy,$data['filter_type']);
        }else{
            $query->orderBy($orderBy,$order);
        }

        

        if ($this->paginatedList === true) {

            $item_per_page = 25;

            $categories = $query->paginate($item_per_page)->appends($search_query);
            $categories->pagination_summary = get_pagination_summary($categories);
        } else {
            $categories = $query->get();
        }

        return $categories;
    }


    public function itemLists($data =null, $category)
    {
        $search_query = [];

        $order = 'desc';
        $orderBy = 'created_at';

        $query = Item::leftJoin('settings', 'items.source_site', '=', 'settings.id');
        $query->select(\DB::raw('item_id, sum(actual_sale) as actual_sale, name, 
        classification, max(price_cents) as price_cents, max(number_of_sales) as number_of_sales, 
        author_username, max(items.created_at) as created_at, settings.site, items.source_site'))
        ->where('classification', $category->path)
        ->where('source_site', $category->source_site);
        
        
        // $query = Item::select('id','item_id','actual_sale','name','classification','price_cents','number_of_sales','author_username','created_at')->where('classification', $category->path);

        if(!empty($data)){

            $search_query = [
                "search" => $data["search"] ?? '',
                "search_area" => $data["search_area"] ?? '',
                "search_from_date" => $data["search_from_date"] ?? '',
                "search_to_date" => $data["search_to_date"] ?? '',
                "search_date_range" => $data["search_date_range"] ?? ''
            ];

            if(isset($data["search"]) && isset($data["search_area"])){

                if($data["search_area"] == 'name'){

                    $query->where($data["search_area"],'like','%'.$data["search"].'%');
                
                }else if($data["search_area"] == 'classification'){

                    $query->whereHas('category', function ($searchQuery) use($data) {
                        $searchQuery->where('name', 'like', '%'.$data["search"].'%');
                    });

                }else if($data["search_area"] == 'tags'){

                    $query->whereJsonContains($data["search_area"],$data["search"]);
                
                }else{
                    $query->where($data["search_area"],$data["search"]);
                }
                
            }
            if(isset($data["search_from_date"]) && isset($data["search_to_date"])){
                
                $query->whereBetween('created_at', [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
            }

        }

        $query->groupBy('item_id', 'name', 'classification', 'author_username', 'settings.site', 'items.source_site');
        $query->orderBy($orderBy,$order);

        if ($this->paginatedList === true) {

            $item_per_page = 25;

            $items = $query->paginate($item_per_page)->appends($search_query);
            $items->pagination_summary = get_pagination_summary($items);
        } else {
            $items = $query->get();
        }

        return $items;

    }


    public function getWeeklyBestSeller($category){

        // $data['from_date'] = $start = '2021-07-12 00:00:00';
        // $data['to_date'] = $end = '2021-07-19 00:00:00';

        $category_data = Category::where('id',$category)->first();

        $data['from_date'] = $start = Carbon::now()->subWeek()->startOfWeek();
        $data['to_date'] = $end = Carbon::now()->subWeek()->endOfWeek();

        $data['category_name'] = $category_data->name;


        $query =  Category::leftJoin('items', function($join)
        {
            $join->on('categories.path', '=', 'items.classification');
            $join->on('categories.source_site', '=', 'items.source_site');
        });

        $data['items'] = $query->selectRaw("item_id, items.name,items.source_site,
        max(number_of_sales) as number_of_sales,
        SUM(actual_sale) AS actual_sale")
        ->where('classification',  $category_data->path)
        ->where('items.source_site', $category_data->source_site)
        ->whereBetween('items.created_at', [$start, $end])
        ->orderBy('actual_sale', 'desc')
        ->groupBy('item_id', 'items.name','items.source_site')
        ->get();

        return $data;
    }


}