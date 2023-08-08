@extends ('backend.layouts.app')

@section ('title', trans('menus.backend.user-profile-questions.title'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style("https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css") }}
    {{ Html::style("http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css") }}
@stop

@section('page-header')
    <h1>{{ trans('menus.backend.user-profile-questions.title') }}</h1>
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.user-profile-questions.all-retired') }}</h3>

            <div class="box-tools pull-right">
                @include('userprofilequestions.partial-header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="questions-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('labels.user-profile-questions.question_id') }}</th>
                        <th>{{ trans('labels.user-profile-questions.question_txt') }}</th>
                        <!-- <th>{{ trans('labels.user-profile-questions.site_id') }}</th> -->
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody id="tablecontents">
                    @foreach($questions as $question)
                    <tr class="row1" data-id="{{ $question->question_id }}">
                      <td>
                        <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                        <i class="fa fa-sort"></i>
                        </div>
                      </td>
                      <td>{{ $question->question_id }}</td>
                      <td>{{ $question->question_text }}</td>
                      <!-- <td>{{ $question->question_id }}</td> -->
                      <td>
                        <a href="{{route('edit_user_profile_question', ['id' => $question->question_id])}}" class="btn btn-sm btn-primary">
                          <i class="fa fa-lg fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                        </a>
                        <a href="{{route('activate_user_profile_question', ['id' => $question->question_id])}}" class="btn btn-sm btn-success">
                          <i class="fa fa-lg fa-play" data-toggle="tooltip" data-placement="top" title="Activate"></i>
                        </a>  
                        <a href="{{route('delete_user_profile_question', ['id' => $question->question_id])}}" class="btn btn-sm btn-danger">
                          <i class="fa fa-lg fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i>
                        </a> 
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->

    </div><!-- /.box-success -->

@stop

@section('after-scripts')

<!-- jQuery UI -->
<script type="text/javascript" src="http://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>

<!-- Datatables Js-->
<script type="text/javascript" src="http://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js"></script>
<!-- <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script> -->

<script type="text/javascript">
$(function () {
$("#questions-table").DataTable();

  $( "#tablecontents" ).sortable({
    items: "tr",
    cursor: 'move',
    opacity: 0.6,
    update: function() {
        sendDisplayOrderToServer();
    }
  });

  function sendDisplayOrderToServer() {

    var display_order = [];
    $('tr.row1').each(function(index,element) {
      display_order.push({
        question_id: $(this).attr('data-id'),
        position: index+1
      });
    });

    $.ajax({
      type: "POST", 
      dataType: "json", 
      url: "{{ url('questiontables.data') }}",
      data: {
        display_order:display_order,
        _token: '{{csrf_token()}}'
      },
      success: function(response) {
          if (response.status == "success") {
            console.log(response);
          } else {
            console.log(response);
          }
      }
    });

  }
});

</script>

@stop  

<!-- @section('after-scripts')
    
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js") }}
    
    <script>
        $(function() {
            $('#questions-table').DataTable({
                rowReorder: {selector: 'tr'},
                processing: true,
                serverSide: true,
                ajax: '{!! url('questiontables.data') !!}',
                columns: [
                    { data: 'question_id', name: 'question_id' },
                    { data: 'question_text', name: 'question_txt' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });
        });
    </script>

@stop -->





<!-- @section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}

    <script>
        $(function() {
            $('#questions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('questionstable.data') !!}',
                columns: [
                    { data: 'question_id', name: 'question_id' },
                    { data: 'question_text', name: 'question_txt' },
                    { data: 'site_id', name: 'site_id' },
                ]
            });
        });
    </script>
@stop -->