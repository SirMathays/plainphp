<template>
    <div class="card card-child form-group" :class="{active: checked, disabled, removed, old}">
        <div class="card-body">
            <h1>
                <input type="checkbox" v-if="!disabled" :checked="checked"> 
                <span>Viikko {{ number }}</span>
                <small v-if="old" class="text-muted" v-b-tooltip.hover title="Vanhentunut">
                    <i class="fa fa-hourglass-end"></i>
                </small>
                <small v-if="data.length == people.length" class="text-success" v-b-tooltip.hover title="Sopii kaikille">
                    <i class="fa fa-check"></i>
                </small>
                <small v-if="popular" style="color:gold" v-b-tooltip.hover title="Suosituin & lähimpänä">
                    <i class="fa fa-medal"></i>
                </small>
            </h1>
            <h2><b>Viikonloppu:</b> {{ weekend[0].getDate() + '.'  + (weekend[0].getMonth()+1) }} - {{ weekend[1].getDate() + '.'  + (weekend[1].getMonth()+1) }}</h2>

            <div class="people-container">
                <person v-for="person in data" :person="person" :people="people" :key="person"></person>
                <div class="person">
                    <span>{{ data.length }}/{{ people.length }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Person from './Person'
import {WeekObject} from '../week.js'

export default {
    computed: {
        weekend() {
            var year = 2018
            var dto = new Date(year, 0, 1 + (parseInt(this.number) - 1) * 7);

            var start = new Date(dto.setDate(dto.getDate() + 4))
            var end = new Date(dto.setDate(dto.getDate() + 2))

            return [start, end]
        },
        disabled() {
            return this.formDisabled || this.old
        },
        old() {
            return this.weekend[0] < this.$root.now
        }
    },
    props: {
        number: {},
        data: {},
        checked: {},
        removed: {},
        formDisabled: {},
        popular: {},
        people: {}
    },
    components: {
        Person
    }
}
</script>