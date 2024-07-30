<template>
  
                
    <div class="p-6 text-center">
        <div >
            <TIcon name="warning" size="xl" class="text-red-600">
                <span class="material-symbols-outlined ">
                    warning
                    </span>
            </TIcon>
        </div>
        
        <h3 class="font-sans text-xl font-normal text-black dark:text-gray-400">Are you sure you want to delete this Event?</h3>
        <button  @click="deleteEvent" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
            Yes, I'm sure
        </button>
        <button  @click="cancel" data-modal-hide="popup-modal" type="button" class="font-sans text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
    </div>

</template>

<script>
import { defineComponent } from 'vue';
import { notify } from 'notiwind';

export default defineComponent({
name: 'DeleteEvent',
setup(){},
emits: [
    'cancel',
    'done'
],
props: {
        events:{
            type: Object,
             default: null,
        }
       
    },
data(){
    return{
        componentOptions: {}
    }

},


methods: {
        deleteEvent(){
           
            this.$api.delete('/events/delete',{
                params:{
                    id: this.events.hash
                }
            })
            .then((response)=>{
              
                notify({group:"main", title:"Events deleted", type:"positive"},'3000')
            })
            this.$emit('done')
        },
        cancel(){
           
            this.$emit('cancel')
        }
     

    }
})
</script>
