<template>
    <div class="modal-wrapper d-flex">
        <div class="m-auto text-center">
            <span class="material-icons loading-icon m-auto">
                loop
            </span>

            <p class="text-small">Loading...</p>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'

@Component
export default class Loader extends Vue {
    // ...
}
</script>
