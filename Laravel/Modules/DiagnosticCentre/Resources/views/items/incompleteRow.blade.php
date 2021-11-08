@if (Session::get('incompleteRow'))
<div class="alert alert-danger">
    <p>{{ count(Session::get('incompleteRow')) }}  Excel rows are incomplete.</p>
    @foreach(Session::get('incompleteRow') as $key=>$row)
        <p>➤ In row <b>{{$key+2}}</b> following columns are incomplete</p>
        <ul>
            <li>Name : {{ isset($row['name']) ? $row['name'] : 'Name field is required' }}</li>
            <li>Price : {{ isset($row['price']) ? $row['price'] : 'Price field is required' }}</li>
        </ul>
    @endforeach
</div>
<div style="text-align: right; margin: 5px">
   <Button class="btn btn-primary" onclick="window.location.reload()">Clear</Button>
</div>
@endif
@if (Session::get('incorrectDataFormat'))
<div class="alert alert-danger">
    <p>{{ count(Session::get('incorrectDataFormat')) }}  Excel row are incorrect data please check that row.</p>
    @foreach(Session::get('incorrectDataFormat') as $row)
    <ul><li>Incorrect Data</li></ul>
    @endforeach
<div></div>
</div>
<div>
    <a href="{{ url('reload')}}" class="btn btn-dark">Close</a>
</div>
@endif