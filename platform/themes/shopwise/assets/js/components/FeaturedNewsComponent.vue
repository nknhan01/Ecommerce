<template>
    <div class="row">
        <div class="half-circle-spinner" v-if="isLoading">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
        </div>
        <div class="col-lg-4 col-md-6" v-for="item in data" :key="item.id" v-if="!isLoading && data.length">
            <div class="blog_post blog_style2 box_shadow1">
                <div class="blog_img">
                    <a :href="item.url">
                        <img :src="item.image" :alt="item.name" />
                    </a>
                </div>
                <div class="blog_content bg-white">
                    <div class="blog_text">
                        <h5 class="blog_title"><a :href="item.url">{{ item.name }}</a></h5>
                        <ul class="list_none blog_meta">
                            <li><i class="ti-calendar"></i> {{ item.created_at }}</li>
                            <li><i class="eye"></i> {{ item.views }}</li>
                        </ul>
                        <p>{{ item.description }}</p>
                    </div>
                </div>
            </div>
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

        mounted() {
            this.getData();
        },

        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },

        methods: {
            getData() {
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
        }
    }
</script>
