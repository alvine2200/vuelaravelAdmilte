<script setup>
import { useToastr } from '../../toastr.js';
import { ref, onMounted, computed } from 'vue';
import { formatDate } from '../../helper.js';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import axios from 'axios';

const toastr = useToastr();
const appointments = ref({ 'data': [] });

const Appointmentstatus = ref([]);
const selectedStatus = ref();
const getAllAppointments = (status, page = 1) => {
    const params = {};
    if (status) {
        params.status = status;
    }
    axios.get(`/api/appointments?page=${page}`, {
        params: params,
    })
        .then((response) => {
            selectedStatus.value = status;
            appointments.value = response.data;
            // console.log(appointments.value);
        })
        .catch((error) => {
            toastr.error("Error occurred during fetch");
        });
}

const getAppointmentStatus = () => {
    axios.get('/api/appointment-status')
        .then((response) => {
            Appointmentstatus.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

const appointmentCount = computed(() => {
    return Appointmentstatus.value.map(status => status.count).reduce((acc, value) => acc + value, 0);
})

onMounted(() => {
    getAllAppointments();
    getAppointmentStatus();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointment Page</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Appointment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <a href="">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New
                                    Appointment</button>
                            </a>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn"
                                :class="[typeof selectedStatus === 'undefined' ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-info">{{ appointmentCount }}</span>
                            </button>

                            <button v-for="status in  Appointmentstatus " @click="getAllAppointments(status.value)"
                                :key="status.id" type="button" class="btn"
                                :class="[selectedStatus === status.value ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">{{ status.name }}</span>
                                <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Client Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="( appointment, index ) in  appointments.data " :key="appointment.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ appointment.client.first_name }} {{ appointment.client.last_name }}</td>
                                        <td>{{ formatDate(appointment.start_time) }}</td>
                                        <td>{{ formatDate(appointment.end_time) }}</td>
                                        <td>
                                            <span class="badge" :class="`badge-${appointment.status.color}`">{{
                                                appointment.status.name }}</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void()">
                                                <i class="fa fa-edit mr-2"></i>
                                            </a>
                                            <a href="javascript:void()">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <Bootstrap4Pagination class="mx-2" :data="appointments" @pagination-change-page="getAllAppointments" />
                </div>
            </div>
        </div>
    </div>
</template>