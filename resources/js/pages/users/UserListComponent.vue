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
const emit = defineEmits(['userDeleted']);


const showDeleteUserModal = (user) => {
    userIdBeingDeleted.value = user.id;
    console.log('userIdBeingDeleted value:', userIdBeingDeleted.value);
    $('#deleteUserModal').modal('show');
}

const deleteUser = (user) => {
    axios.delete('/api/users/' + user.id)
        .then(() => {
            $('#deleteUserModal').modal('hide');
            toastr.success("User deleted successfully!");
            emit('userDeleted', userIdBeingDeleted.value); // Use userIdBeingDeleted.value
        })
}

const hideDeleteUserModal = () => {
    $('#deleteUserModal').modal('hide');
}

</script>

<template>
    <tr>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ formatDate(user.created_at) }}</td>
        <td>{{ user.role }}</td>
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