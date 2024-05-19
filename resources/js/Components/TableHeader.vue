<script setup>

const emit = defineEmits(['changeOrderBy', "changeOrderType"]);
const props = defineProps({
    tableHeaders: {
        type: Array,
    },
    orderBy: {
        type: String,
    },
    orderType: {
        type: String,
    },
});

const changeOrder = (key) => {
    let type = 'asc';
    if (props.orderBy === key) {
        type = props.orderType === 'asc' ? 'desc' : 'asc';
    }

    emit('changeOrder', {
        order_by: key,
        order_type: type,
    })
}

</script>

<template>
    <thead>
        <tr>
            <template v-for="(header, index) in tableHeaders" :key="index">
                <th scope="col">
                    <template v-if="header.order_available && !header.hide">
                        <span @click="changeOrder(header.key)" class="custom-class-th-orderable">
                            {{ header.label }}
                        </span>

                        <template v-if="orderBy === header.key">
                            <span v-if="orderType === 'asc'">▲</span>
                            <span v-else>▼</span>
                        </template>
                        <template v-else>
                            <span style="color: #99999980;">⇅</span>
                        </template>

                    </template>

                    <template v-else-if="!header.hide">
                        <span>{{ header.label }}</span>
                    </template>
                </th>
            </template>
        </tr>
    </thead>
</template>
