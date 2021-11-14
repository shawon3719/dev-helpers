```
    $('.select-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', 'selected')
    $select2.trigger('change')
  })
  $('.deselect-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', '')
    $select2.trigger('change')
  })

  $('.select2').select2()
```


## Select all by matched items on search result

Select 2 blade

```
<select class="form-control matched-selection select2 {{ $errors->has('competitor_item_ids') ? 'is-invalid' : '' }}" name="competitor_item_ids[]" id="competitor_item_ids" multiple required>
                    @foreach($items as $item)
                        <option value="{{ $item->item_id }}" {{ in_array($item->item_id, old('competitor_item_ids', [])) ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
```

js

```
<script>

// append select all matche item button
$('.matched-selection')
    .select2()
    .on('select2:open', () => {
        $(".select2-results:not(:has(a))").append('<a type="button" class="select_all_matched_items" style="padding: 10px; margin-top: -5px; color: #007bff; margin-bottom: 10px!important; height: 30px;display: inline-block;">Select All Matched Items</a>');
})

// on clicck select all matched items

$(document).on('click', ".select_all_matched_items", function() {
    
    e = jQuery.Event("keyup");        
    e.keyCode = 65;
    e.ctrlKey = true;
    selectAllMatchedResult(e);

});

// select all by pressing ctrl+A method

$(document).on('keyup', ".select2-search__field", function(e) {
    
    if (e.keyCode == 65 && e.ctrlKey) { //if Ctrl+A pressed

        //Select only filtered results
        $.each($(".select2-results__options").find('li'), function(i, item) {
            $(".select2 > option:contains('" + $(item).text() + "')").prop("selected", "selected");
        });
        
        $(this).val("").click(); //Remove entered letters and close suggestions
       
        $(".matched-selection").trigger("change");  //Select with select2
        $('#select2-drop-mask').select2("close");
    }
});

// Select All from button click Event

function selectAllMatchedResult(e){

    if (e.keyCode == 65 && e.ctrlKey) {

        $.each($(".select2-results__options").find('li'), function(i, item) {
            $(".select2 > option:contains('" + $(item).text() + "')").prop("selected", "selected");
        });

        $(".matched-selection").trigger("change");
        $('#select2-drop-mask').select2("close");
    }
}

</script>
```

