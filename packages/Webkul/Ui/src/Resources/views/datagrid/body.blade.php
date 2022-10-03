<tbody>

    @if (count($records))
        @foreach ($records as $key => $record)

            @php $firstRowCss='' @endphp
            @php $firstCellCss='' @endphp
            @php $parent='' @endphp

            @if(isset($record->parent_id) && !empty($record->parent_id) && $record->parent_id > 0)
                @php $firstRowCss='display:none;' @endphp
                @php $firstCellCss='padding-left:30px;' @endphp
                @php $parent=$record->parent_id @endphp
            @endif

            <tr class="parent_{{ $parent }}" style="{{$firstRowCss}}">

                {{--@if(isset($record->$columns['product_type']))
                <td></td>
                    @endif
                --}}

                @if ($enableMassActions)
                    <td  style="{{ $firstCellCss }}">

                        <div class="custom-control custom-checkbox">
                            <input id="{{ $record->{$index} }}" class="custom-control-input" type="checkbox" v-model="dataIds" @change="select" value="{{ $record->{$index} }}">
                            <label class="custom-control-label" for="{{ $record->{$index} }}"></label>
                        </div>

                    </td>
                @endif

                @foreach ($columns as $column)

                    @php
                        $columnIndex = explode('.', $column['index']);

                        $columnIndex = end($columnIndex);
                    @endphp

                    @if (isset($column['wrapper']))

                        @php $display='table-cell;' @endphp
                        @if (isset($column['hidden']) && $column['hidden'] == true)
                            @php $display='none' @endphp
                        @endif
                        @if (isset($column['closure']) && $column['closure'] == true)
                            <td style="display:{{ $display  }};" class="paragraph" data-value="{{ $column['label'] }}">{!! $column['wrapper']($record) !!}</td>
                        @else
                            <td style="display:{{ $display  }}" class="paragraph" data-value="{{ $column['label'] }}">{{ $column['wrapper']($record) }}</td>
                        @endif

                    @else

                        @if ($column['type'] == 'price')
                            @if (isset($column['currencyCode']))
                                <td data-value="{{ $column['label'] }}" class="paragraph">{{ core()->formatPrice($record->{$columnIndex}, $column['currencyCode']) }}</td>
                            @else
                                <td data-value="{{ $column['label'] }}" class="paragraph">{{ core()->formatBasePrice($record->{$columnIndex}) }}</td>
                            @endif
                        @elseif($column['type'] == 'image')
                            @if(isset($record->product_image))
                                <td data-value="{{ $column['label'] }}" class="paragraph"><img src="{{getenv('WASSABI_STORAGE').'/'.$record->product_image}}" style="width:75px;height:75px;"  /></td>
                            @else
                                <td data-value="{{ $column['label'] }}" class="paragraph"></td>
                            @endif
                        @elseif($column['type'] == 'family')
                                <td data-value="{{ $column['label'] }}" class="paragraph"><p style="font-weight: bold;" > @if($record->type=='configurable') Variable  @else {{ ucwords($record->type)}} @endif</p><p>{{$record->attribute_family}}</p></td>
                        @elseif ($column['type'] === 'status')
                            <td data-value="{{ $column['label'] }}" class="paragraph">
                                @if($record->{$columnIndex})
                                    <span class="status-published">
                                        {{ __('admin::app.cms.pages.published') }}
                                    </span>
                                @else
                                    <span class="status-draft">
                                        {{ trans('admin::app.cms.pages.draft') }}
                                    </span>
                                @endif
                            </td>
                        @else
                            <td data-value="{{ $column['label'] }}" class="paragraph"> @if(!empty($record->{$columnIndex})){{ $record->{$columnIndex} }} @endif

                                @if($column['index']=='instructor_number' && !empty($record->certification))
                                    <br>
                                    <a href="{{getenv('WASSABI_STORAGE').'/'.$record->certification}}" target="_blank">certification</a>
                                @endif
                            </td>
                        @endif
                    @endif

                @endforeach
                @if ($enableActions)
                    <td class="actions" style="white-space: nowrap; width: 100px;" data-value="{{ __('ui::app.datagrid.actions') }}">
                        <div class="action">
                            @if(isset($record->product_type) && !empty($record->product_type) && $record->product_type == 'configurable')
                               <a class="expend_link"   id="expend_link_{{ $record->product_id }}"   data-parentid="{{ $record->product_id }}" data-index="{{ $record->{$index} }}"   ><i class="far fa-plus-circle"></i></a>
                               <a class="collapse_link" id="collapse_link_{{ $record->product_id }}" data-parentid="{{ $record->product_id }}" data-index="{{ $record->{$index} }}" ><i class="far fa-minus-circle"></i></a>
                            @endif

                            @foreach ($actions as $action)

                                @php
                                    $toDisplay = (isset($action['condition']) && gettype($action['condition']) == 'object') ? $action['condition']() : true;
                                @endphp

                                @if ($toDisplay)

                                    <a
                                        data-method="{{ $action['method'] }}"
                                        data-token="{{ csrf_token() }}"

                                        @if (isset($action['target']))
                                            target="{{ $action['target'] }}"
                                        @endif

                                        @if (isset($action['title']))
                                            title="{{ $action['title'] }}"
                                        @endif

                                        @if(isset($action['custom']) && $action['custom'])
                                            @if($action['custom_type'] === 'seller-product')

                                                    @if(!empty($record->url_key))
                                                          href="{{route('shop.product.index', $record->url_key)}}"
                                                    @endif

                                            @elseif($action['custom_type'] === 'print-order')
                                                data-id="{{$record->id}}"

                                            @elseif($action['custom_type'] === 'contact-seller')
                                             data-id="{{$record->id}}" @if(isset($record->customer_id)) data-customerid="{{$record->customer_id}}" @endif @if(isset($record->customer_seller_id)) data-customersellerid="{{$record->customer_seller_id}}" @endif

                                            @elseif($action['custom_type'] === 'view-post')
                                                href="{{ route($action['route'], ['slug' => $record->url_key]) }}"
                                                target="_blank"

                                            @else($action['custom_type'] === 'pagebuilder')
                                                href="{{route($action['route'], ['page_id' => $record->pb_page_id])}}"
                                            @endif
                                        @else

                                            @if($action['route'] === 'marketplace.account.products.delete')
                                                class="delete-product"
                                            @endif

                                            data-action="{{ route($action['route'], $record->{$index}) }}"
                                            @if ($action['method'] == 'GET')
                                                href="{{ route($action['route'], $record->{$action['index'] ?? $index}) }}"
                                            @endif

                                            @if ($action['method'] != 'GET')
                                                v-on:click="doAction($event)"
                                            @endif
                                        @endif>

                                        @if( isset($action['route']))
                                            @if($action['route'] !='marketplace.account.refunds.view'  )
                                                <span class="{{ $action['icon'] }}"></span>
                                                @else
                                                @if(isset($record->seller_payout_status))
                                                    @if($record->seller_payout_status=='refunded')
                                                        <span class="{{ $action['icon'] }}"></span>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            <span class="{{ $action['icon'] }}"></span>
                                        @endif

                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10" style="text-align: center;">{{ $norecords }}</td>
        </tr>
    @endif
</tbody>



