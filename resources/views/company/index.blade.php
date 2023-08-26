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
            <a class='btn btn-warning btn-sm' href='{{url("company/create")}}'>Create Company</a>
        </div>
        <div class="table-responsive">
           <table id="table_id" class="display">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Logo</th>
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
            loadTable();
      
  });
  function loadTable(){
    
    var datas_datatable = $('#table_id').DataTable({
            // "lengthMenu": [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            dom: 'Bfrtip',
            "paging": true,
            "pageLength": 10,
            // buttons: [{
            //         extend: 'colvis',
            //         text: 'Column',
            //     },
            //     'pageLength',
            //     {
            //         extend: 'excel',
            //         text: 'Excel',
            //         exportOptions: {
            //             columns: ':visible th:not(:last-child)'
            //         }
            //     },
            //      {
            //         text: 'Tambah',
            //         action: function ( e, dt, node, config ) {
                        
            //         }
            //     } 
            // ],
            // responsive: true,
            processing: true,
            serverSide: true,
            // autoWidth: false,
            ajax: "{{ url('company/show') }}",
            columns: [
             { data: 'id' },
        { data: 'name' },
        { data: 'email' },
        { data: 'website' },
        { data: function(data){ 
            return '<img src="'+data.image_url+'" width="30px" height="30px" />';
        } },
        {   data: 'action'    },
                // {
                //     data: 'DT_RowIndex',
                //     orderable: false,
                //     searchable: false
                // },
                // // HERE DYNAMIC COLUMN THAT I WANT
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false,
                //     className: "action-center"
                // },
            ],
            // columnDefs: [
            //     {   
            //         "targets": [],
            //         "visible": false,
            //         "searchable": true
            //     }
            // ],
            // order: [
            //     [0, "asc"]
            // ],
        });
    
  } 
function deleteUser(id){
    $.ajax({
        url: "{{url('company')}}"+'/'+id,
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