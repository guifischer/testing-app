<script setup>
import {router, useForm} from '@inertiajs/vue3'
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import {watch} from "vue";
import SelectInput from "@/Components/SelectInput.vue";
import TableHeader from "@/Components/TableHeader.vue";
import {headerData} from "@/Pages/Dashboard/Partials/headerData.js";
import {notify} from "@kyvg/vue3-notification";

const props = defineProps({
    tasks: {
        type: Object,
    },
    filters: {
        type: Object,
    },
    taskStatus: {
        type: Object,
    },
});

let delayTimeout;
const form = useForm(props.filters);

const changeOrder = ({order_by, order_type}) => {
    form.order_by = order_by;
    form.order_type = order_type;
};

const takeOn = (task_id) => {
    router.patch(route('tasks.takeon', {task: task_id}), null, {
        preserveScroll: true,
        onError: () => {
            notify({
                title: "An Error Occurred",
                text: "Failed to take on task",
                type: "error"
            });
        }
    });
};

watch(form, data => {
    clearTimeout(delayTimeout);

    delayTimeout = setTimeout(() => {
        data.get('/dashboard', {
            preserveState: true,
            preserveScroll: true,
        });

    }, 500);

}, {deep: true})
</script>

<template>
    <section class="custom-class-main">
        <div class="custom-class-box">
            <TextInput
                id="search"
                v-model="form.search"
                type="text"
                class="custom-class-search-input"
                placeholder="Search"
            />

            <SelectInput
                :values="taskStatus"
                v-model="form.status"
                placeholder="Status"
            />

            <SelectInput
                :values="[{label: 'Own By Me', value: $page.props.auth.user.id}]"
                v-model="form.owner"
                placeholder="Owner"
            />
        </div>

        <table class="custom-class-table">
            <table-header
                :tableHeaders="headerData"
                @changeOrder="changeOrder"
                :order-by="form.order_by"
                :order-type="form.order_type"
            />
            <tbody>
            <template v-for="(task, index) in tasks.data" :key="index">
                <tr>
                    <td>
                        {{ task.name }}
                    </td>
                    <td>
                        {{ task.description }}
                    </td>
                    <td>
                        {{ task.user_name ?? "-" }}
                    </td>
                    <td class="custom-class-full-text">
                        {{ task.status_description }}
                    </td>
                    <td class="custom-class-full-text">
                        <div class="custom-class-actions">
                            <span @click="$emit('openTaskModal', task)" class="custom-class-span-btn">View</span>
                            <template v-if="task.user_id === null">
                                <span @click="takeOn(task.id)" class="custom-class-span-btn">Take On</span>
                            </template>
                            <template v-else>
                                <span>-</span>
                            </template>
                        </div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <pagination :links="tasks.meta.links"/>
    </section>
</template>

<style>
.custom-class-main {
    margin-bottom: 1rem;
    background: #ffffff;
    border-radius: 5px;
    padding: 1rem;
}

.custom-class-box {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    gap: 0.5rem;
}

.custom-class-box .custom-class-search-input {
    flex-grow: 1;
}

.custom-class-table {
    width: 100%;
}

.custom-class-table th {
    text-align: left;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    height: 20px;
    font-size: 14px;
}

.custom-class-table .custom-class-th-orderable {
    margin-right: 0.5rem;
    cursor: pointer
}

.custom-class-table td {
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
    font-size: 14px;
    padding: 1rem;
}

.custom-class-table .custom-class-full-text {
    text-wrap: nowrap;
}

.custom-class-span-btn {
    cursor: pointer;
    color: #3182ce;
}

.custom-class-span-btn:hover {
    cursor: pointer;
    color: #2c5282;
}

.custom-class-actions {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.custom-class-actions span:first-child {
    margin-right: 0.5rem;
}

</style>
