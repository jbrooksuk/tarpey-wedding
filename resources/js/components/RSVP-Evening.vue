<template>
    <div class="w-full">
        <div class="bg-red-100 border border-red-400 text-red-600 px-4 py-3 rounded mb-8" role="alert" v-if="error">
            <strong class="font-bold">Wowzers!</strong>
            <span class="block sm:inline">Something went wrong, please try again.</span>
        </div>

        <div class="bg-green-100 border border-green-400 text-green-600 px-4 py-3 rounded mb-8" role="alert" v-if="success">
            <strong class="font-bold">Awesome!</strong>
            <span class="block sm:inline">We've received your RSVP. We'll see you at the party 🎉</span>
        </div>

        <div v-for="(familyMember, index) in family">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Guest's Full Name
                    </label>
                    <input class="block bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey w-full" type="text" v-model="familyMember.name" :disabled="sending" placeholder="Luigi Fiat">
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Dietary requirements / Comments
                    </label>
                    <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey w-full" type="text" placeholder="Dietary Requirements" v-model="familyMember.dietary_requirements" :disabled="sending"></textarea>
                </div>
                <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Attending?
                    </label>
                    <input class="block bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey" type="checkbox" v-model="familyMember.attending" value="1" :disabled="sending">
                </div>
            </div>

            <hr class="border-t border-gray-400 my-4" v-if="index < (family.length - 1)">
        </div>

        <hr class="border-t border-gray-400 my-4">

        <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" @click="updateRsvp">Send RSVP</button>
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" @click="addFamilyMember">Add Guest</button>
        <button v-if="family.length > 1" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" @click="removeFamilyMember">Remove Guest</button>
    </div>
</template>

<script>
    const axios = require('axios');

    export default {
        props: ['familyId'],
        data () {
            return {
                family: [{
                    name: '',
                    dietary_requirements: '',
                    attending: true,
                }],
                sending: false, // Is an RSVP sending?
                success: false, // Did we have a success response?
                error: false, // Did we have an error response?
            }
        },
        methods: {
            addFamilyMember() {
                this.family.push({
                    name: '',
                    dietary_requirements: '',
                    attending: true,
                });
            },
            removeFamilyMember() {
                let familyMember = this.family.length - 1;

                this.family.splice(familyMember, 1);
            },
            updateRsvp() {
                this.success = false;
                this.error = false;
                this.sending = true;

                axios.post(`/rsvp/${this.familyId}`, this.family).then((response) => {
                    this.success = response.data.success;
                    this.error = !response.data.success;
                    this.sending = false;
                }).catch((error) => {
                    this.error = true;
                    this.sending = false;
                });
            }
        }
    }
</script>
