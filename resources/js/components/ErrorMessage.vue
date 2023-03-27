<template>
    <span v-if="error" class="mt-2 text-red-500 italic">{{ error }}</span>
</template>

<script>
export default {
    props: {
        errors: {
            type: Array,
            default: [],
        },
    },
    computed: {
        error() {
            if (this.errors.length) {
                return this.errors[0]
            }

            return null
        }
    }
}
</script>
