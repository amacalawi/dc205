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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button @click="loadPrev()" class="me-1 btn btn-secondary" :disabled="start === 0 || toggledPrev > 0">Prev</button>
                            <button @click="loadNext()" class="ms-1 btn btn-secondary" :disabled="ended > 0 || toggledNext > 0">Next</button>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row pt-2 pb-2">
                        <div class="col-md-6 col-lg-3" v-for="item in toRender" :key="item">
                            <div class="card" :id="`card-${item.id}`">
                                <div class="card-body d-flex flex-row">
                                    <h5 class="card-title font-weight-bold mb-2 text-uppercase">{{ item.name }}</h5>
                                </div>
                                <div class="bg-image hover-overlay ripple rounded-0" data-mdb-ripple-color="light">
                                    <img class="img-fluid" :src="item.img"/>
                                    <a href="#!">
                                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <p :id="`collapse-${item.id}`" class="card-text collapse">
                                        <div class="people" v-for="person in item.people" :key="person">
                                            <vue-gravatar class="rounded-circle" :id="person.user.id" :email="person.user.email" :title="person.user.name.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())" :size="24" />
                                        </div>
                                    </p>
                                    <div class="d-flex justify-content-between">
                                    <a class="btn btn-link link-danger p-md-1 my-1" 
                                        :data-bs-target="`#collapse-${item.id}`" 
                                        data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="collapseContent">
                                        (<span class="count">{{item.likes}}</span>) people who likes this.
                                    </a>
                                    <div>
                                        <i class="fas fa-heart text-muted p-md-1 my-2 me-0" 
                                        :class="{ active: item.isLike}"
                                        :attribute="item.name"
                                        @click="toggleLike(item.id)"
                                        title="I like it"></i>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button @click="loadPrev()" class="me-1 btn btn-secondary" :disabled="start === 0 || toggledPrev > 0">Prev</button>
                            <button @click="loadNext()" class="ms-1 btn btn-secondary" :disabled="ended > 0 || toggledNext > 0">Next</button>
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
        data: () => ({
            start: 0,
            end: 8,
            ended: 0,
            breeds: [],
            toRender: [],
            toggledPrev: 0,
            toggledNext: 0
        }),
        mounted() {
            const fetchData = async() => {
                try {
                    const response = await fetch(`https://dog.ceo/api/breeds/list/all`);
                    const data = await response.json();
                    console.log(data.message);
                    for (var breed in data.message) {
                        if (data.message.hasOwnProperty(breed)) {
                            this.breeds.push(breed);
                        }
                    }
                    let token = localStorage.getItem('token');
                    for (let i = this.start; i < this.end; i++) {
                        let dog = this.breeds[i];
                        const responseImg = await fetch(`https://dog.ceo/api/breed/${dog}/images/random`); 
                        const dataImg = await responseImg.json();
                        const dataLike = await API.get(`get-likes/${dog}`, { headers: {"Authorization" : `Bearer ${token}`} })
                        this.toRender.push({
                            id: i,
                            name: dog,
                            img: dataImg.message,
                            likes: dataLike.data.count,
                            isLike: dataLike.data.isLike,
                            people: dataLike.data.people
                        })
                    }
                    console.log('start: ' + this.start + ', end:' + this.end);
                } catch (error) {
                    console.log(error);
                }
            }
            fetchData();
        },
        methods: {
            loadNext() {
                const fetchDataNext = async() => {
                    let token = localStorage.getItem('token')
                    this.toggledNext = 1;
                    this.toRender = [];
                    this.start += 8;
                    this.end = this.start + 8;
                    for (let i = this.start; i < this.end; i++) {
                        let dog = this.breeds[i];
                        if (dog) {
                            const responseImg = await fetch(`https://dog.ceo/api/breed/${dog}/images/random`); 
                            const dataImg = await responseImg.json();
                            const dataLike = await API.get(`get-likes/${dog}`, { headers: {"Authorization" : `Bearer ${token}`} })
                            this.toRender.push({
                                id: i,
                                name: dog,
                                img: dataImg.message,
                                likes: dataLike.data.count,
                                isLike: dataLike.data.isLike,
                                people: dataLike.data.people
                            })
                        } else {
                            this.ended++;
                            break;
                        }
                    }
                    this.toggledNext = 0;
                    console.log('start: ' + this.start + ', end:' + this.end)
                }
                fetchDataNext();
            },

            loadPrev() {
                const fetchDataPrev = async() => {
                    let token = localStorage.getItem('token')
                    this.toggledPrev = 1;
                    this.toRender = [];
                    this.start -= 8;
                    this.end = this.start + 8;
                    this.ended = 0;
                    for (let i = this.start; i < this.end; i++) {
                        let dog = this.breeds[i];
                        if (dog) {
                            const responseImg = await fetch(`https://dog.ceo/api/breed/${dog}/images/random`); 
                            const dataImg = await responseImg.json();
                            const dataLike = await API.get(`get-likes/${dog}`, { headers: {"Authorization" : `Bearer ${token}`} })
                            this.toRender.push({
                                id: i,
                                name: dog,
                                img: dataImg.message,
                                likes: dataLike.data.count,
                                isLike: dataLike.data.isLike,
                                people: dataLike.data.people
                            })
                        }
                    }
                    this.toggledPrev = 0;
                    console.log('start: ' + this.start + ', end:' + this.end)
                }
                fetchDataPrev();
            },

            toggleLike(id) {
                let token = localStorage.getItem('token')
                let dog = this.breeds[id];
                let post = { breed: dog }
                API.post(`toggle-like`, post, { headers: {"Authorization" : `Bearer ${token}`} })
                .then(response => {
                    console.log(response)
                    let result = response.data
                    if(result.status === 'ok') {
                        if (result.like) {
                            document.querySelector("#card-" + id + ' i.fa-heart').classList.add('active');
                        } else {
                            document.querySelector("#card-" + id + ' i.fa-heart').classList.remove('active');
                        }
                        document.querySelector("#card-" + id + ' span.count').innerHTML = result.count;
                        document.querySelector("#card-" + id + ' .card-text').innerHTML = '<div class="people">' + result.people + '</div>';
                    } else {
                        this.$swal({
                            icon: 'error',
                            title: 'Oops...',
                            type: 'error',
                            text: result.messages,
                            confirmButtonColor: '#f27474'
                        });
                    }
                })
            }
        }
    }
</script>