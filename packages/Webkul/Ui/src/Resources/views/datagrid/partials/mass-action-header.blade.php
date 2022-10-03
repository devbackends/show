<thead v-if="massActionsToggle">
    @if (isset($results['massactions']))
        <tr class="mass-action" v-if="massActionsToggle" style="height: 65px;">
            <th colspan="100%">
                <div class="mass-action-wrapper" style="display: flex; flex-direction: row; align-items: flex-end; justify-content: flex-start;">

                    <div class="custom-control custom-checkbox" v-on:click="removeMassActions">
                        <input type="checkbox" class="custom-control-input" id="checkboxRemoveMassActions" checked>
                        <label class="custom-control-label" for="checkboxRemoveMassActions"></label>
                    </div>

                    <form method="POST" id="mass-action-form" style="display: inline-flex;" action="" :onsubmit="getOnSubmitMassAction">
                        @csrf()

                        <input type="hidden" id="indexes" name="indexes" v-model="dataIds">

                        <div class="control-group mx-2">
                            <select class="form-control" v-model="massActionType" @change="changeMassActionTarget" name="massaction-type" required>
                                <option v-for="(massAction, index) in massActions" :key="index" :value="massAction.type">@{{ massAction.label }}</option>
                            </select>
                        </div>

                        <div class="control-group mx-2" style="margin-left: 10px;" v-if="massActionType == 'update'">
                            <select class="form-control" v-model="massActionUpdateValue" name="update-options" required>
                                <option v-for="(massActionValue, id) in massActionValues" :value="massActionValue">@{{ id }}</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mx-2" style="margin-left: 10px;">
                            {{ __('ui::app.datagrid.submit') }}
                        </button>
                    </form>
                </div>
            </th>
        </tr>
    @endif
</thead>