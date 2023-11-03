<script setup>
import axios from "axios";
import { ref, onMounted, reactive, watch } from "vue";
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../toastr.js';
import UserListComponent from "./UserListComponent.vue";
import { debounce } from 'lodash';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';


const toastr = useToastr();
const users = ref({ 'data': [] });
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
const getUsers = (page = 1) => {
  axios.get(`/api/users?page=${page}`)
    .then((response) => {
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

const userDeleted = (userId) => {
  users.value = users.value.filter(user => user.id !== userId)
}


const searchQuery = ref(null);

const search = () => {
  axios.get('/api/users/search', {
    params: {
      query: searchQuery.value,
    }
  }).then((response) => {
    users.value = response.data;
  }).catch((error) => {
    console.log(error);
  });
}

const selectedUsers = ref([]);
const toggleSelection = (user) => {
  const index = selectedUsers.value.indexOf(user.id);
  if (index === -1) {
    selectedUsers.value.push(user.id);
  } else {
    selectedUsers.value.splice(index, 1);
  }
  console.log(selectedUsers.value);
}

const bulkDelete = () => {
  axios.delete('/api/users', {
    data: {
      ids: selectedUsers.value,
    }
  })
    .then((response) => {
      users.value.data = users.value.data.filter(user => !selectedUsers.value.includes(user.id));
      selectedUsers.value = [];
      selectAll.value = false;
      toastr.success(response.data.message);
    })
    .catch((error) => {
      toastr.error("Unknown Error occur during deletion");
    })
}

const selectAll = ref(false);

const selectAllUsers = () => {
  if (selectAll.value) {
    selectedUsers.value = users.value.data.map(user => user.id);
  } else {
    selectedUsers.value = [];
  }
  console.log(selectedUsers.value);
}

watch(searchQuery, debounce(() => {
  search();
}));

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
      <div class="button mx-3 mb-3 row d-flex justify-content-between">
        <div>
          <button type="button" class="btn btn-primary" @click="adduser"><i class="fas fa-plus-circle"></i> Add
            New User
          </button>
          <button v-if="selectedUsers.length > 0" type="button" class="btn btn-danger ml-2" @click="bulkDelete">Delete
            selected</button>
        </div>
        <div>
          <input type="text" v-model="searchQuery" class="form-control" placeholder="search...">
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" v-model="selectAll" @change="selectAllUsers" class="form-control">
                  </th>
                  <th style="width: 15px">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Registered Date</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody v-if="users.data.length > 0">
                <UserListComponent v-for="(user, index) in users.data" :key="user.id" :user=user :index=index
                  @edit-user="editUser" @user-deleted="userDeleted" @toggle-selection="toggleSelection"
                  :select-all="selectAll" />
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="6" class="text-center">No Results Found</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <Bootstrap4Pagination class="mx-2" :data="users" @pagination-change-page="getUsers" />
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