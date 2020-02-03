<template>
  <div class="admin-list">
      <ui-table :bus="this" class="is-fullwidth" v-bind="{data,columns}">
      </ui-table>
  </div>
</template>

<script>
import api from '@api/users'
export default {
    name:'admin-user-list',
    data(){
        return {
            meta:'',
            page:1,
            data:[],
            columns:[
                {
                    name:'id',
                    label:'no',
                },
                {
                    name:'email',
                    label:'email',
                },
                {
                    name:'first_name',
                    label:'first name',
                },
                {
                    name:'last_name',
                    label:'last name',
                },
            ],
        }
    },
    methods:{
        async remove(){
            console.log('remove user');

        },
        async users (){
            var list = await api.index();
            this.data = list.data.data;
            this.meta = list.data.meta;
        }

    },
    created (){
        this.users();
    },
}
</script>

<style lang="scss">

</style>
