## css and js cdn
```
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
```

## Laravel Global Session Toaster

```
@if(Session::has('errors'))
                        @foreach ($errors->all() as $error)
                            @php echo "<script> window.onload = function(){ showAlertMessage('error','{$error}'); }; </script>"; @endphp
                        @endforeach
                    @endif

                    @if(Session::has('success'))
                        @php $success = Session::get('success'); echo "<script> window.onload = function(){ showAlertMessage('success','{$success}'); }; </script>"; @endphp
                    @endif

                    @if(Session::get('success'))
                        @php $success = Session::get('success'); echo "<script> window.onload = function(){ showAlertMessage('success','{$success}'); }; </script>"; @endphp
                    @endif

                    @if(Session::get('failed'))
                        @php $failed = Session::get('failed'); echo "<script> window.onload = function(){ showAlertMessage('error','{$failed}'); }; </script>"; @endphp
                    @endif
```

```
<script>
  function showAlertMessage(type, message){

      if(type == 'error' || type == 'failed'){
        var color = '#dc3545';
      }
      if(type == 'success'){
          color = 'green';
      }

      Toastify({
          text: message,
          duration: 5000,
          close:true,
          gravity:"top",
          position: "center",
          backgroundColor: color,
      }).showToast();

    }
</script>
```
