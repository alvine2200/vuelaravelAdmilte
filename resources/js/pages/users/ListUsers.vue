<script setup>
import axios from "axios";
import { ref, onMounted, reactive } from "vue";

const users = ref([]);
const form = reactive({
  name: '',
  email: '',
  password: '',
});


// fetch users
const getUsers = () => {
  axios.get('/api/users').then((response) => {
    users.value = response.data;
  })
};

// create user
const createUser = () => {
  axios.post('/api/users', form)
    .then((response) => {
      users.value.unshift(response.data);
      form.name = "";
      form.email = "";
      form.password = "";

      $('#createUserModal').modal('hide');
    });
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
        <button class="btn btn-primary float-end" data-toggle="modal" data-target="#createUserModal"><i
            class="fas fa-plus-circle"></i>Add User</button>
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
                  <td>{{ user.created_at }}</td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modal for adding user -->
  <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form autocomplete="off">
            <div class="mb-3 col-md-12 form-group">
              <label for="name">Name</label>
              <input v-model="form.name" type="text" class="form-control">
            </div>
            <div class="mb-3 col-md-12 form-group">
              <label for="email">Email</label>
              <input v-model="form.email" type="email" class="form-control">
            </div>
            <div class="mb-3 col-md-12 form-group">
              <label for="password">Password</label>
              <input v-model="form.password" type="password" class="form-control">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" @click="createUser" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
</template>