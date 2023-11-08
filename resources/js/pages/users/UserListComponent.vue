<script setup>
import axios from 'axios';
import { formatDate } from '../../helper';
import { useToastr } from '../../toastr';
import { ref } from 'vue';

const props = defineProps({
    user: Object,
    index: Number,
    selectAll: Boolean,
})

const toastr = useToastr();
const emit = defineEmits(['confirmUserDeletion', 'editUser', 'toggleSelection']);

const roles = ref([
    {
        name: 'ADMIN',
        value: 1,
    }, {
        name: 'USER',
        value: 2,
    }
])


const editUser = (user) => {
    emit('editUser', user);
}

const changeRole = (user, role) => {
    axios.patch('/api/users/' + user.id + '/change_role', {
        role: role,
    }).then((response) => {
        toastr.success('User Role Changed successfully');
    }).catch((error) => {
        toastr.error('Role change failed, something went wrong');
    });
}

</script>

<template>
    <tr>
        <td>
            <input @change="$emit('toggleSelection', props.user)" :checked="selectAll" type="checkbox" class="form-control">
        </td>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ formatDate(user.created_at) }}</td>
        <td>
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="role in roles" :value="role.value" :selected="user.role === role.name">{{ role.name }}
                </option>
            </select>
        </td>
        <td>
            <a @click.prevent="editUser(user)"><i class="fa fa-edit mr-2"></i></a>
            <a @click.prevent="$emit('confirmUserDeletion', user.id)"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
</template>