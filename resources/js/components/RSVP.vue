<template>
    <div class="w-full">
        <div class="bg-teal-400 border border-teal-500 text-teal-800 px-4 py-3 rounded mb-8" role="alert" v-if="success">
            <strong class="font-bold">Thanks!</strong>
            <span class="block sm:inline">We've received your RSVP.</span>
        </div>

        <div v-for="(familyMember, index) in familyMembers" :key="familyMember.id">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Guest Name
                    </label>
                    <span class="block tracking-wide text-gray-700 mb-2">
                        {{ familyMember.first_name }} {{ familyMember.last_name }}
                    </span>
                </div>
                <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0" v-if="!family.evening">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Are they attending?
                    </label>
                    <div class="inline-flex rounded">
                        <button type="button" class="bg-gray-400 font-bold py-2 px-4 rounded-l" :class="{ 'bg-green-500 hover:bg-green-600 text-white': familyMember.attending == 1, 'bg-gray-400 hover:bg-gray-500 text-gray-800': familyMember.attending == 0 }" @click="familyMember.attending = true">
                            Yes
                        </button>
                        <button type="button" class="bg-gray-400 font-bold py-2 px-4 rounded-r" :class="{ 'bg-red-400 hover:bg-red-600 text-white' : familyMember.attending == 0, 'bg-gray-400 hover:bg-gray-500 text-gray-800' : familyMember.attending == 1 }" @click="familyMember.attending = false">
                            No
                        </button>
                    </div>
                </div>
                <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0" v-if="family.evening">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Will you be there?
                    </label>
                    <div class="inline-flex">
                        <button type="button" class="font-bold py-2 px-4 rounded-l" :class="{ 'bg-green-500 hover:bg-green-600 text-white': familyMember.attending_evening, 'bg-gray-400 hover:bg-gray-500 text-gray-800': !familyMember.attending_evening }" @click="familyMember.attending_evening = true">
                            Yes
                        </button>
                        <button type="button" class="font-bold py-2 px-4 rounded-r" :class="{ 'bg-red-400 hover:bg-red-600 text-white' : !familyMember.attending_evening, 'bg-gray-400 hover:bg-gray-500 text-gray-800' : familyMember.attending_evening }" @click="familyMember.attending_evening = false">
                            No
                        </button>
                    </div>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0" v-if="!family.evening">
                    <div v-if="!familyMember.child" class="space-y-4">
                        <div>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Starter
                            </label>

                            <div class="inline-block relative w-full">
                                <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline disabled:bg-gray-100 disabled:cursor-not-allowed" v-model="familyMember.starter_food_choice" :required="familyMember.attending" :disabled="!familyMember.attending">
                                    <option :selected="familyMember.starter_food_choice === ''"></option>
                                    <option value="1" :selected="familyMember.starter_food_choice == '1'">Bruschetta</option>
                                    <option value="2" :selected="familyMember.starter_food_choice == '2'">Ham Hock Terrine</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>

                            <ErrorMessage :errors="getErrors(index, 'starter_food_choice')" />
                        </div>

                        <div>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Mains
                            </label>

                            <div class="inline-block relative w-full">
                                <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline disabled:bg-gray-100 disabled:cursor-not-allowed" v-model="familyMember.main_food_choice" :required="familyMember.attending" :disabled="!familyMember.attending">
                                    <option :selected="familyMember.main_food_choice === ''"></option>
                                    <option value="1" :selected="familyMember.main_food_choice == '1'">Slow Cooked Belly Pork, Apple Cider Gravy, Dauphinois Potatoes and Seasonal Veg</option>
                                    <option value="2" :selected="familyMember.main_food_choice == '2'">Slow Braised Shin Beef, Carrots and Greens, Shallots, Creamed Mashed Potatoes</option>
                                    <option value="3" :selected="familyMember.main_food_choice == '3'">Alternative (Please Provide Dietary Requirements)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>

                            <ErrorMessage :errors="getErrors(index, 'main_food_choice')" />
                        </div>

                        <div>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Dessert
                            </label>

                            <div class="inline-block relative w-full">
                                <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline disabled:bg-gray-100 disabled:cursor-not-allowed" v-model="familyMember.dessert_food_choice" :required="familyMember.attending" :disabled="!familyMember.attending">
                                    <option :selected="familyMember.dessert_food_choice === ''"></option>
                                    <option value="1" :selected="familyMember.dessert_food_choice == '1'">Chocolate Brownie with Ice Cream</option>
                                    <option value="2" :selected="familyMember.dessert_food_choice == '2'">Sticky Toffee Pudding</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>

                            <ErrorMessage :errors="getErrors(index, 'dessert_food_choice')" />
                        </div>
                        <span v-if="familyMember.food_choice_error" class="mt-3 text-red-600 font-bold">Please select an option.</span>
                    </div>
                    <template v-else>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Menu Choice
                        </label>

                        <em class="italic">Garlic Bread, Fish Fingers and Brownie.</em>
                    </template>

                    <div class="mt-4">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Do You Have Dietary requirements?
                        </label>

                        <textarea class="block appearance-none w-full bg-white border border-gray-400 hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline disabled:bg-gray-100 disabled:cursor-not-allowed" type="text" placeholder="Dietary Requirements" v-model="familyMember.dietary_requirements" :disabled="sending || !familyMember.attending"></textarea>

                        <ErrorMessage :errors="getErrors(index, 'dietary_requirements')" />
                    </div>
                </div>
            </div>

            <hr class="border-t border-gray-400 my-4" v-if="index < (family.members.length - 1)">
        </div>

        <hr class="border-t border-gray-400 my-4">

        <button class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded" @click="updateRsvp">Send RSVP</button>
    </div>
</template>

<script>
    import ErrorMessage from "./ErrorMessage.vue";

    const axios = require('axios');

    export default {
        components: {
            ErrorMessage,
        },
        props: {
            family: Object
        },
        data () {
            return {
                familyMembers: [],
                sending: false, // Is an RSVP sending?
                success: false, // Did we have a success response?
                error: false, // Did we have an error response?
                errors: [],
            }
        },
        mounted () {
            this.familyMembers = this.sortFamily(this.family);
        },
        methods: {
            getErrors(familyMemberId, key) {
                const errorKey = `members.${familyMemberId}.${key}`

                return this.errors[errorKey] || []
            },
            sortFamily(family) {
                return family.members.sort((a, b) => a.id - b.id);
            },
            updateRsvp() {
                this.success = false;
                this.error = false;
                this.sending = true;

                // if (!this.checkForm()) {
                //     this.error = true;

                //     window.scrollTo(0, 0)

                //     return false;
                // }

                axios.post(`/rsvp/${this.family.id}`, this.family).then((response) => {
                    this.errors = []
                    this.success = response.data.success;
                    this.error = !this.success

                    window.scrollTo(0, 0)

                    this.sending = false
                }).catch((error) => {
                    this.errors = error.response.data.errors
                    this.error = true
                    this.sending = false

                    window.scrollTo(0, 0)
                });
            },
            toggleAttending(index) {
                let members = this.family.members
                members[index].attending = !members[index].attending

                this.familyMembers.members = members
            },
            checkForm () {
                let errorCount = 0;

                this.familyMembers.filter((member) => {
                    if (this.family.evening === false) {
                        return member.attending;
                    }
                }).forEach((item, index) => {
                    if (!item.starter_food_choice || item.starter_food_choice == 0) {
                        item.food_choice_error = true
                        errorCount++
                    } else {
                        item.food_choice_error = false
                    }
                })

                return errorCount > 0 ? false : true;
            }
        }
    }
</script>
