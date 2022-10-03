<template>
	<span class="date__flatpickr">
		<slot>
			<input type="text" :name="name" class="form-control" :value="value" data-input>
		</slot>
	</span>
</template>

<script>
import Flatpickr from 'flatpickr';

export default {
    props: {
        name: String,
        value: String,
        minDate: {type: Date, format: 'Y-m-d'},
        maxDate: {type: Date, format: 'Y-m-d'},
    },

    data: () => ({
        datepicker: null,
    }),

    mounted() {
        const element = this.$el.getElementsByTagName('input')[0];
        const today = new Date();

        let minDate = this.minDate;
        if (!minDate || minDate.getTime() <= today.getTime()) {
            minDate = today;
        }
        this.datepicker = new Flatpickr(
            element, {
                altFormat: 'Y-m-d',
                dateFormat: 'Y-m-d',
                minDate: minDate,
                maxDate: this.maxDate,
                onChange: (selectedDates, dateStr, instance) => {
                    this.$emit('onChange', dateStr)
                },
            });
    }
};
</script>