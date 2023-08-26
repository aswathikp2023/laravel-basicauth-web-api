@extends('layouts.app')

@section('content')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
@endpush

@push('scripts') 
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
@endpush
<center>
<div class="col-md-10">
    
<div class="card-body">
        <div class='col-md-3'>
            <a class='btn btn-warning btn-sm' href='{{url("employee/create")}}'>Create Employee</a>
        </div>
        <div class="table-responsive">
           <table id="table_id" class="display">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    </div>
</center>
    @push('scripts')
    <script type="text/javascript">
        $(function () {
    
    var datas_datatable = $('#table_id').DataTable({
            dom: 'Bfrtip',
            "paging": true,
            "pageLength": 10,
            processing: true,
            serverSide: true,
            ajax: "{{ url('employee/show') }}",
            columns: [
             { data: 'firstname' },
        { data: 'lastname' },
        { data: function(data){
            return data.company.name;
        } },
        { data: 'email' },
        { data: 'phone' },
        {   data: 'action'    }
            ],
        });
    
  } );
function deleteUser(id){
    $.ajax({
        url: "{{url('employee')}}"+'/'+id,
        type: 'DELETE', 
        data: {
        "_token": "{{ csrf_token() }}"
        },
        success: function(result){
            $('#table_id').DataTable().draw(false);
  }});
  }
    </script>
   
@endpush
@endsection