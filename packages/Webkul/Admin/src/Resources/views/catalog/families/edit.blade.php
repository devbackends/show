@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.families.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.families.update', $attributeFamily->id) }}"
              id="edit-families-form">

            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link"
                           onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.catalog.families.edit-title') }}
                    </h3>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.catalog.families.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.before', ['attributeFamily' => $attributeFamily]) !!}

                    <accordian :title="'{{ __('admin::app.catalog.families.general') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.controls.before', ['attributeFamily' => $attributeFamily]) !!}

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <input type="text" v-validate="'required'" name="code" class="control" id="code"
                                       value="{{ old('code') ?: $attributeFamily->code }}" disabled="disabled"
                                       data-vv-as="&quot;{{ __('admin::app.catalog.families.code') }}&quot;" v-code/>
                                <input type="hidden" name="code" value="{{ $attributeFamily->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name"
                                       value="{{ old('name') ?: $attributeFamily->name }}"
                                       data-vv-as="&quot;{{ __('admin::app.catalog.families.name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.controls.after', ['attributeFamily' => $attributeFamily]) !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.general.after', ['attributeFamily' => $attributeFamily]) !!}


                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.before', ['attributeFamily' => $attributeFamily]) !!}

                    <accordian :title="'{{ __('admin::app.catalog.families.groups') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.controls.before', ['attributeFamily' => $attributeFamily]) !!}

                            <button type="button" style="margin-bottom : 20px" class="btn btn-primary"
                                    @click="showModal('addGroup')">
                                {{ __('admin::app.catalog.families.add-group-title') }}
                            </button>

                            <group-list></group-list>

                            {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.controls.before', ['attributeFamily' => $attributeFamily]) !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.family.edit_form_accordian.groups.after', ['attributeFamily' => $attributeFamily]) !!}
                </div>
            </div>

        </form>
    </div>

    <modal id="addGroup" :is-open="modalIds.addGroup">
        <h3 slot="header">{{ __('admin::app.catalog.families.add-group-title') }}</h3>

        <div slot="body">
            <group-form></group-form>
        </div>
    </modal>

    <!-- Modal -->

    <settings></settings>

    <!-- Modal -->

    <group-position></group-position>

@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.accordian-content .table table tbody').each(function (key, el) {
                $(el).sortable({
                    items: 'tr',
                    update: function (event, ui) {
                        eventBus.$emit('updatePositions', {
                            el: ui.item[0],
                            new_position: ui.item.index() + 1,
                        })
                    }
                }).disableSelection();
            });
        })
    </script>
    <script>
/*        function sendFormBeforeUnload() {
            const formData = $("#edit-families-form").serialize();

            $.ajax({
                type: 'post',
                url: "<?php route('admin.catalog.families.update', $attributeFamily->id) ?>",
                data: formData
            });
        }

        $(window).bind('beforeunload', function () {
            sendFormBeforeUnload();

            return undefined;
        });
*/
    </script>


    <script type="text/x-template" id="settings-template">

        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Settings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="settings-form"  method="POST" data-vv-scope="settings-form" @submit.prevent="saveSettingForm('settings-form',$event)">
                            @csrf
                            <input type="hidden"  id="attribute_id" name="attribute_id">
                            <input type="hidden"  id="attribute_family_id" name="attribute_family_id" value="{{$attributeFamily->id}}">
                            <input type="hidden"  id="old_group" name="old_group" >
                            <div class="control-group">
                                <label>Group</label>
                                <select class="control" id="new_group" name="group" required v-model="group_id">
                                    <option v-for="(group,index) in groups" :key="index" :value="group.id" v-text="group.name"></option>
                                </select>
                            </div>
                            <div class="control-group">
                                <label>column width</label>
                                <select class="control" name="width" id="width" required v-model="width">
                                    <option value="">Select</option>
                                    <option value="1">1/12</option>
                                    <option value="2">2/12</option>
                                    <option value="3">3/12</option>
                                    <option value="4">4/12</option>
                                    <option value="5">5/12</option>
                                    <option value="6">6/12</option>
                                    <option value="7">7/12</option>
                                    <option value="8">8/12</option>
                                    <option value="9">9/12</option>
                                    <option value="10">10/12</option>
                                    <option value="11">11/12</option>
                                    <option value="12">12/12</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </script>

    <script type="text/x-template" id="group-position-template">
          <div>
              <div class="modal fade" id="groupPositionModal" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="groupPositionModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">

                          <div class="modal-header">
                              <h5 class="modal-title" id="groupPositionModalLabel">Change Group Position</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <form   action="{{ route('admin.catalog.attributes.change-group-position') }}" method="post" @submit.prevent="changeGroupPosition">
                                  @csrf
                                  <input type="hidden" id="group" name="group" >
                                  <input type="number" id="position" name="position" required>
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary">Save</button>
                              </form>
                          </div>


                      </div>
                  </div>
              </div>
          </div>
    </script>


    <script type="text/x-template" id="group-form-template">
        <form method="POST" action="{{ route('admin.catalog.families.store') }}" data-vv-scope="add-group-form"
              @submit.prevent="addGroup('add-group-form')">

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <div class="control-group" :class="[errors.has('add-group-form.groupName') ? 'has-error' : '']">
                        <label for="groupName" class="required">{{ __('admin::app.catalog.families.name') }}</label>
                        <input type="text" v-validate="'required'" v-model="group.groupName" class="control"
                               id="groupName" name="groupName"
                               data-vv-as="&quot;{{ __('admin::app.catalog.families.name') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('add-group-form.groupName')">@{{ errors.first('add-group-form.groupName') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('add-group-form.position') ? 'has-error' : '']">
                        <label for="position" class="required">{{ __('admin::app.catalog.families.position') }}</label>
                        <input type="text" v-validate="'required|numeric'" v-model="group.position" class="control"
                               id="position" name="position"
                               data-vv-as="&quot;{{ __('admin::app.catalog.families.position') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('add-group-form.position')">@{{ errors.first('add-group-form.position') }}</span>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.catalog.families.add-group-title') }}
                    </button>

                </div>
            </div>

        </form>
    </script>

    <script type="text/x-template" id="group-list-template">
        <div style="margin-top: 20px">
            <group-item v-for='(group, index) in groups' :group="group" :custom_attributes="custom_attributes"
                        :key="index" :index="index" @onRemoveGroup="removeGroup($event)"
                        @onAttributeAdd="addAttributes(index, $event)"
                        @onAttributeRemove="removeAttribute(index, $event)"></group-item>
        </div>
    </script>

    <script type="text/x-template" id="group-item-template">
        <accordian :title="group.groupName" :active="true">
            <div slot="header">
                <i class="icon expand-icon left"></i>
                <h1>@{{ group.name ? group.name : group.groupName }}</h1>
                <i class="icon pencil-lg-icon mr-2 ml-2"  @click="changeGroupPosition(group.id)"/>
                <i class="icon trash-icon  mr-2 ml-2" @click="removeGroup()" ></i>
            </div>
            <div slot="body">
                <input type="hidden" :name="[groupInputName + '[name]']"
                       :value="group.name ? group.name : group.groupName"/>
                <input type="hidden" :name="[groupInputName + '[position]']" :value="group.position"/>
                <button v-on:click="onShowPositions" class="border-0 mb-3"> Show positions</button>
                <div class="table" v-if="group.custom_attributes.length" style="margin-bottom: 20px;">
                    <table>
                        <thead>
                        <tr>
                            <th>{{ __('admin::app.catalog.families.attribute-code') }}</th>
                            <th>{{ __('admin::app.catalog.families.name') }}</th>
                            <th>{{ __('admin::app.catalog.families.type') }}</th>
                            <th>width</th>
                            <th v-bind:class="showPosition ? '' : 'd-none'">Position</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for='(attribute, index) in group.custom_attributes'>
                            <td>
                                <div style="display: -webkit-flex;display: flex;">
                                    <div>
                                        <svg style="width: 71%" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11 18c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm-2-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 4c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                    </div>
                                    <div>
                                        <input type="hidden" :name="[groupInputName + '[custom_attributes][][id]']"
                                               :value="attribute.id"/>
                                        <input v-if="typeof attribute.pivot != 'undefined'" type="hidden" :name="[groupInputName + '[width][]']"
                                               :value="JSON.stringify({'attribute_id':attribute.id,'attribute_group_id':attribute.pivot.attribute_group_id,'width':attribute.pivot.width})"/>
                                        @{{ attribute.code }}
                                    </div>
                                </div>
                            </td>
                            <td>@{{ attribute.admin_name }}</td>
                            <td>@{{ attribute.type }}</td>
                            <td><span v-if="typeof attribute.pivot != 'undefined'">@{{ attribute.pivot.width }}</span> </td>
                            <td v-bind:data-group-id="group.id" class="position-element"
                                v-bind:class="showPosition ? '' : 'd-none'">@{{
                                attribute.position }}
                            </td>
                            <td class="actions">
                                <i class="icon trash-icon" @click="removeAttribute(attribute)"
                                   v-if="attribute.is_user_defined || attribute.removable"></i>
                            </td>
                            <td class="actions">
                                <i class="icon pencil-lg-icon" @click="changeAttributeGroup(attribute)"></i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-primary dropdown-toggle">
                    {{ __('admin::app.catalog.families.add-attribute-title') }}
                </button>

                <div class="dropdown-list" style="width: 240px">
                    <div class="search-box">
                        <input type="text" class="control" placeholder="{{ __('admin::app.catalog.families.search') }}">
                    </div>

                    <div class="dropdown-container">
                        <ul>
                            <li v-for='(attribute, index) in custom_attributes' :data-id="attribute.id">
                                <span class="checkbox">
                                    <input type="checkbox" :id="attribute.id" :value="attribute.id"/>
                                    <label class="checkbox-view" :for="attribute.id"></label>
                                    @{{ attribute.admin_name }}
                                </span>
                            </li>
                        </ul>

                        <button type="button" class="btn btn-primary" @click="addAttributes($event)">
                            {{ __('admin::app.catalog.families.add-attribute-title') }}
                        </button>
                    </div>
                </div>
            </div>
        </accordian>


    </script>

    <script>

        function updatePosition(data) {
            $.ajax({
                type: 'put',
                url: "<?= route('admin.catalog.families.updateAttrPosition', $attributeFamily->id) ?>",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    data: JSON.stringify(data),
                }
            });
        }

        let groups = @json($attributeFamily->attribute_groups);
        groups = groups.sort((a, b) => a.position - b.position)

        let position = 1, changes = false, positionsToUpdate = [];
        for (let groupKey in groups) {
            groups[groupKey].custom_attributes = groups[groupKey].custom_attributes.sort((a, b) => parseInt(a.position) - parseInt(b.position));
            for (let attrKey in groups[groupKey].custom_attributes) {
                if (groups[groupKey].custom_attributes[attrKey].position !== position) {
                    positionsToUpdate.push({
                        'id': groups[groupKey].custom_attributes[attrKey].id,
                        'position': position
                    });
                    groups[groupKey].custom_attributes[attrKey].position = position;
                    changes = true;
                }
                position++;
            }
        }

        if (changes) {
            updatePosition(positionsToUpdate)
        }

        var custom_attributes = @json($custom_attributes);



        Vue.component('settings', {

            data: function () {
                return {
                    groups:groups,
                    group_id:null,
                    width:null,

                }
            },

            template: '#settings-template',
            mounted() {
                this.$root.$on('changeSettings', (group_id,width) => {
                    this.group_id=group_id;
                    this.width=width;
                })
            },
            methods: {
                saveSettingForm(formScope,e) {
                    var this_this = this;
                    this.$validator.validateAll(formScope).then(function (result) {
                        if (result) {
                            e.preventDefault();
                            const old_group=$('#old_group').val();
                            console.log('old_group'+old_group)
                            const new_group=$('#new_group').val();
                            const attribute_id=$('#attribute_id').val();
                            const attribute_family_id=$('#attribute_family_id').val();
                            const width=$('#width').val();


                            let indexOfOldGroup = this_this.getObjectIndexByValue(this_this.groups,'id',old_group);
                            let indexOfOldAttribute = this_this.getObjectIndexByValue(this_this.groups[indexOfOldGroup].custom_attributes,'id',attribute_id);

                            let indexOfNewGroup = this_this.getObjectIndexByValue(this_this.groups,'id',new_group);
                            let attributeObject= this_this.groups[indexOfOldGroup].custom_attributes[indexOfOldAttribute];
                            attributeObject.pivot.attribute_group_id=new_group;
                            attributeObject.pivot.width=width;
                           if(old_group!=new_group){
                               this_this.groups[indexOfNewGroup].custom_attributes.push(attributeObject);
                               this_this.groups[indexOfOldGroup].custom_attributes.splice(indexOfOldAttribute,1);
                           }
                            $('#staticBackdrop').modal('hide');
                        }
                    });

                },
                getObjectIndexByValue(array,property,value){
                    for(let i=0;i < array.length; i++){
                        if(array[i][property]==value){
                            return i;
                        }
                    }
                    return -1;
                }
            }
        });

        Vue.component('group-position', {

            data: function () {
                return {
                    groups:groups
                }
            },

            template: '#group-position-template',
            mounted() {

            },
            methods: {
                changeGroupPosition: function(e){
                    e.preventDefault();
                    const group_id=$('#group').val();
                    const position=$('#position').val();
                    this.groups.forEach(function (group) {
                     if(group.id==group_id){
                         group.position=position
                         $('#groupPositionModal').modal('hide');
                     }
                    });

                }
            }
        });

        Vue.component('group-form', {

            data: function () {
                return {
                    group: {
                        'groupName': '',
                        'position': '',
                        'custom_attributes': []
                    }
                }
            },

            template: '#group-form-template',

            methods: {
                addGroup: function (formScope) {
                    var this_this = this;

                    this.$validator.validateAll(formScope).then(function (result) {
                        if (result) {

                            var filteredGroups = groups.filter(function (group) {
                                return this_this.group.groupName.trim() === (group.name ? group.name.trim() : group.groupName.trim())
                            })

                            if (filteredGroups.length) {
                                const field = this.$validator.fields.find({name: 'groupName', scope: 'add-group-form'});

                                if (field) {
                                    this.$validator.errors.add({
                                        id: field.id,
                                        field: 'groupName',
                                        msg: "{{ __('admin::app.catalog.families.group-exist-error') }}",
                                        scope: 'add-group-form',
                                    });
                                }
                            } else {
                                groups.push(this_this.group);

                                groups = this_this.sortGroups();

                                this.group = {
                                    'groupName': '',
                                    'position': '',
                                    'is_user_defined': 1,
                                    'custom_attributes': []
                                };

                                this_this.$parent.closeModal();

                                window.flashMessages = [{
                                    'type': 'alert-success',
                                    'message': "{{__('admin::app.catalog.families.group-created') }}"
                                }];
                                this_this.$root.addFlashMessages();
                            }
                        }
                    });
                },

                sortGroups: function () {
                    return groups.sort(function (a, b) {
                        return a.position - b.position;
                    });
                },
                sendFormAjax: function (e) {
                    const x = 'http://bigguns.localhost/admin/catalog/families/edit/34';
                    const mydata = $("#edit-families-form").serialize();

                    $.ajax({
                        type: 'post',
                        url: x,
                        data: mydata,
                        success: function (response) {
                            alert('success!!!')
                        }
                    });
                }
            }
        });

        Vue.component('group-list', {

            template: '#group-list-template',

            data: function () {
                return {
                    groups: groups,
                    custom_attributes: custom_attributes
                }
            },

            created: function () {
                this.groups.forEach(function (group) {
                    group.custom_attributes.forEach(function (attribute) {
                        var attribute = this.custom_attributes.filter(function (attributeTemp) {
                            return attributeTemp.id == attribute.id;
                        });

                        if (attribute.length) {
                            let index = this.custom_attributes.indexOf(attribute[0])

                            this.custom_attributes.splice(index, 1)
                        }

                    });
                });
            },

            methods: {
                removeGroup: function (group) {
                    if(confirm('Are you sure you want to delete Group?')){
                        group.custom_attributes.forEach(function (attribute) {
                            this.custom_attributes.push(attribute);
                        })

                        this.custom_attributes = this.sortAttributes();

                        let index = groups.indexOf(group)

                        groups.splice(index, 1)
                    }
                },

                addAttributes: function (groupIndex, attributeIds) {
                    attributeIds.forEach(attributeId => {
                        var attribute = this.custom_attributes.filter(function (attribute) {
                            return attribute.id == attributeId;
                        });

                        attribute[0].removable = true;

                        this.groups[groupIndex].custom_attributes.push(attribute[0]);

                        let index = this.custom_attributes.indexOf(attribute[0])

                        this.custom_attributes.splice(index, 1)
                    })
                },

                removeAttribute: function (groupIndex, attribute) {
                    let index = this.groups[groupIndex].custom_attributes.indexOf(attribute)

                    this.groups[groupIndex].custom_attributes.splice(index, 1)

                    this.custom_attributes.push(attribute);

                    this.custom_attributes = this.sortAttributes();
                },

                sortAttributes: function () {
                    return this.custom_attributes.sort(function (a, b) {
                        return a.id - b.id;
                    });
                }
            }
        })

        Vue.component('group-item', {
            props: ['index', 'group', 'custom_attributes'],

            template: "#group-item-template",
            data() {
                return {
                    showPosition: false,
                    possiblePositions: [],
                }
            },
            computed: {
                groupInputName: function () {
                    if (this.group.id)
                        return "attribute_groups[" + this.group.id + "]";

                    return "attribute_groups[group_" + this.index + "]";
                }
            },
            mounted() {

                // Set possible range of positions for this group of attributes (for example 1-8, 9-13, 14-20)
                for (let key in this.group.custom_attributes) {
                    this.possiblePositions.push(this.group.custom_attributes[key].position)
                }

                // Register event 'Update position'
                eventBus.$on('updatePositions', payload => {

                    // Group filter
                    if (parseInt(payload.el.querySelector('.position-element').getAttribute("data-group-id")) !== this.group.id) return null;

                    let positionsToUpdate = [];

                    // Set new position
                    const new_position = this.possiblePositions[payload.new_position - 1]

                    // Get current position of the element
                    const currentPosition = parseInt(payload.el.querySelector('.position-element').innerHTML);

                    this.group.custom_attributes.forEach(el => {

                        // Filter items that don't need to be changed
                        if (new_position < currentPosition) {
                            if (el.position > currentPosition || el.position < new_position) return true;
                        } else {
                            if (el.position < currentPosition || el.position > new_position) return true;
                        }

                        // Change position of moved item
                        if (el.position === currentPosition) {
                            positionsToUpdate.push({
                                id: el.id,
                                position: new_position,
                            });
                            return el.position = new_position;
                        }

                        // Change position of affected items
                        if (new_position < currentPosition) {
                            positionsToUpdate.push({
                                id: el.id,
                                position: el.position + 1,
                            });
                            return el.position += 1;
                        } else {
                            positionsToUpdate.push({
                                id: el.id,
                                position: el.position - 1,
                            });
                            return el.position -= 1;
                        }

                    });

                    // Send request to server to update attrs positions
                    updatePosition(positionsToUpdate);

                })
            },
            methods: {
                removeGroup: function () {
                    this.$emit('onRemoveGroup', this.group)
                },
                onShowPositions: function (e) {
                    e.preventDefault();
                    this.showPosition = !this.showPosition;
                },
                addAttributes: function (e) {
                    var attributeIds = [];

                    $(e.target).prev().find('li input').each(function () {
                        var attributeId = $(this).val();

                        if ($(this).is(':checked')) {
                            attributeIds.push(attributeId);

                            $(this).prop('checked', false);
                        }
                    });

                    $('body').trigger('click')

                    this.$emit('onAttributeAdd', attributeIds)
                },

                removeAttribute: function (attribute) {
                    var confirmDelete = confirm('Are you sure to do this?')

                    if (confirmDelete) {
                        this.$emit('onAttributeRemove', attribute)
                    }
                },
                changeAttributeGroup: function(attribute){
                if(typeof attribute.pivot != 'undefined'){
                    this.$root.$emit('changeSettings',attribute.pivot.attribute_group_id,attribute.pivot.width);
                    $('#staticBackdrop').modal('show');
                    $('#attribute_id').val(attribute.id);
                    $('#old_group').val(attribute.pivot.attribute_group_id);
                }else{
                    window.flashMessages = [{
                        'type': 'alert-error',
                        'message': "You need to save the attribute before modifying it"
                    }];
                    this.$root.addFlashMessages();
                }

                },
                changeGroupPosition: function(group_id){
                    console.log(group_id);
                    $('#groupPositionModal').modal('show');
                    $('#group').val(group_id);
                }
            }
        });
    </script>
@endpush