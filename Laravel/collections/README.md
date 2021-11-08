```
$data['items'] = collect();
        
                $data['items']  = $data['items']->merge($items_list)->groupBy('name')->toArray();
                
                
                $intersect = $items_list->intersectByKeys( $items_list );
                
                $data['items_list'] = $intersect->all();
        
                $item_name = array();
                
        
                foreach($data['items_list'] as $items_list){
                    foreach($items_list as $name=>$value){
                        $item_name[$name] = $value;
                    }
                }
                
        
                $data['items_name_list'] =  array_unique($item_name);
```

## Key short ascending order

```
 ksort($data['items_list']);
```