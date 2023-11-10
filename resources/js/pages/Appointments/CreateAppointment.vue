<script setup>
import axios from 'axios';
import { onMounted, ref, reactive } from 'vue';
import { useToastr } from '../../toastr';
import { useRoute, useRouter } from 'vue-router';
import { Form } from 'vee-validate';

const toastr = useToastr();
const router = useRouter();
const route = useRoute();
const editMode = ref(false);


const form = reactive({
    'title': '',
    'client': '',
    'date': '',
    'time': '',
    'description': '',
});

const handleSubmit = (values, actions) => {
    editMode.value ? updateAppointment(values, actions) : createAppointment(values, actions);
}

const createAppointment = (values, actions) => {
    axios.post('/api/appointments/create', form)
        .then((response) => {
            router.push('/admin/appointments');
            toastr.success("Appointment Created Successfully");
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
}

const updateAppointment = (values, actions) => {
    axios.post(`/api/appointments/${route.params.id}/update`, form)
        .then((response) => {
            router.push('/admin/appointments');
            toastr.success("Appointment Updated Successfully");
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
}

const getAppointment = () => {
    axios.get(`/admin/appointments/${route.params.id}/edit`)
        .then((response) => {
            form.title = response.data.title;
            // form.client = response.data.client;
            form.date = response.data.formatted_start_time;
            form.time = response.data.formatted_end_time;
            form.description = response.data.description;
        })
        .catch((error) => {
            console.log(error);
        })
}

onMounted(() => {
    if (route.name === "admin.appointments.edit") {
        editMode.value = true;
        getAppointment();
    }
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <span v-if="editMode">Edit</span> <span v-else>Create</span> Appointment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointment</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span> <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot:default="{ errors }">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" v-model="form.title" :class="{ 'is-invalid': errors.title }"
                                            class="form-control" id="title">
                                        <span class="invalid-feedback">{{ errors.title }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client">Client Name</label>
                                        <select type="text" v-model="form.client" class="form-control"
                                            :class="{ 'is-invalid': errors.client }" id="client">
                                            <option>Client One</option>
                                            <option>Client Two</option>
                                        </select>
                                        <span class="invalid-feedback">{{ errors.client }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date">Appointment Date</label>
                                        <input type="date" v-model="form.date" class="form-control"
                                            :class="{ 'is-invalid': errors.date }" id="date">
                                        <span class="invalid-feedback">{{ errors.date }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="time">Appointment Time</label>
                                        <input type="time" v-model="form.time" :class="{ 'is-invalid': errors.time }"
                                            class="form-control" id="time">
                                        <span class="invalid-feedback">{{ errors.time }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="decription">Description</label>
                                        <textarea v-model="form.description" class="form-control"
                                            :class="{ 'is-invalid': errors.description }" id="description" cols="30"
                                            rows="5"></textarea>
                                        <span class="invalid-feedback">{{ errors.description }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>