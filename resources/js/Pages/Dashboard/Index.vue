<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import TasksList from "@/Pages/Dashboard/Partials/TasksList.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref} from "vue";
import Modal from "@/Components/Modal.vue";
import TaskInfo from "@/Pages/Dashboard/Partials/TaskInfo.vue";

const props = defineProps({
    filters: {
        type: Object,
    },
    tasks: {
        type: Array,
    },
    taskStatus: {
        type: Object,
    },
    taskPriorities: {
        type: Object,
    },
});

const selectedTask = ref(null);
const displayTaskModal = ref(false);

const openTaskModal = (task) => {
    displayTaskModal.value = true;
    selectedTask.value = task;
};

const closeTaskModal = () => {
    displayTaskModal.value = false;
};

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <section class="max-w-7xl mx-auto sm:px-6 lg:px-4 py-4 my-4">
            <section class="py-4 my-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-row justify-between items-center">
                    <h2 class="p-6 text-gray-900">Welcome {{ $page.props.auth.user.name }}</h2>
                    <secondary-button class="mr-6" @click="openTaskModal(null)">Create task</secondary-button>
                </div>
            </section>

            <tasks-list
                :tasks="tasks"
                :filters="filters"
                :taskStatus="taskStatus"
                @openTaskModal="openTaskModal"
            />

            <modal :show="displayTaskModal" @close="closeTaskModal">
                <task-info
                    :task="selectedTask"
                    :taskStatus="taskStatus"
                    :taskPriorities="taskPriorities"
                    @close-modal="closeTaskModal"
                />
            </modal>
        </section>
    </AuthenticatedLayout>
</template>
