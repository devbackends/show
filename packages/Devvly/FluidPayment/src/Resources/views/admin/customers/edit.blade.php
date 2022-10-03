{{--
@if($seller && $fluidCustomer = \Devvly\FluidPayment\Models\FluidCustomer::query()->firstWhere('seller_id', '=', $seller->id))
    <accordian title="2A Commerce Bank Account" :active="true">
        <div slot="body">
            <div class="control-group boolean">
                <label for="bank_approved">
                    Bank Account Approved
                </label>
                <label class="switch">
                    <input type="checkbox" class="control" id="bank_approved" name="bank_approved" data-vv-as="&quot;bank_approved&quot;" {{ $fluidCustomer->is_approved ? 'checked' : ''}} value="1">
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </accordian>
@endif--}}
