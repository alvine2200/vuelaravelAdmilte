<script setup>
import { useToastr } from '../../toastr.js';
import { ref, onMounted, computed } from 'vue';
import { formatDate } from '../../helper.js';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import axios from 'axios';
import Swal from 'sweetalert2';

const toastr = useToastr();
const appointment = ref([]);
const appointments = ref([]);


const confirmAppointmentDelete = (appointmentId) => {
    appointment.value = appointmentId;
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/appointments/${appointmentId}/delete`)
                .then((response) => {
                    appointments.value.data = appointments.value.data.filter(appointment => appointment.id != appointmentId);
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                })
                .catch((error) => {
                    Swal.fire({
                        title: "Not Deleted!",
                        text: "Something went wrong.",
                        icon: "error"
                    });
                    // toastr.error('Something Went wrong!');
                })

        }
    });
}

const Appointmentstatus = ref([]);
const selectedStatus = ref();


const getAllAppointments = (status) => {
    const params = {};
    if (status) {
        params.status = status;
    }
    axios.get(`/api/appointments`, {
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
                            <router-link to="/admin/appointments/create">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New
                                    Appointment</button>
                            </router-link>
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
                                            <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>
                                            <a @click.prevent=confirmAppointmentDelete(appointment.id)>
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <Bootstrap4Pagination class="mx-2" :data="appointments" @pagination-change-page="getAllAppointments" /> -->
                </div>
            </div>
        </div>
    </div>
</template>