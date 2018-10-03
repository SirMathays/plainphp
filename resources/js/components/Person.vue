<template>
    <div class="person" v-b-tooltip.hover :title="people[person]">
        <img :src="'./images/' + person + '.jpg'" :alt="person">
    </div>
</template>

<script>
export default {
    watch: {
        // 
    },
    props: {
        person: {},
        people: {}
    }
}
</script>
