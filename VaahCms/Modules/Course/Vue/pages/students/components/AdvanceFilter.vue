<script  setup>

import { ref, watch } from 'vue';
import { useStudentStore } from '../../../stores/store-students'
import VhFieldVertical from '../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useStudentStore();
const course_count_range = ref([0, store.assets.total_courses])

watch(course_count_range, (range) => {
    store.query.filter.course_count_min = range[0]
    store.query.filter.course_count_max = range[1]
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
             <VhFieldVertical label="Select the Date range">
                <div class="p-inputgroup">
                        <Calendar class="w-full"
                                   placeholder="Enter your DOB(FROM)"
                                   name="students-dob"
                                   
                                   v-model="store.query.filter.dob_from" required/>
                        <div class="required-field hidden"></div>
                    </div>
                <div class="p-inputgroup">
                        <Calendar class="w-full"
                                   placeholder="Enter your DOB(TO)"
                                   name="students-dob"
                                   
                                   v-model="store.query.filter.dob_to" required/>
                        <div class="required-field hidden"></div>
                    </div>
             </VhFieldVertical>
            <Divider/>

            <VhFieldVertical >
                <template #label>
                    <b>Enrolled courses range:</b>
                </template>
                <div >
                    <div class="mb-3 p-2">
                        <Slider v-model="course_count_range"
                                range
                                :min="0" 
                                :max="store.assets.total_courses"
                                :step="1"
                                class="w-full"/>
                    </div>
                </div>
                <div class="text-sm mt-1 text-gray-500">
                    {{ course_count_range[0] }} - {{store.assets.total_courses }}
                </div>
                
            </VhFieldVertical>

             <Divider/>

            <VhFieldVertical >
                <template #label>
                    <b>Courses:</b>
                </template>

                <div class="field-radiobutton">
                    <div class="p-inputgroup">
                        <MultiSelect
                            v-model="store.query.filter.course"
                            :options="store.assets.courses"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select course(s)"
                            class="w-full"
                            display="chip"
                            :filter="true"
                        />
                    </div>
                </div>
                
            </VhFieldVertical>


        </Panel>

    </div>
</template>
