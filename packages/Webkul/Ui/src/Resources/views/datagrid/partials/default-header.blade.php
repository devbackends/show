<thead v-if="massActionsToggle == false">
    <tr style="height: 65px;">
        @if (count($results['records']) && $results['enableMassActions'])
            <th class="grid_head" id="mastercheckbox" style="width: 50px;">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="allSelected" v-on:change="selectAll" id="checkboxSellectAll">
                    <label class="custom-control-label" for="checkboxSellectAll"></label>
                </div>
            </th>
        @endif

        @foreach($results['columns'] as $key => $column)
                @php $style='' @endphp

                @if (isset($column['hidden']) && $column['hidden'] == true)
                    @php $style.="display:none;" @endphp
                    @else

                @endif
                @if(isset($column['width']))
                    @php $width = $column['width']  @endphp
                    @php $style.="width:{{$width}}" @endphp
                @endif
            <th style="{{$style}}"


                @if(isset($column['sortable']) && $column['sortable'])
                    v-on:click="eventBus.$emit('applySort', '{{ $column['index'] }}')"
                @endif
            >
                {{ $column['label'] }}
            </th>
        @endforeach

        @if ($results['enableActions'])
            <th>
                {{ __('ui::app.datagrid.actions') }}
            </th>
        @endif
    </tr>
</thead>