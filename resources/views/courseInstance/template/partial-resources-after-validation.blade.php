{{--loop through the old request to recreate resource rows  --}}
@for ($i = 0; $i < old('resource_count', 0)+1; $i++)
      {{--this line prevents rows that were deleted from being displayed--}}
      @if (old($i .'_resource_id', 0) != null or old('resource_count', 0) == 0)
        <tr>
            <td>
                {{ Form::select($i .'_resource_id', $resourceList, '', ['class' => 'form-control', 'id' => $i .'_resource_id']) }}
            </td>
            <td>
                {{--{{ Form::select($i .'_resource_type', $resourceTypes, '', ['class' => 'form-control', 'id' => $i . '._resource_type']) }}--}}
                @inject('resourceTypes', 'App\Http\Controllers\CourseInstance\TemplateController')
                {{ Form::select($i .'_resource_type', $resourceTypes->getResourceTypes(old($i .'_resource_id')), old($i .'_resource_type'), ['class' => 'form-control', 'id' => $i .'_resource_type']) }}
            </td>
            <td align="center">
                <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $i }}"  {{ old('radio_is_imr')=="$i" ? 'checked='.'"'.'checked'.'"' : '' }} />
            </td>
            <td>
                {{ Form::select($i .'_setup_time', Session::get('minutes'), '', ['class' => 'form-control', 'id' => $rownum ."_setup_time"]) }}
            </td>
            <td>
                {{ Form::input($i .'_start_time', $i .'_start_time', old($i .'_start_time'), ['class' => 'form-control timepicker' ]) }}
            </td>
            <td>
                {{ Form::input($i .'_end_time', $i .'_end_time', old($i .'_end_time'), ['class' => 'form-control timepicker' ]) }}
            </td>

            <td>
                {{ Form::select($i .'_teardown_time', Session::get('minutes'), '', ['class' => 'form-control', 'id' => $i ."_teardown_time"]) }}
            </td>
            <td>
                <input type="button" class="ibtnDel btn btn-md btn-danger "  value="{{ trans('buttons.general.crud.delete') }}">
            </td>
        </tr>
      @endif
@endfor