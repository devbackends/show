<template>
    <div id="count-down-container">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <h2 v-text="title"></h2>
            </div>
            <div class="row justify-content-center align-items-center">
                <div id="clockdiv">
                    <div>
                        <span class="days" ></span>
                        <div class="smalltext">Days</div>
                    </div>
                    <div>
                        <span class="hours" ></span>
                        <div class="smalltext">Hours</div>
                    </div>
                    <div>
                        <span class="minutes" ></span>
                        <div class="smalltext">Minutes</div>
                    </div>
                    <div>
                        <span class="seconds" ></span>
                        <div class="smalltext">Seconds</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
#count-down-container{
    background: #FFD913;
    padding-top: 20px;
    padding-bottom: 20px;
}
#count-down-container h2 {
    font-size: 2rem;
}
#clockdiv{
    font-family: sans-serif;
    color: #000;
    display: inline-block;
    font-weight: 100;
    text-align: center;
    font-size: 2.5rem;
    font-family: poster-gothic-atf,sans-serif;
}

#clockdiv > div{
    padding: 10px;
    font-weight: bold;
    display: inline-block;
}

#clockdiv div > span{
    font-weight: bold;
    display: inline-block;
}

.smalltext{
    font-weight: bold;
    display: inline-block;
}
</style>
<script>
export default {
    props: ['date','title'],

    data: () => ({
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        total: 0

    }),

    mounted() {

        const deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
        this.initializeClock('clockdiv', deadline);
        },
    methods: {
        getTimeRemaining(endtime) {
            const total = Date.parse(this.date) - Date.parse(new Date());
            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return {
                total,
                days,
                hours,
                minutes,
                seconds
            };
        },
        initializeClock(id, endtime) {
            const clock = document.getElementById('clockdiv');
            const daysSpan = clock.querySelector('.days');
            const hoursSpan = clock.querySelector('.hours');
            const minutesSpan = clock.querySelector('.minutes');
            const secondsSpan = clock.querySelector('.seconds');
            this.updateClock(id,endtime);
            const timeinterval = setInterval(this.updateClock, 1000);
        },
        updateClock(id,endtime) {
            const t = this.getTimeRemaining(endtime);
            const clock = document.getElementById('clockdiv');
            clock.querySelector('.days').innerHTML = t.days;
            clock.querySelector('.hours').innerHTML = ('0' + t.hours).slice(-2);
            clock.querySelector('.minutes').innerHTML = ('0' + t.minutes).slice(-2);
            clock.querySelector('.seconds').innerHTML = ('0' + t.seconds).slice(-2);

            if (t.total <= 0) {
                clearInterval(timeinterval);
            }
        }
    }
}
</script>