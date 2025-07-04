<script  setup>

import { ref, watch } from 'vue';
import { useCourseStore } from '../../../stores/store-courses'
import VhFieldVertical from '../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useCourseStore();
const student_count_range = ref([0, store.assets.total_students])

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
                                :max="store.assets.total_students"
                                :step="1"
                                class="w-full"/>
                    </div>
                </div>
                <div class="text-sm mt-1 text-gray-500">
                    {{ student_count_range[0] }} - {{student_count_range[1] }}
                </div>
                
            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Select student to see their courses:</b>
                </template>

               <div class="field-radiobutton">
                  
                <div class="p-inputgroup">
                    <AutoComplete
                    v-model="store.query.filter.student"
                  
                    :suggestions="store.auto_filter_list"  
                    placeholder="Select student(s)"
                    class="w-full"
                    display="chip"
                    :filter="false"                       
                    :multiple="true"
                    :field="'name'"                     
                    :optionValue="'id'"                      
                    @complete="store.searchStudents"           
                    />
                </div>
                </div>
                
            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Select teachers:</b>
                </template>

                <div class="field-radiobutton">
                    <div class="p-inputgroup">
                        <MultiSelect
                            v-model="store.query.filter.teacher"
                            :options="store.assets.teachers"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select teacher(s)"
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
