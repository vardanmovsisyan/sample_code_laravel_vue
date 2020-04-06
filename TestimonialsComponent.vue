<!--
    Just a sample vue component
    Translations are done using the following https://www.npmjs.com/package/vue-i18next 
    i18next package on the frontend side
    Laravel translation files are imported in JS as JSON using the following package:
    https://www.npmjs.com/package/@kirschbaum-development/laravel-translations-loader
    In this project Laravel Mix is used
-->


<template>
    <main class="testimonials_main">
        <h2 class="title">{{ $t("site:testimonials") }}</h2>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Carousel indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="indicator active"></li>
                <li data-target="#myCarousel" data-slide-to="1" class="indicator"></li>
                <li data-target="#myCarousel" data-slide-to="2" class="indicator"></li>
            </ol>
            <!-- Wrapper for carousel items -->
            <div class="carousel-inner">
                <div :class="t==0?`item carousel-item active`:`item carousel-item`" v-for="(testimonial,t) in testimonials" :key="t">
                    <div class="img-box"><img :src="testimonial.image" :alt="testimonial.title"></div>
                    <div class="testimonial" v-html="testimonial.description"></div>
                    <div class="overview">
                        {{testimonial.title}}
                    </div>
                    <div class="star-rating">
                        <ul class="list-inline">
                            <li class="list-inline-item"><i class="fa fa-star"></i></li>
                            <li class="list-inline-item"><i class="fa fa-star"></i></li>
                            <li class="list-inline-item"><i class="fa fa-star"></i></li>
                            <li class="list-inline-item"><i class="fa fa-star"></i></li>
                            <li class="list-inline-item"><i class="fa fa-star"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Carousel controls -->
            <a class="carousel-control left carousel-control-prev" href="#myCarousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="carousel-control right carousel-control-next" href="#myCarousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </main>
</template>

<script>
    export default {
        data() {
            return {
                testimonials:[]
            }
        },
        mounted() {
            axios.get('/testimonials-data')
            .then((response)=>{
                this.testimonials=response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
    }
</script>

<style lang="sass" scoped>
    @import '../../sass/variables.scss';

    .title {
        color: $textColor;
        text-align: center;
        text-transform: uppercase;
        font-family: "Roboto", sans-serif;
        font-weight: bold;
        position: relative;
        margin: 25px 0 50px;
        &::after {
            content: "";
            width: 100px;
            position: absolute;
            margin: 0 auto;
            height: 3px;
            background: #007b5e;
            left: 0;
            right: 0;
            bottom: -10px;
        }
    }
    .carousel {
        width: 650px;
        margin: 0 auto;
        padding-bottom: 50px;
    }
    .item {
        font-size: 14px;
        text-align: center;
        overflow: hidden;
        min-height: 340px;
    }
    .img-box {
        width: 145px;
        height: 145px;
        margin: 0 auto;
        border-radius: 50%;
    }
    .indicator{
        width: 11px;
        height: 11px;
        border:none;
        margin: 1px 5px;
        border-radius: 50%;
        &.active {
            background: $mediumdGrey;
        }
    }
    .testimonials_main{
        padding-top: 50px;
        padding-bottom: 50px;
    }
</style>