<template>
    <div class="flex flex-col items-center justify-center p-4 md:p-8 md:pt-4">
        <div class="flex justify-center">
            <div class="flex flex-col md:flex-row items-center text-center text-slate-800">
                <TIcon :name="content.icon" size="xl" ></TIcon>
                <span class="font-sans text-2xl font-semibold flex flex-col items-center justify-center md:px-2">
                    {{ content.title }}
                </span>
            </div>
        </div>

        <div class="flex-1 pt-2 md:p-4">
            <p class="py-1 font-sans bg-white" v-html="content.content">
            </p>

        </div>
       

    </div>

</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'PostMeeting',
    setup() { },

    props:{
        content:{
            type:Object,
            default:null
        }
    },

    mounted(){
        this.show()
    },

    data() {
        return {
            componentOptions: {}
        }
    },
   
})
</script>
