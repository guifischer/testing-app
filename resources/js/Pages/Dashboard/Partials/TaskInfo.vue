<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";

import TextareaInput from "@/Components/TextareaInput.vue";
import DangerButton from "@/Components/DangerButton.vue";

const emit = defineEmits(['closeModal']);
const props = defineProps({
    task: {
        type: Object,
        required: false,
    },
    taskStatus: {
        type: Object,
        required: true,
    },
    taskPriorities: {
        type: Object,
        required: true,
    },
});

const new_task = {
    name: '',
    description: '',
    status: '',
    priority: '',
    due_at: '',
}

const form = useForm(props.task != null ? props.task : new_task);
const editingInfo = ref(props.task == null);
const nameInput = ref(null);
const descriptionInput = ref(null);
const statusInput = ref(null);
const priorityInput = ref(null);
const deadlineInput = ref(null);

const cancelEdit = () => {
    form.reset();

    if (props.task) {
        editingInfo.value = false;
        return;
    }

    emit('closeModal');
};

const save = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => emit('closeModal'),
        onError: () => {
            // Handle Error here - Add a message to user or something like that
            // Reset the form field to initial value?
            // use InputRef if we want to focus an element - Need to define what field to focus if we have multiple errors
        }
    };

    if (props.task) {
        updateTask(route('tasks.update', {task: props.task.id}), options);
        return;
    }

    createTask(route('tasks.create'), options);
};

const createTask = (url, options) => {
    form.post(url, options);
};

const updateTask = (url, options) => {
    form.patch(url, options);
};

const completeTask = () => {
    form.status = props.taskStatus.COMPLETED.value;
    save();
};

const deleteTask = () => {
    form.delete(route('tasks.destroy', {task: props.task.id}), {
        preserveScroll: true,
        onSuccess: () => emit('closeModal'),
        onError: () => console.log('error'), // Handle Error here - Add a message to user or something like that
    });
};

</script>

<template>
    <div class="p-6 my-4">
        <div class="flex flex-row justify-between">
            <h3 class="text-base font-semibold leading-7 text-gray-900">Task Details</h3>

            <template v-if="editingInfo">
                <div class="space-x-2">
                    <SecondaryButton @click="cancelEdit">Cancel</SecondaryButton>
                    <SecondaryButton @click="save">Save</SecondaryButton>
                </div>
            </template>

            <template v-else-if="task != null && task.user_id === $page.props.auth.user.id">
                <div class="space-x-2">
                    <SecondaryButton @click="editingInfo=true">Edit</SecondaryButton>

                    <template v-if="form.status === taskStatus.IN_PROGRESS.value">
                        <SecondaryButton @click="completeTask">Complete</SecondaryButton>
                    </template>
                </div>
            </template>
        </div>
        <div class="mt-6 border-t border-gray-100 flex flex-col">

                <div class="mt-6">
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        ref="nameInput"
                        v-model="form.name"
                        type="text"
                        :class="[`mt-1 w-full`, !editingInfo ? 'bg-gray-100' : '']"
                        placeholder="Name"
                        :disabled="!editingInfo"
                    />

                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="description" value="Description" />

                    <TextareaInput
                        id="description"
                        ref="descriptionInput"
                        v-model="form.description"
                        type="text"
                        :class="[`mt-1 w-full`, !editingInfo ? 'bg-gray-100' : '']"
                        placeholder="Description"
                        :disabled="!editingInfo"
                    />

                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="status" value="Status" />

                    <SelectInput
                        id="status"
                        ref="statusInput"
                        v-model="form.status"
                        :values="taskStatus"
                        type="text"
                        :class="[`mt-1 w-full`, !editingInfo ? 'bg-gray-100' : '']"
                        placeholder="Status"
                        :disabled="!editingInfo"
                    />

                    <InputError :message="form.errors.status" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="priority" value="Priority" />

                    <SelectInput
                        id="priority"
                        ref="priorityInput"
                        v-model="form.priority"
                        :values="taskPriorities"
                        type="text"
                        :class="[`mt-1 w-full`, !editingInfo ? 'bg-gray-100' : '']"
                        placeholder="Priority"
                        :disabled="!editingInfo"
                    />

                    <InputError :message="form.errors.priority" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="due_at" value="Deadline" />

                    <TextInput
                        id="due_at"
                        ref="deadlineInput"
                        v-model="form.due_at"
                        type="date"
                        :class="[`mt-1 w-full`, !editingInfo ? 'bg-gray-100' : '']"
                        placeholder="Deadline"
                        :disabled="!editingInfo"
                    />

                    <InputError :message="form.errors.due_at" class="mt-2" />
                </div>

            <template v-if="editingInfo && task != null && task.user_id === $page.props.auth.user.id">
                <danger-button @click="deleteTask" class="mt-6 self-end">Delete</danger-button>
            </template>
        </div>
    </div>
</template>
