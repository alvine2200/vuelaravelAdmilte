<script setup>
//
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Appointment Page</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><route-link to="">Home</route-link></li>
                        <li class="breadcrumb-item"><router-link to="/admin/appointments">Appointment</router-link>
                        </li>
                        <li class="breadcrumb-item active">Home</li>
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
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" v-model="title" name="title" class="form-control" id="title">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client">Client Name</label>
                                        <select type="text" v-model="client" name="client" class="form-control" id="client">
                                            <option>Client One</option>
                                            <option>Client Two</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date">Appointment Date</label>
                                        <input type="date" v-model="date" name="date" class="form-control" id="date">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="time">Appointment Time</label>
                                        <input type="time" v-model="time" name="time" class="form-control" id="time">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="decription">Description</label>
                                        <textarea name="description" class="form-control" id="description" cols="30"
                                            rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>