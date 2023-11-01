<script setup>
import axios from "axios";
import { ref, onMounted, reactive } from "vue";
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../toastr.js';


const toastr = useToastr();
const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const userIdBeingDeleted = ref(null);

// validate fields create user
const createUserSchema = yup.object({
  name: yup.string().required(),
  email: yup.string().email().required(),
  password: yup.string().required().min(8),
})

// edit user validation rules
const editUserSchema = yup.object({
  name: yup.string().required(),
  email: yup.string().email().required(),
  password: yup.string().when((password, schema) => {
    return password ? schema.min(8) : schema;
  }),
});


// fetch users
const getUsers = () => {
  axios.get('/api/users').then((response) => {
    users.value = response.data;
  })
};

// adding user model
const adduser = () => {
  editing.value = false;
  $('#userFormModal').modal('show');
}

//handle form submission
const handleSubmit = (values, actions) => {
  editing.value ? updateUser(values, actions) : createUser(values, actions);
}
// create user
const createUser = (values, { resetForm, setErrors }) => {
  axios.post('/api/users', values)
    .then((response) => {
      users.value.unshift(response.data);
      $('#userFormModal').modal('hide');
      toastr.success("User added successfully");
      resetForm();
    })
    .catch((error) => {
      if (error.response.data.errors) {
        setErrors(error.response.data.errors);
        return;
      }
      toastr.error("Unknow Validation Error occurred");
    });
}

const editUser = (user) => {
  editing.value = true;
  form.value.resetForm();
  $('#userFormModal').modal('show');
  formValues.value = {
    id: user.id,
    name: user.name,
    email: user.email,
  }
}

const updateUser = (values, { setErrors }) => {
  axios.put('/api/users/' + formValues.value.id, values)
    .then((response) => {
      const index = users.value.findIndex(user => user.id === response.data.id);
      users.value[index] = response.data;
      $('#userFormModal').modal('hide');
      toastr.success("User updated successfully");
      return;
    })
    .catch((error) => {
      if (error.response.data.errors) {
        setErrors(error.response.data.errors);
        return;
      }
      toastr.error("Unknown Validation Error occurred");
    })
}

const showDeleteUserModal = (user) => {
  userIdBeingDeleted.value = user.id;
  $('#deleteUserModal').modal('show');
}

const deleteUser = () => {
  axios.delete(`/api/users/${userIdBeingDeleted.value}`)
    .then(() => {
      $('#deleteUserModal').modal('hide');
      users.value = users.value.filter(user => user.id !== userIdBeingDeleted.value)
      toastr.success("User deleted successfully!");
      return;
    })
    .catch((error) => {
      toastr.error("Unknown Error occurred on delete");
    });
}

const hideDeleteUserModal = () => {
  $('#deleteUserModal').modal('hide');
}

onMounted(() => {
  getUsers();
});
</script>

<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Users Page</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Users</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="button mx-3 mb-3 row">
        <button type="button" class="btn btn-primary float-end" @click="adduser"><i class="fas fa-plus-circle"></i> Add
          New User
        </button>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 15px">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Registered Date</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in users" :key="user.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td></td>
                  <td></td>
                  <td>
                    <a @click.prevent="editUser(user)"><i class="fa fa-edit mr-2"></i></a>
                    <a @click.prevent="showDeleteUserModal(user)"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modal for adding user -->
  <div class="modal fade" id="userFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <span v-if="editing">Edit User</span>
            <span v-else>Add New User</span>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema"
          v-slot="{ errors }" :initial-values="formValues">
          <div class="modal-body">
            <div class="mb-3 col-md-12 form-group">
              <label for="name">Name</label>
              <Field name="name" id="name" :class="{ 'is-invalid': errors.name }" type="text" class="form-control" />
              <span class="invalid-feedback">{{ errors.name }}</span>
            </div>
            <div class="mb-3 col-md-12 form-group">
              <label for="email">Email</label>
              <Field name="email" :class="{ 'is-invalid': errors.email }" id="email" type="email" class="form-control" />
              <span class="invalid-feedback">{{ errors.email }}</span>
            </div>
            <div class="mb-3 col-md-12 form-group">
              <label for="password">Password</label>
              <Field name="password" :class="{ 'is-invalid': errors.password }" id="password" type="password"
                class="form-control" />
              <span class="invalid-feedback">{{ errors.password }}</span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </Form>
      </div>
    </div>
  </div>

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
          <button @click.prevent="deleteUser" class="btn btn-primary" type="button">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>