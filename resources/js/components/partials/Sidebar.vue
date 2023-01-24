<template>
    <ul class="navbar-nav sidebar sidebar-dark accordion sidebar-theme" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white" href="javascript:;">
            <div class="sidebar-brand-text">
                <img class="mt-3 mb-3" src="/img/logo.png" alt="" width="134" height="32"/>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <li class="nav-item" :class="{ active: this.$route.name === link.name }" v-for="link in links" :key="link">
            <a v-if="link.function === ''" class="nav-link" :href="link.slug">
                <i :class="link.icons"></i>
                <span>{{ link.name }}</span>
            </a>
            <a v-else class="nav-link" :href="link.slug" @click="logout()">
                <i :class="link.icons"></i>
                <span>{{ link.name }}</span>
            </a>
        </li>
    </ul>
</template>

<script>
    import API from '../../Api';
    import Auth from '../../Auth.js';

    export default {
        data: () => ({
            user: Auth.user,
            links: [
                {
                    name: "Dashboard",
                    slug: "#/dashboard",
                    icons: "fas fa-fw fa-tachometer-alt",
                    function: ""
                },
                {
                    name: "Profile Settings",
                    slug: "#/profile-settings",
                    icons: "fas fa-fw fa-user",
                    function: ""
                },
                {
                    name: "Signout",
                    slug: "javascrip:;",
                    icons: "fas fa-fw fa-sign-out-alt",
                    function: "logout"
                }
            ]
        }),

        mounted() {
        },

        methods: {
            logout() {
                let token = localStorage.getItem('token')
                API.post(`logout`, { headers: {"Authorization" : `Bearer ${token}`} })
                .then(({data}) => {
                    Auth.logout()
                    this.$router.push('/login')
                    console.log(data)
                })
                .catch((error) => {
                    console.log(error)
                });
            }
        }
    }
</script>