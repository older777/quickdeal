<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    show: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits(['reloadTask', 'closeModal']);

const formCreate = useForm({
    name: '',
    comment: '',
});
const close = () => {
    emit('closeModal', false);
};
const enableSubmit = computed(() => {
    if (formCreate.processing) {
        return true;
    } else {
        return false;
    }
});
const create = async () => {
    formCreate.clearErrors();
    formCreate.processing = true;
    await axios.post(route('task.store'), formCreate)
        .then((resp) => {alert(resp.data.msg);})
        .catch(function (error) {
            formCreate.processing = false;
            if (error.response) {
                alert(error.response.data);
            }
        });
    formCreate.processing = false;
};
</script>
<template>
    <Modal :show="show" :maxWidth="'xl'" :closeable="true">
        <form @submit.prevent="create" class="w-full mx-4 my-2">
            <InputLabel for="name" value="Название задачи" />
            <TextInput name="name" v-model="formCreate.name" class="inline-block ml-1 w-3/4" />
            <InputLabel for="comment" value="Комментарий" />
            <TextArea name="comment" v-model="formCreate.comment" class="inline-block ml-1 !w-3/4" />
            <br />
            <PrimaryButton type="submit" class="mt-2 mr-2 disabled:pointer-events-none disabled:bg-gray-300"
                :disabled="enableSubmit">
                Создать
            </PrimaryButton>
            <SecondaryButton class="mt-2" @click="close(); emit('reloadTask');">Закрыть</SecondaryButton>
        </form>
    </Modal>
</template>