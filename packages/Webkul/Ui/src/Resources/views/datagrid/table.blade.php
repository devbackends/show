
<div class="table">
    <datagrid></datagrid>

    @if (isset($results['paginated']) && $results['paginated'])
        @include('ui::datagrid.pagination', ['results' => $results['records']])
    @endif
</div>

@push('scripts')
    <script type="text/x-template" id="datagrid">
        <div class="grid-container">

            <DatagridHeader :columns="{{json_encode($results['columns'])}}"></DatagridHeader>

            <div class="table-responsive">
                <table class="table">
                    @include('ui::datagrid.partials.mass-action-header')

                    @include('ui::datagrid.partials.default-header')

                    @include('ui::datagrid.body', ['records' => $results['records'], 'actions' => $results['actions'], 'index' => $results['index'], 'columns' => $results['columns'],'enableMassActions' => $results['enableMassActions'], 'enableActions' => $results['enableActions'], 'norecords' => $results['norecords']])
                </table>
            </div>
        </div>
    </script>
    <script>
        $( document ).ready(function() {
            $('.collapse_link').css('display','none');

            $(document).on('click','.expend_link',function(e) {
                var parentid=$(this).data('parentid');
                $(".parent_"+parentid).css('display','table-row')
                $("#collapse_link_"+parentid).css('display','inline-block');
                $("#expend_link_"+parentid).css('display','none');
            });

            $(document).on('click','.collapse_link',function(e) {
                var parentid=$(this).data('parentid');
                $(".parent_"+parentid).css('display','none');

                $("#collapse_link_"+parentid).css('display','none');
                $("#expend_link_"+parentid).css('display','inline-block');

            });
        });
    </script>
    <script>
        Vue.component('datagrid', {
            template: '#datagrid',

            data: function() {
                return {
                    filterIndex: @json($results['index']),
                    gridCurrentData: @json($results['records']),
                    massActions: @json($results['massactions']),
                    massActionsToggle: false,
                    massActionsCustomOnSubmit: false,
                    massActionTarget: null,
                    massActionType: null,
                    massActionValues: [],
                    massActionTargets: [],
                    massActionUpdateValue: null,
                    url: new URL(window.location.href),
                    dataIds: [],
                    allSelected: false,
                }
            },

            mounted() {
                // Set mass actions from data from server
                this.setMassActions();
            },

            computed: {
                getOnSubmitMassAction() {
                    return this.massActionsCustomOnSubmit ? this.massActionsCustomOnSubmit : 'return confirm("{{ __('ui::app.datagrid.click_on_action') }}")';
                }
            },

            methods: {
                setMassActions() {
                    for (let massAction of this.massActions) {
                        let obj = {
                            type: massAction.type,
                            action: massAction.action
                        };

                        this.massActionTargets.push(obj);

                        if (massAction.type === 'update') {
                            this.massActionValues = massAction.options;
                        }
                    }

                    if (typeof this.massActions[0] != 'undefined') {
                        if (this.massActions[0].onSubmit) {
                            this.massActionsCustomOnSubmit = this.massActions[0].onSubmit;
                        }
                    }
                },

                changeMassActionTarget() {
                    if (this.massActionType == 'delete') {
                        for(i in this.massActionTargets) {
                            if (this.massActionTargets[i].type == 'delete') {
                                this.massActionTarget = this.massActionTargets[i].action;

                                break;
                            }
                        }
                    }

                    if (this.massActionType == 'update') {
                        for(i in this.massActionTargets) {
                            if (this.massActionTargets[i].type == 'update') {
                                this.massActionTarget = this.massActionTargets[i].action;

                                break;
                            }
                        }
                    }

                    document.getElementById('mass-action-form').action = this.massActionTarget;
                },

                //triggered when any select box is clicked in the datagrid
                select() {
                    this.allSelected = false;

                    if (this.dataIds.length == 0) {
                        this.massActionsToggle = false;
                        this.massActionType = null;
                    } else {
                        this.massActionsToggle = true;
                    }
                },

                //triggered when master checkbox is clicked
                selectAll() {
                    this.dataIds = [];

                    this.massActionsToggle = true;

                    if (this.allSelected) {
                        if (this.gridCurrentData.hasOwnProperty("data")) {
                            for (currentData in this.gridCurrentData.data) {

                                i = 0;
                                for(currentId in this.gridCurrentData.data[currentData]) {
                                    if (i==0)
                                        this.dataIds.push(this.gridCurrentData.data[currentData][this.filterIndex]);

                                    i++;
                                }
                            }
                        } else {
                            for (currentData in this.gridCurrentData) {

                                i = 0;
                                for(currentId in this.gridCurrentData[currentData]) {
                                    if (i==0)
                                        this.dataIds.push(this.gridCurrentData[currentData][currentId]);

                                    i++;
                                }
                            }
                        }
                    }
                },

                doAction(e) {
                    var element = e.currentTarget;

                    if (confirm('{{__('ui::app.datagrid.massaction.delete') }}')) {
                        axios.post(element.getAttribute('data-action'), {
                            _token : element.getAttribute('data-token'),
                            _method : element.getAttribute('data-method')
                        }).then(function(response) {
                            this.result = response;

                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            } else {
                                location.reload();
                            }
                        }).catch(function (error) {
                            location.reload();
                        });

                        e.preventDefault();
                    } else {
                        e.preventDefault();
                    }
                },

                captureColumn(id) {
                    element = document.getElementById(id);

                    console.log(element.innerHTML);
                },

                removeMassActions() {
                    this.dataIds = [];

                    this.massActionsToggle = false;

                    this.allSelected = false;

                    this.massActionType = null;
                },
            },
        });
    </script>
@endpush
