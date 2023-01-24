<script setup>
import { defineAsyncComponent } from 'vue'
const Sidebar = defineAsyncComponent(() => import('./partials/Sidebar.vue'))
const Footer = defineAsyncComponent(() => import('./partials/Footer.vue'))
</script>

<template>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <Sidebar />
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow navbar-theme">
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid pb-4 ps-4 pe-4">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Profile</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row pt-2 pb-2">
                        <div class="col-md-8 offset-md-2 text-center">
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <vue-gravatar class="rounded-circle" :email="user.email" :title="user.name" :size="120" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" v-model="user.name" placeholder="Name">
                                        <label for="name">Name</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" v-model="user.email" placeholder="Email">
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" v-model="user.gender" aria-label="Gender">
                                            <option value="">select a gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <label for="floatingSelect">Gender</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" v-model="user.birthdate" placeholder="Birthdate">
                                        <label for="name">Birthdate</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" v-model="user.contact_no" placeholder="Name">
                                        <label for="name">Contact No</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" v-model="user.address" placeholder="Address">
                                        <label for="name">Address</label>
                                    </div>
                                </div>
                            </div>
                            <div v-if="error.message" class="alert mt-3" :class="[error.type]" role="alert">
                                {{error.message}}
                            </div>
                            <button class="btn btn-lg btn-primary mt-3" type="button" @click="updateProfile()">Update Profile</button>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <Footer />
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
</template>

<script>
    import API from '../Api';
    import Auth from '../Auth.js';

    export default {
        name: 'app',
        data: () => ({
            user: {
                name: '',
                email: '',
                gender: '',
                birthdate: '',
                contact_no: '',
                address: ''
            },
            error: {
                type: '',
                message: ''
            }
        }),

        mounted() {
            const fetchData = async() => {
                try {
                    let token = localStorage.getItem('token')
                    const response = await API.get(`get-info/${Auth.user.id}`, { headers: {"Authorization" : `Bearer ${token}`} })
                    let data = response.data.data
                    setTimeout(() => 
                        this.user = {
                            name: data.name,
                            email: data.email,
                            gender: data.gender ? data.gender : '',
                            birthdate: data.birthdate,
                            contact_no: data.contact_no,
                            address: data.address,
                            avatar: data.avatar,
                        }
                    , 100)
                } catch (error) {
                    console.log(error)
                }
            }
            fetchData();
        },

        methods: {
            clear() {
                setTimeout(() => {
                    this.error = {
                        type: '',
                        message: ''
                    }
                }, 5000)
            },

            updateProfile() {
                let token = localStorage.getItem('token')
                API.put(`update-info/${Auth.user.id}`, this.user, { headers: {"Authorization" : `Bearer ${token}`} })
                .then(({data}) => {
                    console.log(data)
                    if(data.status === 'success') {
                        this.error.type = 'alert-success'
                    } else {
                        this.error.type = 'alert-danger'
                    }
                    this.error.message = data.message
                    this.clear()
                })
                .catch((error) => {
                    console.log(error)
                    this.error.type = 'alert-danger'
                    this.error.message = error.message
                    this.clear()
                });
            }
        }
    }
</script>