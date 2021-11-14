<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Item;
use App\Models\MyItem;
use App\Models\Setting;
use App\Models\Tag;
use Carbon\Carbon;
use Herbert\Envato\Auth\Token;
use Herbert\Envato\Exceptions\TooManyRequestsException;
use Herbert\EnvatoClient;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ItemService
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
        $orderBy = 'created_at';

        $query = Item::with('category')->leftJoin('settings', 'items.source_site', '=', 'settings.id');
        
        $query->select(\DB::raw('item_id, sum(actual_sale) as actual_sale, name, 
        classification, max(price_cents) as price_cents, max(number_of_sales) as number_of_sales, 
        author_username, max(items.created_at) as created_at, settings.site, items.source_site'));

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
                
                $query->whereBetween('items.created_at', [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
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

    public function authorItemLists($data = null,$author,$source_site)
    {
        $search_query = [];

        $order = 'desc';
        $orderBy = 'created_at';

        $query = Item::with('category')->leftJoin('settings', 'items.source_site', '=', 'settings.id');
        
        $query->select(\DB::raw('item_id, sum(actual_sale) as actual_sale, name, 
        classification, max(price_cents) as price_cents, max(number_of_sales) as number_of_sales, 
        author_username, max(items.created_at) as created_at, settings.site, source_site'))
        ->where('author_username', $author)
        ->where('source_site', $source_site);

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

        $query->groupBy('item_id', 'name', 'source_site', 'classification', 'author_username', 'settings.site');
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

    public function getSimilarItemsData($data,$item_id){

        $data['search'] = $data->search;
        $data['search_from_date'] = $data->search_from_date;
        $data['search_to_date'] = $data->search_to_date;
        $data['search_date_range'] = $data->search_date_range;
        $data['filter_type'] = $data->filter_type;

        $data['weekly_sale'] = Item::select(\DB::raw('sum(actual_sale) as weekly_sale'))
        ->where('item_id',$item_id)
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->first()->weekly_sale;

        $data['monthly_sale'] = Item::select(\DB::raw('sum(actual_sale) as monthly_sale'))
        ->where('item_id',$item_id)
        ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
        ->first()->monthly_sale;

        $data['yearly_sale'] = Item::select(\DB::raw('sum(actual_sale) as yearly_sale'))
        ->where('item_id',$item_id)
        ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
        ->first()->yearly_sale;

        $data['item'] = Item::leftJoin('settings', 'items.source_site', '=', 'settings.id')
        ->select(\DB::raw('items.*, settings.site as site'))->orderBy('created_at','desc')->where('item_id',$item_id)->first();

        $search_query = [];

        $order = 'desc';
        $orderBy = 'created_at';

        $query = Item::select('id','item_id','actual_sale','rating','price_cents','number_of_sales','created_at')->where('item_id',$item_id);
        
        if($data['search_from_date'] !=null && $data['search_to_date'] !=null){
            $search_query = [
            "search" => $item["search"] ?? '',
            "search_from_date" => $data["search_from_date"] ?? '',
            "search_to_date" => $data["search_to_date"] ?? '',
            "search_date_range" => $data["search_date_range"] ?? '',
            "filter_type" => $data['filter_type'] ?? ''
            ];
        }

        if($data['search_from_date'] !=null && $data['search_to_date'] !=null){

            $from_date = $data['search_from_date'];
            $to_date = $data['search_to_date'];

            $query->whereBetween('created_at', [$from_date, $to_date]);

        }

        if($data['filter_type'] !=null){
            $query->orderBy($orderBy,$data['filter_type']);
        }else{
            $query->orderBy($orderBy,$order);
        }

        if ($this->paginatedList === true) {

            $item_per_page = 25;

            $data['similar_items'] = $query->paginate($item_per_page)->appends($search_query);
            $data['similar_items']->pagination_summary = get_pagination_summary($data['similar_items']);
        } else {
            $data['similar_items'] = $query->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });
        }
        return $data;

    }

    public function getAuthorlists($data = null){
        $search_query = [];

        $query = Author::leftJoin('settings', 'authors.source_site', '=', 'settings.id');

        $query->select([
            'author_username',
            'settings.site',
            'source_site',
            'author_wise_unique_items_count',
            'author_wise_last_7_days_sale',
            'author_wise_last_30_days_sale',
            ])
            ->groupBy('author_username',
            'author_wise_unique_items_count',
            'author_wise_last_7_days_sale',
            'author_wise_last_30_days_sale',
            'settings.site',
            'source_site');
        
        if(isset($data["search"])){

            $search_query = [
                "search" => $data["search"] ?? ''
            ];

            $query->where('author_username','like','%'.$data["search"].'%');
                
        }

        $query->orderBy('author_wise_unique_items_count','desc');

        if ($this->paginatedList === true) {

            $item_per_page = 25;

            $authors = $query->paginate($item_per_page)->appends($search_query);
            $authors->pagination_summary = get_pagination_summary($authors);
        } else {
            $authors = $query->get();
        }

        return $authors;
    }


    public function getTagsList($data = null){

        $search_query = [];

        $order = 'desc';
        $orderBy = 'unique_items_count';

        $query = Tag::with('setting');

        if(!empty($data)){

            $search_query = [
                "search" => $data["search"] ?? '',
                "max_filter_range" => $data['max_filter_range'] ?? '',
                "min_filter_range" => $data['min_filter_range'] ?? '',
                "filter_type" => $data['filter_type'] ?? ''
            ];

            if(isset($data["search"])){
                $query->where('tags.name', 'like', '%'.$data["search"].'%');
            }

        }

        $query->select([
            'tags.name',
            'tags.id',
            'tags.source_site',
            'tags.unique_items_count',
            'tags.last_7_days_sale',
            'tags.last_30_days_sale',
            ])
            ->groupBy('tags.id','tags.name','tags.source_site');

        if(isset($data['max_filter_range']) && isset($data['min_filter_range'])){
            $query->has('items', '>=', $data['min_filter_range'])->has('items', '<=', $data['max_filter_range']);
        }

        if(isset($data['filter_type'])){
            $query->orderBy($orderBy,$data['filter_type']);
        }else{
            $query->orderBy($orderBy,$order);
        }

        if ($this->paginatedList === true) {

            $item_per_page = 10;

            $items = $query->paginate($item_per_page)->appends($search_query);
            $items->pagination_summary = get_pagination_summary($items);
        } else {
            $items = $query->get();
        }

        return $items;
        
    }


    public function getAuthorTags($data = null, $author, $source_site){

        $search_query = [];

        $order = 'desc';
        $orderBy = 'unique_items_count';

        $query = Tag::with('setting')->leftJoin('item_tag', 'tags.id', '=', 'item_tag.tag_id')
        ->leftJoin('items', 'item_tag.item_id', '=', 'items.id')
        ->where('author_username', 'like', $author)
        ->where('items.source_site', $source_site);

        if(!empty($data)){

            $search_query = [
                "search" => $data["search"] ?? '',
                "max_filter_range" => $data['max_filter_range'] ?? '',
                "min_filter_range" => $data['min_filter_range'] ?? '',
                "filter_type" => $data['filter_type'] ?? ''
            ];

            if(isset($data["search"])){
                $query->where('tags.name', 'like', '%'.$data["search"].'%');
            }

        }

        // $query->orderBy($orderBy,$order);

        $query->select([
            'tags.name',
            'tags.id',
            'tags.source_site',
            'tags.unique_items_count',
            'tags.last_7_days_sale',
            'tags.last_30_days_sale',
            ])
            ->groupBy('tags.id','tags.name','tags.source_site',);

        if(isset($data['max_filter_range']) && isset($data['min_filter_range'])){
            $query->has('items', '>=', $data['min_filter_range'])->has('items', '<=', $data['max_filter_range']);
        }

        if(isset($data['filter_type'])){
            $query->orderBy($orderBy,$data['filter_type']);
        }else{
            $query->orderBy($orderBy,$order);
        }

        if ($this->paginatedList === true) {

            $item_per_page = 10;

            $items = $query->paginate($item_per_page)->appends($search_query);
            $items->pagination_summary = get_pagination_summary($items);
        } else {
            $items = $query->get();
        }

        return $items;
        
    }


    public function getItemsByTag($data = null,$tag, $source_site){

        $search_query = [];

        $order = 'desc';
        $orderBy = 'created_at';

        $query = Item::with('category')->leftJoin('settings', 'items.source_site', '=', 'settings.id');
        $query->select(\DB::raw('item_id, sum(actual_sale) as actual_sale, name, 
        classification, max(price_cents) as price_cents, max(number_of_sales) as number_of_sales, 
        author_username, max(items.created_at) as created_at, settings.site, items.source_site'))
        ->whereJsonContains('tags', $tag)
        ->where('items.source_Site', $source_site);

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
                
                $query->whereBetween('items.created_at', [date("Y-m-d", strtotime($data["search_from_date"])), date("Y-m-d", strtotime($data["search_to_date"]))]);
            }

        }

        $query->groupBy('item_id', 'name', 'classification', 'items.source_site', 'author_username', 'settings.site');
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

    public function createMyItem($request){

        $token = new Token(config('constants.personal_token'));
        $client = new EnvatoClient($token);

        $setting = Setting::where('id',$request->source_site)->first();

        $parameters = json_decode($setting->parameters);

        for($i=1; $i<=$parameters->page; $i++){
            $instructions = (
                [
                    'username'          => $request->author_username,
                    'site'              => $setting->site,
                    'page'              => $i,
                    'page_size'         => $parameters->page_size,
                    'sort_by'           => $parameters->sort_by,
                    'sort_direction'    => $parameters->sort_direction,
                ]
            );

            try {
                $items = $client->catalog->items($instructions);

                if(count($items->results['matches']) == 0){
                    break;
                }else{
                    echo count($items->results['matches']).' Data has been found and processing to store';
                    echo "\n";
                }
                
                foreach($items->results['matches'] as $value){
    
                    $existing_my_item = MyItem::select('id')->whereItemId($value['id'])->first();
    
                    MyItem::updateOrCreate(
                        [
                            'id'       => $existing_my_item->id ?? null,
                        ],
                        [
                            'item_id'       => $value['id'],
                            'source_site'   => $request->site,
                            'created_by'    => Auth::user()->id ?? 1,
                        ]
                    );
    
                    $actual_sale = 0;
                    try {
                        $previous_day_sales = Item::select('item_id','number_of_sales','created_at')->where('item_id', $value['id'])->whereDate('created_at', '<' ,  date("Y-m-d h:i:s"))->orderBy('created_at', 'desc')->first();
                        
                        if($previous_day_sales){
                            $actual_sale =  $value['number_of_sales'] - $previous_day_sales->number_of_sales;
                        }
    
                        $item = Item::select('id')->where('item_id', $value['id'])->whereDate('created_at', date('Y-m-d'))->where('source_site',$request->source_site)->first();
                        
                        $item_data = Item::updateOrCreate(
                            [
                                'id'       => $item->id ?? null,
                            ],
                            [
                                'item_id'               => $value['id'],
                                'name'                  => $value['name'],
                                'description'           => $value['description'],                            
                                'classification'        => $value['classification'], 
                                'classification_url'    => $value['classification_url'], 
                                'price_cents'           => $value['price_cents'], 
                                'number_of_sales'       => $value['number_of_sales'], 
                                'actual_sale'           => $actual_sale, 
                                'author_username'       => $value['author_username'], 
                                'author_url'            => $value['author_url'],
                                'author_image'          => $value['author_image'],
                                'thumbnail'             => json_encode($value['previews']),
                                'tags'                  => json_encode($value['tags']),
                                'url'                   => $value['url'],
                                'summary'               => $value['summary'],
                                'rating'                => json_encode($value['rating']),
                                'item_published_at'     => $value['published_at'],
                                'item_updated_at'       => $value['updated_at'],                            
                                'created_by'            => 1,
                            ]
                        );
                        if($item_data){
                           Artisan::call('make:item-tag-relationship', ['item' => $item_data]);
                        }
                        // return true;
                    } catch (\Illuminate\Database\QueryException $e) {
                        Log::error($e);
                    } 
                }
            }
            catch (TooManyRequestsException $e) {
                Log::error($e);
                // Get the number of seconds remaining (float)
                $secondsRemaining = $e->getSecondsRemaining();
            
                // Get the timestamp for when we can make our next request
                $timestamp = $e->getRetryTime();
            
                // Sleep until the rate limiting has ended
                $e->wait();
                return false;
            }




        }

        // Artisan::call('delete-duplicate-data:sameday');

        return true;
    }



}