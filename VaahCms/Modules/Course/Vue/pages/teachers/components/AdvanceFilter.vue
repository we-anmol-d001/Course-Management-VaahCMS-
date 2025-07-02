<script  setup>

import { useTeacherStore } from '../../../stores/store-teachers'
import VhFieldVertical from './../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useTeacherStore();

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

                        <Button data-testid="teachers-hide-filter"
                                class="p-button-sm"
                                @click="store.show_advance_filters = false">
                            <i class="pi pi-times"></i>
                        </Button>

                    </div>

                </template>

            
            <Divider/>

            
            

             <VhFieldVertical >
                <template #label>
                    <b>Course:</b>
                </template>

                <!-- <div class="field-radiobutton">
                    <div class="p-inputgroup">
                        <Dropdown v-model="store.query.filter.course"
                                :options="store.assets.courses"
                                optionValue="id"
                                optionLabel="name"
                                placeholder="Select your course"
                                class="w-full"/>
                    </div>
                </div>
                 -->
            </VhFieldVertical>
             <VhFieldVertical >

                <template #label>
                    <b>Select multiple Courses:</b>
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
