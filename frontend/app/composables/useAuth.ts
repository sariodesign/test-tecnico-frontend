import type { User } from '~/types/user'

export function useAuth() {
    const user = useState<User | null>('user', () => null)
    const isLoggedIn = computed(() => user.value !== null)

    async function login(email: string, password: string) {
        const { user: loggedUser } = await $fetch<{ user: User }>('/api/auth/login', {
            method: 'POST',
            body: { email, password },
        })
        user.value = loggedUser
    }

    async function fetchUser() {
        const requestFetch = useRequestFetch()
        const { user: fetched } = await requestFetch<{ user: User | null }>('/api/auth/me')
        user.value = fetched
    }

    async function logout() {
        await $fetch('/api/auth/logout', { method: 'POST' })
        user.value = null
        await navigateTo('/')
    }

    return {
        user,
        isLoggedIn,
        login,
        fetchUser,
        logout
    }
}