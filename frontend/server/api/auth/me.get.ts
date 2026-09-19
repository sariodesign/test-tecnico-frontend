export default defineEventHandler(async (event) => {
    const config = useRuntimeConfig()
    const token = getCookie(event, 'auth_token')

    if (!token) {
        return { user: null }
    }

    try {
        const response = await $fetch<{ data?: unknown } & Record<string, unknown>>(
            `${config.laravelApiUrl}/user`,
            {
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${token}`,
                },
            }
        )

        const user = (response && 'data' in response && response.data)
            ? response.data
            : response

        return { user }
    } catch (error: any) {
        if (error?.response?.status === 401) {
            deleteCookie(event, 'auth_token')
            return { user: null }
        }
        throw error
    }
})