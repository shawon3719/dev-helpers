
## CSS
```
<!-- daterange picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

```

## JS

```
<!-- date-range-picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

```


### Blade

```
<div>
            <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="text" value="{{old('search_date_range', '')}}" autocomplete="off" name="search_date_range" class="form-control float-right dateranges"  placeholder="Select Date Range..">
            </div>
            <small class="text-danger search_date_range_error"></small>
            <input class="form-control search_from_date" value="{{old('search_from_date', '')}}"  name="search_from_date" type="hidden">
            <input class="form-control search_to_date" value="{{old('search_from_date', '')}}"  name="search_to_date" type="hidden">
        </div>
```


## Script 

```
$(function(){



        /*******************************************/
        // Date Ranges
        /*******************************************/
        $('.dateranges').daterangepicker({
            "alwaysShowCalendars": true,
            // "autoApply": true,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'Y-MM-DD'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
            
        }); 

        $('input[name="search_date_range"]').on('apply.daterangepicker', function(ev, picker) {
            $('.search_from_date').val(picker.startDate.format('Y-MM-DD'));
            $('.search_to_date').val(picker.endDate.format('Y-MM-DD'));
            $(this).val(picker.startDate.format('Y-MM-DD') + ' - ' + picker.endDate.format('Y-MM-DD'));
        });

        $('input[name="search_date_range"]').on('cancel.daterangepicker', function(ev, picker) {
            $('.search_from_date').val('');
            $('.search_to_date').val('');
            $(this).val('');
        });
});
```
