<template>
    <main class="text-center">
        <div class="container">
            <form class="form-signin w-100">
                <img class="mb-4" src="../../../img/logo.png" alt="" width="134" height="32">
                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" v-model="user.email" placeholder="Email">
                    <label for="email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" v-model="user.password" placeholder="Password">
                    <label for="password">Password</label>
                </div>

                <div class="checkbox mb-3">
                    <a href="/#/register">not registered?</a>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="button" @click="login()">Sign In</button>
                <p class="mt-5 mb-3 text-muted">Copyright &copy; 2023</p>
            </form>
        </div>
    </main>
</template>

<script>
    import API from '../Api';
    import Auth from '../Auth.js';

    export default {
        data() {
            return {
                user: {
                    username: '',
                    password: '',
                }
            };
        },

        methods: {
            login() {
                API.post(`login`, this.user)
                .then(({data}) => {
                    console.log(data);
                    Auth.login(data.authorisation.token,data.user); //set local storage
                    this.$router.push('/dashboard');
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>