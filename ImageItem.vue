<!--Using lazyload custom directive -->

<template>
    <figure v-lazyload>
        <img :class="image_class"
              :data-url="source"
              :alt="alt"
        >
    </figure>
</template>

<script>
export default {
    name: "ImageItem",
    props: {
        source: {
            type: String,
            required: true
        },
        image_class: {
            type: String,
            required: false
        },
        alt:{
            type: String,
            required: true,
        }
    }
}
</script>

<style>

</style>