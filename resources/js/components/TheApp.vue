<template>
<div class="mb-5 pb-3">
    <div class="jumbotron text-white" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.75) 20%, transparent), url(./images/bg.jpg);">
        <div class="container">
            <h1 class="display-3">Haukion Kala oy</h1>
            <p class="lead">Vuosi&shy;tapaa&shy;minen 2018</p>
            <p v-if="mostPopular">Suosituin ja lähimpänä: <b v-html="mostPopular.string"></b></p>
            <p v-if="suitsEverybody.length > 0">Kaikille sopii seuraavat viikot: <b v-html="suitsEverybody.join(', ')"></b></p>
        </div>
    </div>

    <div class="container">
        <div class="form-group">
            <label>Kuka olet?</label>
            <select class="form-control" v-model="form.person">
                <option :value="undefined">Valitse</option>
                <option v-for="(person, key) in people" :key="key" :value="key.toString()">
                    {{person}} {{ unansweredFolk.indexOf(person) > -1 ? '(Ei vastannut)' : undefined }}
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Valitse sopivat viikonloput</label>
            <div class="weeks card parent-card">
                <div class="card-body">
                    <week 
                        v-for="(item, number) in weeks" 
                        :key="number" 
                        :number="number" 
                        :data="item" 
                        :form-disabled="formDisabled"
                        :removed="form.weeks.indexOf(number) < 0 && weeks[number].indexOf(form.person) > -1"
                        :checked="form.weeks.indexOf(number) > -1"
                        :popular="mostPopular && mostPopular.value.week == number"
                        :people="people"
                        @click.native="handleInput(number)"></week>
                </div>
            </div>
        </div>

        <transition name="save-footer">
            <div v-if="!formDisabled" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9">
                <div class="container" style="padding: 1em; border-radius: 3px; background: #fff; box-shadow: 0 -2px 10px 0 rgba(0,0,0,.2)">
                    <button class="btn btn-block btn-primary" :disabled="formDisabled" @click.prevent="post">Tallenna</button>
                </div>
            </div>
        </transition>

        <div class="toaster">
            <transition-group name="toaster-list">
                <b-alert
                    v-for="item in messages"
                    :key="item.key"
                    :variant="item.type ? item.type : 'info'"
                    dismissible
                    @dismissed="dropMessage(item.key)"
                    :show="5">
                    {{ item.text && item.text }}
                </b-alert>
            </transition-group>
        </div>
    </div>
</div>
</template>

<script>
import Week from "./Week"
import axios from 'axios';
import {WeekObject} from '../week.js'

export default {
    data() {
        return {
            weeks: {},
            messages: [],
            form: {
                person: undefined,
                weeks: [],
            }
        }
    },
    props: {
        people: {},
        weeksImported: {},
    },
    computed: {
        formDisabled() {
            return this.people[this.form.person] === undefined
        },
        peopleCount() {
            return this.people.length
        },
        weeksByPopularity() {
            var math = []

            Object.keys(this.weeks).map((item, key) => {
                var weekObj = new WeekObject(item, 2018)
                if(weekObj.isOld(this.$root.now) === false) {
                    math.push({ week: item, popularity: this.weeks[item].length })
                }
            })

            math = math.sort(function (a, b) {
                return b.popularity - a.popularity || a.week - b.week
            });

            return math
        },
        mostPopular() {
            var most = this.weeksByPopularity[0]

            if(most.popularity <= 0) return undefined

            return {
                value: most,
                string: 'Viikko ' + most.week + ' (' + most.popularity + '/' + this.peopleCount + ')'
            }
        },
        suitsEverybody() {
            return Object.keys(this.weeks).filter((item, key) => {
                var weekObj = new WeekObject(item, 2018)
                return this.weeks[item].length == this.peopleCount && weekObj.isOld(this.$root.now) === false
            })
        },
        unansweredFolk() {
            return this.people.filter((item, key) => {
                return [...new Set(Object.values(this.weeks).flat())].indexOf(key.toString()) < 0
            })
        }
    },
    watch: {
        'form.person': function (newValue, oldValue) {
            if (newValue !== undefined) {
                this.form.weeks = Object.keys(this.weeks).filter(key => {
                    return this.weeks[key].indexOf(this.form.person) > -1
                })
            } else {
                this.form.weeks = []
            }
        }
    },
    methods: {
        pushMessage(text, type) {
            this.messages.push({ key: Math.random().toString(36).substring(7), text, type })
        },
        dropMessage(key) {
            var index = this.messages.findIndex(x => x.key == key)
            this.messages.splice(index, 1)
        },
        post() {
            var app = this

            axios.post('handle.php', this.form).then(resp => {
                app.pushMessage(resp.data.message, 'success')
                app.weeks = resp.data.weeks
            }).catch(err => {
                app.pushMessage(err.response.data.message, 'danger')
            })
        },
        handleInput(e) {
            if (this.formDisabled) return

            if (this.form.weeks.indexOf(e) < 0) this.form.weeks.push(e)
            else this.form.weeks.splice(this.form.weeks.indexOf(e), 1)
        }
    },
    created() {
        this.weeks = this.weeksImported
    },
    components: {
        Week,
    }
}
</script>

