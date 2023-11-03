<script setup>
import axios from 'axios';
import { formatDate } from '../../helper';
import { useToastr } from '../../toastr';
import { ref } from 'vue';

defineProps({
    user: Object,
    index: Number,
})

const toastr = useToastr();
const userIdBeingDeleted = ref(null);
const emit = defineEmits(['userDeleted', 'editUser']);
const roles = ref([
    {
        name: 'ADMIN',
        value: 1,
    }, {
        name: 'USER',
        value: 2,
    }
])

const showDeleteUserModal = (user) => {
    userIdBeingDeleted.value = user.id;
    $('#deleteUserModal').modal('show');
}

const editUser = (user) => {
    emit('editUser', user);
}

const deleteUser = (user) => {
    axios.post('/api/users/' + user.id, {
        userId: user.id,
    })
        .then(() => {
            console.log(user);
            $('#deleteUserModal').modal('hide');
            toastr.success("User deleted successfully!");
            emit('userDeleted', userIdBeingDeleted.value); // Use userIdBeingDeleted.value
        })
}

const hideDeleteUserModal = () => {
    $('#deleteUserModal').modal('hide');
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
            <a @click.prevent="showDeleteUserModal(user)"><i class="fa fa-trash"></i></a>
        </td>
    </tr>

    <!-- modal for deleting user -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span>Delete User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are You Sure You Want To Delete This? </h5>
                </div>
                <div class="modal-footer">
                    <button @click.prevent="hideDeleteUserModal" class="btn btn-secondary" type="button">Close</button>
                    <button @click.prevent="deleteUser(user)" class="btn btn-primary" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>