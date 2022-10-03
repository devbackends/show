<div class="row store-settings__payment">
    <div class="col">
        <p class="font-paragraph-big">Inventory Webhook</p>
        <div class="from-group control-group" :class="[errors.has('webhooks') ? 'has-error' : '']">
            <label for="webhooks" class="required">WooCommerce</label>
            <input type="text" value="{{$seller->webhooks}}"  class="control form-control" id="webhooks" name="webhooks" data-vv-as="&quot; Webhooks &quot;" />
            <span class="control-error" v-if="errors.has('webhooks')">@{{ errors.first('webhooks') }}</span>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        $(document).ready(() => {
        });
    </script>
@endpush