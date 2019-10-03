<template>
    <div class="notification-container">
        <div ref="list" class="notification-list">
        </div>
    </div>
</template>

<script>
export default {
    name:'notification-container',
    mounted(){
        Vue.prototype.$notification.container = this.$refs['list'];
    },
}
</script>
