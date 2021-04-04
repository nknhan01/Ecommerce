<template>
    <div class="col-md-12">
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div v-if="!isLoading" v-countDown v-carousel v-bind:id="id" class="product_slider carousel_slider owl-carousel owl-theme nav_style3" data-loop="true" data-dots="false" data-nav="true" data-margin="30" data-responsive='{"0":{"items": "1"}, "650":{"items": "2"}, "1199":{"items": "2"}}'>
            <div class="item" v-for="item in data" :key="item.id" v-if="data.length" v-html="item"></div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                isLoading: true,
                data: []
            };
        },
        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
            id: {
                type: String,
                default: () => null,
            },
        },
        mounted() {
          this.getProducts();
        },
        methods: {
            getProducts() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then(res => {
                        this.data = res.data.data;
                        this.isLoading = false;
                    })
                    .catch(res => {
                        this.isLoading = false;
                        console.log(res);
                    });
            },
        },
        directives: {
            countDown: {
                inserted: function (el) {
                    var countDown = $(el).find('.countdown_time');
                    var endTime = countDown.data('time');
                    countDown.countdown(endTime, function (tm) {
                        countDown.html(tm.strftime('<div class="countdown_box"><div class="countdown-wrap"><span class="countdown days">%D </span><span class="cd_text">' + countDown.data('days-text') + '</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown hours">%H</span><span class="cd_text">' + countDown.data('hours-text') + '</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown minutes">%M</span><span class="cd_text">' + countDown.data('minutes-text') + '</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown seconds">%S</span><span class="cd_text">' + countDown.data('seconds-text') + '</span></div></div>'));
                    });
                },
            }
        }
    }
</script>
