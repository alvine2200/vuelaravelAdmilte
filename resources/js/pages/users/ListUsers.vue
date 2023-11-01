<script setup>
import axios from "axios";
import { ref, onMounted, reactive } from "vue";
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';

const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);

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
const handleSubmit = (values) => {
  editing.value ? updateUser(values) : createUser(values);
}
// create user
const createUser = (values) => {
  axios.post('/api/users', values)
    .then((response) => {
      users.value.unshift(response.data);
      $('#userFormModal').modal('hide');
      form.value.resetForm();
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

const updateUser = (values) => {
  axios.put('/api/users/' + formValues.value.id, values)
    .then((response) => {
      const index = users.value.findIndex(user => user.id === response.data.id);
      users.value[index] = response.data;
      $('#userFormModal').modal('hide');
      resetForm();
    }).catch((error) => {
      console.log(error);
    }).finally(() => {
      form.value.resetForm();
    })
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
                    <a @click.prevent="editUser(user)"><i class="fa fa-edit"></i></a>
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
</template>