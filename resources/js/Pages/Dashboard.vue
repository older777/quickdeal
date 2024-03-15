<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref, toRaw, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    tasks: {
        type: Object,
        required: false,
    },
    token: {
        type: String,
        default: '',
        required: false,
    }
});

const showEdit = ref(false);
const showCreate = ref(false);
const showVueX = ref(false);
const keyVueX = ref('');
const valueVueX = ref('');
const store = useStore();

const formEdit = useForm({
    id: '',
    name: '',
    status: true,
    comment: '',
    manager: '',
});

const loadTasks = (sort) => {
    if (sort) {
        router.reload({ only: ['tasks'], data: {sort: sort }});
    } else {
        router.reload({ only: ['tasks'] });
    }
}
const taskEdit = (index) => {
    const task = toRaw(props.tasks.data[index]);
    formEdit.id = task.id;
    formEdit.name = task.name;
    formEdit.comment = task.comment;
    formEdit.manager = task.manager;
    formEdit.status = task.status == 'Resolved' ? true : false;
    showEdit.value = true;
}
const taskDelete = async (index) => {
    if (confirm('Удалить задачу "' + props.tasks.data[index].name + '"?')) {
        await axios.delete(route('task.destroy', { task: props.tasks.data[index].id }))
            .then(() => {
                loadTasks();
            })
            .catch(function (error) {
                if (error.response) {
                    alert(error.response.data);
                }
            });
    }
}
const enableSubmit = computed(() => {
    if (formEdit.processing) {
        return true;
    } else {
        return false;
    }
});
const submitEdit = async () => {
    formEdit.clearErrors();
    formEdit.processing = true;
    await axios.put(route('task.update', { task: formEdit.id }), formEdit)
        .then((resp) => {
            alert(resp.data.msg);
        }
        )
        .catch(function (error) {
            formEdit.processing = false;
            if (error.response) {
                alert(error.response.data);
            }
        });
    formEdit.processing = false;
};
const saveVueX = () => {
    store.state[keyVueX.value] = valueVueX.value;
    keyVueX.value = null;
    valueVueX.value = null;
} 

// События
onMounted(() => {
    router.reload({ only: ['token'] });
});
watch(() => props.token, (newToken) => {
    window.axios.defaults.headers.common = { 'Authorization': `Bearer ${newToken}` }
});
</script>

<template>
    <Head title="Задачи" />

    <AuthenticatedLayout>
        <template #header>
            <div class="grid grid-cols-6 grid-auto-columns-1/6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Задачи</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="w-full grid grid-cols-6 my-4 *:mx-4">
                            <PrimaryButton @click="loadTasks" class="w-42">Обновить список</PrimaryButton>
                            <PrimaryButton @click="loadTasks('created_at')" class="w-42">Сортировка по дате</PrimaryButton>
                            <PrimaryButton @click="loadTasks('status')" class="w-42">Сортировка по статусу</PrimaryButton>
                            <PrimaryButton class="w-42" @click="showCreate = true">Создать задачу</PrimaryButton>
                            <PrimaryButton class="w-42" @click="showVueX = true">VueX</PrimaryButton>
                        </div>
                        <div v-if="tasks && tasks.data.length" class="w-full">
                            <div class="text-xl my-4">Список</div>
                            <table>
                                <thead>
                                    <th>ID</th>
                                    <th>Менеджер</th>
                                    <th>Имя задачи</th>
                                    <th>Комментарий</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th></th>
                                    <th></th>
                                </thead>
                                <tbody v-for="(item, index) in tasks.data" :key="item.id">
                                    <tr class="*:p-4">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.manager }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.comment }}</td>
                                        <td>{{ item.created }}</td>
                                        <td>{{ item.status }}</td>
                                        <td><svg @click="taskEdit(index)" class="h-4 cursor-pointer"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 121.51">
                                                <title>Нажмите для управления</title>
                                                <path
                                                    d="M28.66,1.64H58.88L44.46,16.71H28.66a13.52,13.52,0,0,0-9.59,4l0,0a13.52,13.52,0,0,0-4,9.59v76.14H91.21a13.5,13.5,0,0,0,9.59-4l0,0a13.5,13.5,0,0,0,4-9.59V77.3l15.07-15.74V92.85a28.6,28.6,0,0,1-8.41,20.22l0,.05a28.58,28.58,0,0,1-20.2,8.39H11.5a11.47,11.47,0,0,1-8.1-3.37l0,0A11.52,11.52,0,0,1,0,110V30.3A28.58,28.58,0,0,1,8.41,10.09L8.46,10a28.58,28.58,0,0,1,20.2-8.4ZM73,76.47l-29.42,6,4.25-31.31L73,76.47ZM57.13,41.68,96.3.91A2.74,2.74,0,0,1,99.69.38l22.48,21.76a2.39,2.39,0,0,1-.19,3.57L82.28,67,57.13,41.68Z" />
                                            </svg></td>
                                        <td><svg @click="taskDelete(index)" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 576 512" class="h-4 cursor-pointer">
                                                <path fill="red"
                                                    d="M290.7 57.4L57.4 290.7c-25 25-25 65.5 0 90.5l80 80c12 12 28.3 18.7 45.3 18.7H288h9.4H512c17.7 0 32-14.3 32-32s-14.3-32-32-32H387.9L518.6 285.3c25-25 25-65.5 0-90.5L381.3 57.4c-25-25-65.5-25-90.5 0zM297.4 416H288l-105.4 0-80-80L227.3 211.3 364.7 348.7 297.4 416z" />
                                            </svg></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2>VueX</h2>
                        <div class="grid grid-cols-6 *:px-4"><span>Key</span> <span>Value</span></div>
                        <template v-for="(item, index) in store.state" :key="index">
                            <div class="grid grid-cols-6 *:px-4">
                                <span>{{ index }}</span>
                                <span>{{ item }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="showEdit" :maxWidth="'xl'" :closeable="true">
        <form @submit.prevent="submitEdit" class="w-full mx-4 my-2">
            <div>ID: {{ formEdit.id }}</div>
            <div>Менеджер: {{ formEdit.manager }}</div>
            <div>Название: {{ formEdit.name }}</div>
            <InputLabel for="comment" value="Введите ответ" />
            <TextArea name="comment" v-model="formEdit.comment" vale class="!w-3/4" />
            <input type="checkbox" id="status" name="status" v-model="formEdit.status" />
            <InputLabel for="status" value="решено" class="inline-block ml-1 w-3/4" />
            <input type="hidden" name="id" :value="formEdit.id">
            <PrimaryButton type="submit" class="mt-2 mr-2 disabled:pointer-events-none disabled:bg-gray-300"
                :disabled="enableSubmit">
                Отправить
            </PrimaryButton>
            <SecondaryButton class="mt-2" @click="() => { loadTasks(); showEdit = false; }">Закрыть</SecondaryButton>
        </form>
    </Modal>

    <ModalCreate :show="showCreate" @close-modal="(val) => showCreate = val" @reloadTask="loadTasks()" />

    <Modal :show="showVueX">
        <div class="p-6 m-6 w-full">
            <h1 class="mb-4">Создать запись в хранилище VueX</h1>
            <InputLabel for="key" value="Введите ключ" />
            <TextInput name="key" class="inline-block ml-1 w-3/4" v-model="keyVueX" />
            <InputLabel for="value" value="Введите значение" />
            <TextInput name="value" class="inline-block ml-1 w-3/4" v-model="valueVueX" />
            <br />
            <PrimaryButton @click="saveVueX" class="mt-2 mr-2 disabled:pointer-events-none disabled:bg-gray-300"
                :disabled="enableSubmit">
                Сохранить
            </PrimaryButton>
            <SecondaryButton class="mt-2" @click="showVueX = false">Закрыть</SecondaryButton>
        </div>
    </Modal>
</template>
