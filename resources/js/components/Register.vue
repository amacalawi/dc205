<template>
    <main class="text-center">
        <div class="container">
            <form class="form-signin register w-100">
                <img class="mb-4" src="../../../img/logo.png" alt="" width="134" height="32">
                <h1 class="h3 mb-3 fw-normal">Sign Up</h1>
                <div class="form-floating">
                    <input type="text" class="form-control" id="fullname" v-model="user.name" placeholder="Fullname">
                    <label for="fullname">Fullname</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" v-model="user.email" placeholder="Email">
                    <label for="email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" v-model="user.password" placeholder="Password">
                    <label for="password">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="confirm-password" v-model="user.password_confirmation" placeholder="Confirm Password">
                    <label for="confirm-password">Confirm Password</label>
                </div>
                <div v-if="error.message" class="alert mt-3" :class="[error.type]" role="alert">
                    {{error.message}}
                </div>
                <div class="checkbox mb-3">
                    <a href="/#/login">back to login</a>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="button" @click="register()">Sign up</button>
                <p class="mt-5 mb-3 text-muted">Copyright &copy; 2023</p>
            </form>
        </div>
    </main>
</template>

<script>
    import API from '../Api';
    export default {
        data() {
            return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                error: {
                    type: '',
                    message: ''
                }
            };
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
            
            register() {
                console.log(this.user);
                API.post(`register`, this.user)
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