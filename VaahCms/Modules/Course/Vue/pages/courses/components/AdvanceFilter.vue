<script  setup>

import { ref, watch } from 'vue';
import { useCourseStore } from '../../../stores/store-courses'
import VhFieldVertical from '../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useCourseStore();
const student_count_range = ref([0, 10])

watch(student_count_range, (range) => {
    store.query.filter.student_count_min = range[0]
    store.query.filter.student_count_max = range[1]
})

</script>

<template>
    <div class="col-3" v-if="store.show_advance_filters">

            <Panel class="is-small">

                <template class="p-1" #header>

                    <div class="flex flex-row">
                        <div >
                            <b class="mr-1">Advance Filters</b>
                        </div>

                    </div>

                </template>

                <template #icons>

                    <div class="p-inputgroup">

                        <Button data-testid="students-hide-filter"
                                class="p-button-sm"
                                @click="store.show_advance_filters = false">
                            <i class="pi pi-times"></i>
                        </Button>

                    </div>

                </template>
             
            <Divider/>

            <VhFieldVertical >
                <template #label>
                    <b>Student Count range:</b>
                </template>
                <div >
                    <div class="mb-2 p-2">
                        <Slider v-model="student_count_range"
                                range
                                :min="0" 
                                :max="10"
                                :step="1"
                                class="w-full"/>
                    </div>
                </div>
                <div class="text-sm mt-1 text-gray-500">
                    {{ student_count_range[0] }} - {{student_count_range[1] }}
                </div>
                
            </VhFieldVertical>

             

           

        </Panel>

    </div>
</template>
