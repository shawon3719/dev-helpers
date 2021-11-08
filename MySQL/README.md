## Left Join

```
        $data['top_categories'] = Item::leftJoin('categories', 'categories.path', 'items.classification')
        ->selectRaw('categories.name, path')
        ->whereIn('item_id', $item_ids)
        ->whereRaw('path not LIKE "%/%"')
        ->groupBy('name', 'path')
        ->get()->pluck('name');
```
## Case

```
select
(case when status !='paid' then status else 'delivered' end) as A_count,
count(case when date <='2016-07-12' and subtotal>10000 then 1 else null end) as B_count
from orders
group by status

```
## Weekly data

```
$weekQuery = Item::selectRaw("SUBSTRING(YEARWEEK(created_at), 5) AS week, 
                    STR_TO_DATE(CONCAT(YEARWEEK(created_at), ' Sunday'), '%X%V %W') AS start,
                    STR_TO_DATE(CONCAT(YEARWEEK(created_at), ' Saturday'), '%X%V %W') AS end");

                    if(!empty($my_item)){
                        $weekQuery->whereIn('item_id', $all_items)
                        ->where('source_site', $item_source_site);
                    }else{
                        $weekQuery->where('item_id', $request->item_id)
                        ->where('source_site', $item_source_site);
                    }
                    
                    $data['weeks'] = $weekQuery->whereBetween('created_at', [date("Y-m-d", strtotime($request["search_from_date"])), date("Y-m-d", strtotime($request["search_to_date"]))])
                    ->orderBy('week', 'asc')
                    ->groupBy('week', 'start', 'end')
                    ->get();
        
                    $query = Item::selectRaw("item_id, name, source_site, author_username,
                    SUBSTRING(YEARWEEK(created_at), 5) AS week, 
                    STR_TO_DATE(CONCAT(YEARWEEK(created_at), ' Sunday'), '%X%V %W') AS start,
                    STR_TO_DATE(CONCAT(YEARWEEK(created_at), ' Saturday'), '%X%V %W') AS end,
                    max(number_of_sales) as number_of_sales,
                    SUM(actual_sale) AS actual_sale,url");


                    if(!empty($my_item)){
                        $query->whereIn('item_id', $all_items)
                        ->where('source_site', $item_source_site);
                    }else{
                        $query->where('item_id', $request->item_id)
                        ->where('source_site', $item_source_site);
                    }


                    $items_list = $query->whereBetween('created_at', [date("Y-m-d", strtotime($request["search_from_date"])), date("Y-m-d", strtotime($request["search_to_date"]))])
                    ->orderBy('week', 'asc')
                    ->groupBy('week', 'start', 'source_site', 'author_username', 'end', 'item_id', 'name', 'url')
                    ->get()
                    ->groupBy([function($item) {
                        return $item->week;
                    }, 'name']);
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
## 

```
```
