## In Header

```
<li class="nav-item dropdown me-1">
                            <a class="nav-link text-gray-600" href="{{route('diagnostic-centre.pathology-bills.create')}}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Add New Report"  type="button" id="showHideSearch">
                                <i class="fas fa-file-medical-alt fs-4"></i>
                            </a>
                        </li>
                        <li style="padding-top: 6px; display:none" id="search_box">
                            <select style="width: 320px!important; border-radius: 20px" class="js-diagnostic-bill-search-ajax search form-control" id="searchleft" type="search" name="q" placeholder="Bill Search">
                                <option value="" selected="selected"><i class="ficon bx bx-search"></i>Bill Search</option>
                            </select>
                        </li>
```

## JS File

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.full.js"></script>


```
  $(document).ready(function() {

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

      // On click search icon to show or hide search input
      $('#showHideSearch').click(function(e) {
          e.preventDefault();
          $(".js-diagnostic-bill-search-ajax").val('').trigger('change');
          $("#search_box").animate({
              width: "toggle"
          });
      });

      // On select bill go to bill details page
      $(".js-diagnostic-bill-search-ajax").on('select2:select', function (e) {
          var bill = e.params.data;
          window.open("{{URL::to('/diagnostic-centre/pathology-reports/create/report')}}"+'/'+bill.id, '_blank');
      });

      // Search for bill with select2
      var $ajax = $(".js-diagnostic-bill-search-ajax");
      var base_url = window.location.origin;

      // Format select options
      function formatRepo (repo) {
          
          if (repo.loading) return repo.text;

          var bill_icon = base_url+'/img/bill.png';
          
          var markup = "<div class='select2-result-repository clearfix'>" +
              "<div class='select2-result-repository__avatar'><img src='"+bill_icon+"' /></div>" +
              "<div class='select2-result-repository__meta'>" +
              "<div class='select2-result-repository__title'>" + repo.code + "</div>";

          if (repo.patient && repo.patient.name != null) {
              markup += "<div class='select2-result-repository__description'>" + repo.patient.name + "</div>";
          }

          markup += "<div class='select2-result-repository__statistics'>";

          if(repo.delivery_date != null){
              markup += "<div class='select2-result-repository__forks'>Delivery Date : " + repo.delivery_date + "</div>" +
              "<br>";
          }
          if(repo.delivery_time != null){
              markup += "<div class='select2-result-repository__stargazers'>Delivery Time : " + repo.delivery_time + " </div>" +
              "<br>";
          }
          if(repo.bill_date){
              markup += "<div class='select2-result-repository__watchers'>Bill Date : " + repo.bill_date + "</div>" +
              "<br>";
          }
          if(repo.total){
              markup += "<div class='select2-result-repository__watchers'>Amount : " + repo.total + "</div>" +
              "<br>";
          }
          if(repo.payment && repo.payment.due_amount != null){
              markup +=  "<div class='select2-result-repository__stargazers'>Due Amount : " + repo.payment.due_amount + "</div>";
              "<br>";
          }
          if(repo.referrer && repo.referrer.name != null && repo.referrer.last_degree){
              markup +=  "<div class='select2-result-repository__stargazers'>Referrer : " + repo.referrer.name+' ('+repo.referrer.last_degree+') '+ "</div>";
          }

          markup += "</div>" +
                  "</div></div>";

          return markup;
      }

      // Intial Select option
      function formatRepoSelection (repo) {
          return repo.code || repo.text;
      }

      // Get Employee Data.
      $ajax.select2({
      ajax: {
          url: "{{route('diagnostic-centre.pathology-bills.search')}}",
          dataType: 'json',
          delay: 250,
          data: (params) => {
              return {
                  q: params.term,
                  page: params.page || 1,
              };
          },
          processResults: function (data, params) {
              params.page = params.page || 1;
              return {
                  results: data.bills.data,
                  pagination: {
                      more: (params.page * 25) < data.bills.total
                  }
              };
          },
          cache: true
      },
      escapeMarkup: function (markup) { return markup; },
      minimumInputLength: 3,
      // allowClear: true,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection,
      theme: 'adwitt'
      });

  })
```