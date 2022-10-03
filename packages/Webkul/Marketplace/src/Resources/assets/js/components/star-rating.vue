<template>
    <div>
    <div :class="`stars mr5 fs${size ? size : '16'} ${pushClass ? pushClass : ''}`">
        <input
            v-if="editable"
            type="hidden"
            :value="showFilled"
            name="rating"
             />

        <i
            :class="`fas fa-star  ${editable ? 'cursor-pointer' : ''}`"
            v-for="(rating, index) in parseInt((showFilled != 'undefined') ? showFilled : 3)"
            :key="`${index}${Math.random()}`"
            @click="updateRating(index + 1)">

        </i>

        <template v-if="!hideBlank">
            <i
                :class="`far fa-star ${editable ? 'cursor-pointer' : ''}`"
                v-for="(blankStar, index) in (5 - ((showFilled != 'undefined') ? showFilled : 3))"
                :key="`${index}${Math.random()}`"
                @click="updateRating(showFilled + index + 1)">

            </i>
        </template>
    </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: [
            'size',
            'ratings',
            'editable',
            'hideBlank',
            'pushClass',
        ],

        data: function () {
            return {
                showFilled: this.ratings,
            }
        },

        methods: {
            updateRating: function (index) {
                index = Math.abs(index);
                this.editable ? this.showFilled = index : '';
            }
        },
    }
</script>